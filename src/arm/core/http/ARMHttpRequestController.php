<?php

include_once "arm/core/interface/ARMAutoParse.abstract.php" ;
include_once "arm/core/modules/http/ARMConfig.class.php";
include_once 'arm/core/interface/ARMPoolManager.interface.php' ;

include_once "arm/core/http/ARMReturnSearchClass.vo.class.php" ;
include_once 'arm/core/interface/ARMSingleton.interface.php' ;
include_once 'arm/core/modules/interface/ARMModule.interface.php' ;
include_once 'arm/core/application/ARMBaseSingleton.abstract.php' ;
include_once 'arm/core/modules/ARMModuleManager.class.php' ;
include_once "arm/core/modules/ARMBasePoolManager.abstract.php" ;

include_once "arm/core/utils/ARMControllerLinkMaker.class.php" ;
include_once "arm/core/utils/ARMNavigation.class.php" ;
include_once "arm/core/utils/ARMDebug.class.php" ;

include_once 'arm/core/utils/handler/ARMDataIntHandler.class.php' ;
include_once 'arm/core/utils/handler/ARMDataNumberHandler.class.php' ;
include_once 'arm/core/utils/handler/ARMDataCharHandler.class.php' ;
include_once 'arm/core/utils/handler/ARMDataStringHandler.class.php' ;
include_once "arm/core/utils/handler/ARMDataHandler.class.php" ;
include_once "arm/core/utils/class/ARMClassHandler.class.php" ;
include_once "arm/core/utils/class/ARMClassIncludeManager.class.php" ;

include_once "arm/core/utils/ARMFileFinder.class.php";

include_once "arm/core/vo/ARMReturnResult.vo.php";
include_once "arm/core/view/inteface/ARMViewResolver.interface.php" ;

include_once "arm/core/view/ARMViewManager.class.php";

include_once 'arm/core/modules/http/ARMHttpRequestData.vo.php';

class ARMHttpRequestController {
	/**
	 *
	 * @param ARMConfigVO $custonConfig
	 * @param string $custom_folder_request ex: "user/info/"
	 * @throws ErrorException
	 */
	public function __construct( ARMConfigVO $custonConfig = NULL , $custom_folder_request = NULL ){



		################################################ <config setup> ################################################
		if( $custonConfig ){
			//foi setado um config custon e vai pegar uma instancia baseado nesse config
			//depois setar ele como default
			$instance = ARMConfig::getInstaceByConfigVO( $custonConfig ) ;
			ARMConfig::setDefaultInstance( $instance ) ;
		} else {
			//se não foi setado o configVO personalizado, vai buscar baseado na requisição
			$configInstance = ARMConfig::getInstance( $_SERVER[ 'REQUEST_URI' ] );
			ARMConfig::setDefaultInstance( $configInstance  ) ;
		}
		$config = ARMConfig::getDefaultInstance();
		if( $config->getCurrentConfigVO()->redirect_to ){
			ARMNavigation::redirect( $config->getCurrentConfigVO()->redirect_to , TRUE ) ;
		}

		################################################ </config setup> ################################################

		################################################ <url> ################################################
		$rewriteClass = ARMConfig::getDefaultInstance()->getRewriteHandler() ;
		if( $rewriteClass ){
			ARMClassIncludeManager::load( $rewriteClass ) ;
			$rewriteInstance = new $rewriteClass() ;
			ARMNavigation::setRewriteHandler( $rewriteInstance ) ;

		}
		$initFolder = ARMConfig::getDefaultInstance()->getArrayRequestRangeInit();
		$rangeFolder = ARMConfig::getDefaultInstance()->getArrayRequestRangeMax();

		//se foi enviado um custom folder request, utiliza e não pega da navegação natural
		$folders_array 		= ( $custom_folder_request ) ?
									explode( "/", $custom_folder_request ) :
									ARMNavigation::getURI( ARMConfig::getDefaultInstance()->getAppUrl() , ARMNavigation::URI_RETURN_TYPE_ARRAY , $rangeFolder, $initFolder ) ;
		//$folders_array 		= ARMNavigation::getURI( ARMConfig::getDefaultInstance()->getAppUrl() , ARMNavigation::URI_RETURN_TYPE_ARRAY ) ;
		//removendo pastas iniciais a serem ignoradas setadas no config como app_path
//		ARMDebug::dump(ARMConfig::getDefaultInstance()->getAppUrl());
//		ARMDebug::dump($folders_array);
//		dd( ARMConfig::getDefaultInstance()->getAppPath() );
		
		################################################ </url> ################################################


		$ARMHttpRequestDataVO = new ARMHttpRequestDataVO() ;
		$ARMHttpRequestDataVO->code = 200 ;
		
		################################################ <CONTROLLER> ################################################
		if( ARMConfig::getDefaultInstance()->useController() ) {
			//CHAMA O METODO QUE DEVE SER CHAMADO PARA TODA APLICAÇÃO
			$this->callInit();
		
		}
		
		################################################ <ACL url> ################################################
		if( $this->getAccessController() ){
			$ARMHttpRequestDataVO = $this->_accessControll->requestAccessResult() ;
			if( ! $ARMHttpRequestDataVO ){
				throw new ErrorException( "requestUrlAccess need to be return ARMRequestDataVO" , 500 ) ;
			}
		}
		################################################ </ACL url> ################################################
		 
		if( ARMConfig::getDefaultInstance()->useController() && $ARMHttpRequestDataVO->code == 200 ){
			
			//inicia e pega resultado da controller
			ARMNavigation::$arrayRequest = $folders_array;
			$ARMHttpRequestDataVO = $this->getControllerResult( $folders_array  ) ;
			
		}

		$returnType = $this->getReturnType() ;

		################################################ </CONTROLLER> ################################################


		################################################ <VIEW> ################################################
		if( ARMConfig::getDefaultInstance()->useView() ) {


			if( ! $returnType ){
				// (pt-br) O tipo de retorno não foi implementado
				header("HTTP/1.0 400 Bad Request");
				die;
			}

			$viewList = ARMConfig::getDefaultInstance()->getViewModuleList() ;
			foreach ( $viewList as $alias=>$ARMViewResolverInterfaceClass ){
				ARMViewManager::add( $ARMViewResolverInterfaceClass, $alias ) ;
			}


			$armView = ARMViewManager::getByAlias( $returnType );
			// se nenhuma view resolver for encontrada para o tipo de retorno esperado
			if( ! $armView ){
				throw new ErrorException("No ARMViewResolverInterface instaled for a [$returnType] result request") ;
			}

			//carrega a classe
			ARMClassIncludeManager::load( $armView ) ;
			//verifica se a classe implementa a interface necessária
			if( ! ARMClassHandler::classImplements( $armView , "ARMViewResolverInterface" ) ){
				throw new ErrorException("The class $armView need to implement ARMViewResolverInterface interface ") ;
			}

			//pega a instancia da classe
			if( ARMConfig::getDefaultInstance()->viewResolverByPath() ){
				$viewResolver = call_user_func( $armView."::getInstanceByPath" , ARMNavigation::$arrayRestFolder );
			}else{
				$viewResolver = call_user_func( $armView."::getInstance" );
			}
			//utiliza o metodo da interface que deve exibir e resolver o conteúdo

			$viewResolver->show( $ARMHttpRequestDataVO , $folders_array ) ;
		}
		################################################ </VIEW> ################################################
	}
	/**
	 * 
	 * @var ARMRequestAccessControllInterface
	 */
	private $_accessControll = NULL;
	/**
	 * 
	 * @return ARMRequestAccessControllInterface
	 */
	private function getAccessController(){
		if( $this->_accessControll !== NULL ){
			return $this->_accessControll ;
		}
		//verifica se foi passada a classe no config
		$accessControll = ARMConfig::getDefaultInstance()->getRequestAccessControll() ;
		if( $accessControll ){
			//carrega a classe
			ARMClassIncludeManager::load( $accessControll ) ;
			//verifica se ela implementa ARMRequestAccessControllInterface
			if( ARMClassHandler::classImplements( $accessControll , "ARMRequestAccessControllInterface" ) ){
				$instance = new $accessControll() ;
				$this->_accessControll = $instance ;
				return $this->_accessControll ;
			}
		}
		$this->_accessControll = FALSE;
		return $this->_accessControll ;
	}
	/**
	 *
	 * @return string
	 */
	private function getReturnType(){
		$returnTypeResolver = ARMConfig::getDefaultInstance()->getHttpReturnIndentifierModule() ;

		if( ! ARMClassIncludeManager::load( $returnTypeResolver ) ){
			return NULL ;
		}
		ARMClassHandler::classImplements( $returnTypeResolver , "ARMHttpReturnIndentifierInterface" ) ;
		$HttpReturnIndentifier = call_user_func( $returnTypeResolver . "::getInstance"    ) ;

		return $HttpReturnIndentifier->getType() ;
	}
	/**
	 *
	 * Start Application settings
	 * Aways execute applicationInit of DefaultController class
	 */
	private function callInit() {

		$className = ARMConfig::getDefaultInstance()->getDefaultController() ;

		if( $className == "" ){
			throw new ErrorException( " Config:: DEFAULT_CONTROLLER undefined " ) ;
		}
		ARMClassIncludeManager::load( $className ) ;
		$retorno = call_user_func( "{$className}::applicationInit" );

	}
	/**
	 * inicia a controller conforme configurado em navigation e retorna o resultado do metodo chamado
	 * @return ARMHttpRequestDataVO
	 */
	private function get503Result(){
		$ARMHttpRequestDataVO = new ARMHttpRequestDataVO() ;
		$ARMHttpRequestDataVO->code = 503 ;
		
		return $ARMHttpRequestDataVO ;
	}
	/**
	 * inicia a controller conforme configurado em navigation e retorna o resultado do metodo chamado
	 * @return ARMHttpRequestDataVO
	 */
	private function getControllerResult( $folders_array  ){
		
		// inicia a busca da controller
		$ARMHttpRequestDataVO = new ARMHttpRequestDataVO() ;
		ARMDebug::ifLi("FolderRequestController : ".ARMConfig::getDefaultInstance()->getFolderRequestController() , "debug_request");
		$retornoDaController = self::searchController( $folders_array , ARMConfig::getDefaultInstance()->getFolderRequestController() );
		
		if( ! $retornoDaController->success ){

			$retornoDaController->className 	= ARMConfig::getDefaultInstance()->getDefaultController() ;
			$retornoDaController->methodName 	= "init";
			$retornoDaController->success = TRUE ;

		}
		$className 		= $retornoDaController->className;
		$methodName 	= $retornoDaController->methodName;

		ARMDebug::ifPrint( $folders_array , "debug_controller");

		if( ! ARMClassIncludeManager::load( $className ) ){
			//erro de instalação do arm, a classe setada para resolver não foi encontrada
			throw new ErrorException( $className . " not found" );
		}

		//verifica se o metodo que seria a intenção de acesso é publico e se existe
		if( ! ARMClassHandler::isMethodPublic( $className , $methodName ) ){
			$methodName = "init" ;
		}
		if( ! ARMClassHandler::hasMethod( $className , $methodName ) ){
			$methodName = NULL ;
		}

		ARMNavigation::$controllerInfo = $retornoDaController ;

		$arrayRestFolder = $retornoDaController->arrayRestFolder;

		$totalRest = count( $arrayRestFolder ) ;
		if( $totalRest > 0 && $arrayRestFolder[0] == $methodName ){
			$arrayRestFolder 	= array_slice( $arrayRestFolder , 1, $totalRest ) ;
		}
		//Seta para navigation o array restfolder
		/**
		 * @TODO: no getVariableArraySlug o separator deve vir de uma classe que reolve isso
		 */
		ARMNavigation::$arrayVariable 		= ARMNavigation::getVariableArraySlug( $arrayRestFolder ) ;
		ARMNavigation::$arrayRestFolder 	= $arrayRestFolder ;

		$returnType = $this->getReturnType() ;


		if( $this->getAccessController() ){
			//precisa de controle de acesso
			if( ! $this->_accessControll->hasAccess( $className, $methodName, $returnType ) ){
				//acesso não permitido a essa controller
				return $this->get503Result() ;
			}
		}


		// AQUI O ACL FILTER
		$requestAccessControll = $this->getAccessController();
		if( $requestAccessControll ){
			
			if( ! $this->_accessControll->hasAccess( $className, $methodName, $returnType ) ) {
				$ARMHttpRequestDataVO->code 		= 403 ;
				$ARMHttpRequestDataVO->result 		= "Forbidden";
				return $ARMHttpRequestDataVO ;
			}
		}
		
		$ARMHttpRequestDataVO->code = 200 ;

		$instancia = new $className();
		//se foi setado um metodo, ou seja, não é nulo, acessa e pega o result
		if( $methodName )
			$ARMHttpRequestDataVO->result = $instancia->$methodName();

		return $ARMHttpRequestDataVO ;
	}
	/**
	 * @param $array_url tem que ser passado o retorno do ARMNavigation::getURI()
	 * @return ARMReturnSearchClassVO
	 * @desc metodo para buscar controller baseado na url passada
	 */
	private static function searchController( $array , $_startFolder = ""){
		// @UPGRADE!
		//iniciando o objeto de retorno
		$returnReturnSearchClassVO 		= new ARMReturnSearchClassVO();
		$searchFileOrFolderName 		=  ARMConfig::getDefaultInstance()->getDefaultController() ;;

//
//		//precisa tirar do array a sub-pasta da aplicacao
//		$application_path  = explode("/", ARMDataHandler::removeLastBar( ARMConfig::getDefaultInstance()->getRootUrl( NULL, TRUE ) ) ) ;
//		// remove o dominio do array
//		array_shift( $application_path ) ;
//
//		var_dump(  $_startFolder, $array , $application_path ) ;
//
//		die;



		//pra otimizar
		$arrayCount = count( $array ) ;
		$i = $arrayCount-1 ;
		$currentFolder = "";
		if( $i >=0 ){

			while ( ! $returnReturnSearchClassVO->success && $i >= 0 ) {

				$stringPath 	=  implode( "/" , array_slice( $array, 0, $i ) );

				$currentFolder	= ARMDataHandler::removeSpecialCharacters( $array[$i] );
				//procurando folder
				$searchFileOrFolderName = ARMDataHandler::urlFolderNameToClassName( $currentFolder );

				if( $searchFileOrFolderName == "" ){
					//o nome do arquivo é nada, próxima...
					$i-- ;
					continue;
				}
				//busca o arquivo
				$folderController = ARMDataHandler::removeDoubleBars($_startFolder."/".$stringPath."/".$searchFileOrFolderName.".php");

				ARMDebug::ifLi( "Buscando controller: " .   $folderController);

				$returnReturnSearchClassVO->success 		= file_exists( $folderController );
				if( !$returnReturnSearchClassVO->success )
					$i-- ;
			}

		} // end if exite algo na array folder
		//não encontrou controller então a currentFolder nao existe
		if( ! $returnReturnSearchClassVO->success ){
			$currentFolder = "";
		}
		$resolvedControllerFolder = ARMConfig::getDefaultInstance()->getAppUrl( $currentFolder ) ;
		ARMNavigation::$urlResolvedController = $resolvedControllerFolder ;

		$tempMetodo	= "init";
// 		ARMDebug::print_r( $arrayCount );
// 		ARMDebug::print_r($i );
		if( ($i+1) < $arrayCount ){
			// verifica se a próxima pasta exite, sendo assim, ela seria o nome sugerido para o metodo procurado
			$tempMetodo	= ARMDataHandler::urlFolderNameToMethodName( $array[$i+1] );
		}
// 		ARMDebug::print_r( $tempMetodo );

		$arrayRestFolder = array_slice( $array, $i+1 , $arrayCount );

		$returnReturnSearchClassVO->className		= $searchFileOrFolderName ;
		$returnReturnSearchClassVO->methodName		= $tempMetodo ;
		$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder ;

		return $returnReturnSearchClassVO ;
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
