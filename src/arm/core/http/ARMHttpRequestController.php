<?php

include_once "arm/core/interface/ARMAutoParse.abstract.php";
include_once "arm/core/modules/http/ARMConfig.class.php";
include_once 'arm/core/interface/ARMPoolManager.interface.php';

include_once "arm/core/http/ARMReturnSearchClass.vo.class.php";
include_once 'arm/core/interface/ARMSingleton.interface.php';
include_once 'arm/core/modules/interface/ARMModule.interface.php';
include_once 'arm/core/application/ARMBaseSingleton.abstract.php';
include_once 'arm/core/modules/ARMModuleManager.class.php';
include_once "arm/core/modules/ARMBasePoolManager.abstract.php";

include_once "arm/core/utils/ARMControllerLinkMaker.class.php";
include_once "arm/core/utils/ARMNavigation.class.php";
include_once "arm/core/utils/ARMDebug.class.php";

include_once 'arm/core/utils/handler/ARMDataIntHandler.class.php';
include_once 'arm/core/utils/handler/ARMDataNumberHandler.class.php';
include_once 'arm/core/utils/handler/ARMDataCharHandler.class.php';
include_once 'arm/core/utils/handler/ARMDataStringHandler.class.php';
include_once "arm/core/utils/handler/ARMDataHandler.class.php";
include_once "arm/core/utils/class/ARMClassHandler.class.php";
include_once "arm/core/utils/class/ARMClassIncludeManager.class.php";

include_once "arm/core/utils/ARMFileFinder.class.php";

include_once "arm/core/vo/ARMReturnResult.vo.php";
include_once "arm/core/view/inteface/ARMViewResolver.interface.php";

include_once "arm/core/view/ARMViewManager.class.php";

include_once 'arm/core/modules/http/ARMHttpRequestData.vo.php';

set_error_handler("ARMHttpRequestController::errorHandler", E_ALL);

/**
 * Class ARMHttpRequestController
 * @version : 1.3
 *
 * Warnings
 * Add the HTTP Status code from the response to the code property
 */
class ARMHttpRequestController
{
	/**
	 * @var ARMConfig
	 */
	public static $lastConfigInstance;
	/**
	 * @var array
	 */
	public static $foldersArray;

	/**
	 * variavel local informando qual a controller que resolveu a requisição
	 * @var string
	 */
	protected $resolvedClassName;
	protected $resolvedMethodName;
	protected $returnType;

	/**
	 *
	 * @param ARMConfigVO $custonConfig
	 * @param string $custom_folder_request ex: "user/info/"
	 * @throws ErrorException
	 */
	public function __construct(ARMConfigVO $custonConfig = NULL, $custom_folder_request = NULL)
	{
		################################################ <config setup> ################################################
		if ($custonConfig) {
			//foi setado um config custon e vai pegar uma instancia baseado nesse config
			//depois setar ele como default
			$instance = ARMConfig::getInstaceByConfigVO($custonConfig);
			ARMConfig::setDefaultInstance($instance);
		} else {
			//se não foi setado o configVO personalizado, vai buscar baseado na requisição
			$configInstance = ARMConfig::getInstance($_SERVER['REQUEST_URI']);
			ARMConfig::setDefaultInstance($configInstance);
		}
		$config = ARMConfig::getDefaultInstance();
		if ($config->getCurrentConfigVO()->redirect_to) {
			ARMNavigation::redirect($config->getCurrentConfigVO()->redirect_to, TRUE);
		}
		self::$lastConfigInstance = $config;
		################################################ </config setup> ################################################

		################################################ <url> ################################################
		$rewriteClass = ARMConfig::getDefaultInstance()->getRewriteHandler();
		if ($rewriteClass) {
			ARMClassIncludeManager::load($rewriteClass);
			$rewriteInstance = new $rewriteClass();
			ARMNavigation::setRewriteHandler($rewriteInstance);

		}
		$initFolder = ARMConfig::getDefaultInstance()->getArrayRequestRangeInit();
		$rangeFolder = ARMConfig::getDefaultInstance()->getArrayRequestRangeMax();

		//se foi enviado um custom folder request, utiliza e não pega da navegação natural
		$folders_array = ($custom_folder_request) ?
			explode("/", $custom_folder_request) :
			ARMNavigation::getURI(ARMConfig::getDefaultInstance()->getAppUrl(), ARMNavigation::URI_RETURN_TYPE_ARRAY, $rangeFolder, $initFolder);
		self::$foldersArray = $folders_array;

		ARMNavigation::$arrayVariable = ARMNavigation::getVariableArraySlug($folders_array);
		ARMNavigation::$arrayRestFolder = $folders_array;
		################################################ </url> ################################################

		$ARMHttpRequestDataVO = new ARMHttpRequestDataVO();
		$ARMHttpRequestDataVO->code = 200;

		################################################ <CONTROLLER> ################################################
		if (ARMConfig::getDefaultInstance()->useController()) {
			//CHAMA O METODO QUE DEVE SER CHAMADO PARA TODA APLICAÇÃO
			$this->callInit();

		}

		################################################ <ACL url> ################################################
		if ($this->getAccessController()) {
			$ARMHttpRequestDataVO = $this->_accessControll->requestAccessResult();
			if (!$ARMHttpRequestDataVO) {
				throw new ErrorException("requestUrlAccess need to be return ARMRequestDataVO", 500);
			}
		}
		################################################ </ACL url> ################################################

		################################################ <CONTROLLER> ################################################
		ARMNavigation::$arrayRequest = $folders_array;
		if (ARMConfig::getDefaultInstance()->useController() && $ARMHttpRequestDataVO->code == 200) {

			//inicia e pega resultado da controller
			try {
				$ARMHttpRequestDataVO = $this->getControllerResult($folders_array);
			} catch (ErrorException $e) {
				self::errorExceptionHandler($e);
				die;
			}

		}
		################################################ </CONTROLLER> ################################################

		################################################ <LOG> ################################################
		//TODO: fazer log automático baseado num módulo personalizado? definir.
//		if( ARMConfig::getDefaultInstance()->getLogModule() ){
//			$logInfo = new ARMLogInfoVO() ;
//			$logInfo->data = $ARMHttpRequestDataVO ;
//			$logInfo->data_resolver_class = $this->resolvedClassName ;
//			$logInfo->action = $this->resolvedMethodName ;
//			$logInfo->ref_alias = implode( "/", $folders_array ) ;
//		}
		################################################ </LOG> ################################################

		################################################ <VIEW> ################################################
		self::renderView($ARMHttpRequestDataVO);
		################################################ </VIEW> ################################################
	}

	protected static function renderView($ARMHttpRequestDataVO)
	{

		if (ARMConfig::getDefaultInstance()->useView()) {

			$returnType = self::getReturnType();


			if (!$returnType) {
				// (pt-br) O tipo de retorno não foi implementado
				header("HTTP/1.0 400 Bad Request");
				die;
			}

			$viewList = ARMConfig::getDefaultInstance()->getViewModuleList();
			foreach ($viewList as $alias => $ARMViewResolverInterfaceClass) {
				ARMViewManager::add($ARMViewResolverInterfaceClass, $alias);
			}


			$armView = ARMViewManager::getByAlias($returnType);
			// se nenhuma view resolver for encontrada para o tipo de retorno esperado
			if (!$armView) {
				throw new ErrorException("No ARMViewResolverInterface instaled for a [$returnType] result request");
			}

			//carrega a classe
			ARMClassIncludeManager::load($armView);
			//verifica se a classe implementa a interface necessária
			if (!ARMClassHandler::classImplements($armView, "ARMViewResolverInterface")) {
				throw new ErrorException("The class $armView need to implement ARMViewResolverInterface interface ");
			}

			//pega a instancia da classe
			if (ARMConfig::getDefaultInstance()->viewResolverByPath()) {
				$viewResolver = call_user_func($armView . "::getInstanceByPath", ARMNavigation::$arrayRestFolder);
			} else {
				$viewResolver = call_user_func($armView . "::getInstance");
			}
			//utiliza o metodo da interface que deve exibir e resolver o conteúdo

			$viewResolver->show($ARMHttpRequestDataVO, self::$foldersArray);
		}
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
	private function getAccessController()
	{
		if ($this->_accessControll === NULL) {
			//verifica se foi passada a classe no config
			$accessControll = ARMConfig::getDefaultInstance()->getRequestAccessControll();
			if ($accessControll) {
				//carrega a classe
				ARMClassIncludeManager::load($accessControll);
				//verifica se ela implementa ARMRequestAccessControllInterface
				if (ARMClassHandler::classImplements($accessControll, "ARMRequestAccessControllInterface")) {
					$instance = new $accessControll();
					$this->_accessControll = $instance;
					return $this->_accessControll;
				}
			}
			$this->_accessControll = FALSE;
		}

		return $this->_accessControll;
	}

	private static $_viewType;

	/**
	 *
	 * @return string
	 */
	private static function getReturnType()
	{
		if (!self::$_viewType) {
			$returnTypeResolver = ARMConfig::getDefaultInstance()->getHttpReturnIndentifierModule();

			if (!ARMClassIncludeManager::load($returnTypeResolver)) {
				return NULL;
			}
			if (ARMClassHandler::classImplements($returnTypeResolver, "ARMHttpReturnIndentifierInterface")) {
				$HttpReturnIndentifier = call_user_func($returnTypeResolver . "::getInstance");

				self::$_viewType = $HttpReturnIndentifier->getType();

			}
		}
		return self::$_viewType;
	}

	/**
	 *
	 * Start Application settings
	 * Aways execute applicationInit of DefaultController class
	 */
	private function callInit()
	{

		$className = ARMConfig::getDefaultInstance()->getDefaultController();

		if ($className == "") {
			throw new ErrorException(" Config:: DEFAULT_CONTROLLER undefined ");
		}
		if (!ARMClassIncludeManager::load($className)) {
			dd("Classe $className não encontrada.");
		}

		if (!class_exists($className)) {
			dd("Classe $className não existe!");
		}
		$retorno = call_user_func("{$className}::applicationInit");

	}

	/**
	 * inicia a controller conforme configurado em navigation e retorna o resultado do metodo chamado
	 * @return ARMHttpRequestDataVO
	 */
	private function getControllerResult($folders_array)
	{

		// inicia a busca da controller
		$ARMHttpRequestDataVO = new ARMHttpRequestDataVO();
		ARMDebug::ifLi("FolderRequestController : " . ARMConfig::getDefaultInstance()->getFolderRequestController(), "debug_request");
		$retornoDaController = self::searchController($folders_array, ARMConfig::getDefaultInstance()->getFolderRequestController());

		if (!$retornoDaController->success) {

			$retornoDaController->className = ARMConfig::getDefaultInstance()->getDefaultController();
			$retornoDaController->methodName = "init";
			$retornoDaController->success = TRUE;

		}
		$className = $retornoDaController->className;
		$methodName = $retornoDaController->methodName;

		ARMDebug::ifPrint($folders_array, "debug_controller");

		if (!ARMClassIncludeManager::load($className)) {
			//erro de instalação do arm, a classe setada para resolver não foi encontrada
			throw new ErrorException($className . " not found");
		}

		//verifica se o metodo que seria a intenção de acesso é publico e se existe
		if (!ARMClassHandler::isMethodPublic($className, $methodName)) {
			$methodName = "init";
		}
		if (!ARMClassHandler::hasMethod($className, $methodName)) {
			$methodName = NULL;
		}

		ARMNavigation::$controllerInfo = $retornoDaController;

		$arrayRestFolder = $retornoDaController->arrayRestFolder;

		$totalRest = count($arrayRestFolder);
		if ($totalRest > 0 && $arrayRestFolder[0] == $methodName) {
			$arrayRestFolder = array_slice($arrayRestFolder, 1, $totalRest);
		}
		//Seta para navigation o array restfolder
		/**
		 * @TODO: no getVariableArraySlug o separator deve vir de uma classe que reolve isso
		 */
		ARMNavigation::$arrayVariable = ARMNavigation::getVariableArraySlug($arrayRestFolder);
		ARMNavigation::$arrayRestFolder = $arrayRestFolder;


		$returnType = self::getReturnType();

		//salva as infos locais
		$this->resolvedClassName = $className;
		$this->resolvedMethodName = $methodName;
		$this->returnType = $returnType;
		// AQUI O ACL FILTER
		$requestAccessControll = $this->getAccessController();
		if ($requestAccessControll) {
			//precisa de controle de acesso
			if (!$this->_accessControll->hasAccess($className, $methodName, $returnType)) {
				//acesso não permitido a essa controller
				$ARMHttpRequestDataVO = new ARMHttpRequestDataVO();
				$ARMHttpRequestDataVO->code = 503;
				return $ARMHttpRequestDataVO;
			}
		}

		$instancia = new $className();
		//se foi setado um metodo, ou seja, não é nulo, acessa e pega o result
		if ($methodName)
			$ARMHttpRequestDataVO->result = $instancia->$methodName();

		$ARMHttpRequestDataVO->code = $this->getHttpStatusCode();
		return $ARMHttpRequestDataVO;
	}

	private function getHttpStatusCode()
	{
		$status = http_response_code();
		if ($status === false)
			return 200;
		return $status;
	}

	/**
	 * @param $array_url tem que ser passado o retorno do ARMNavigation::getURI()
	 * @return ARMReturnSearchClassVO
	 * @desc metodo para buscar controller baseado na url passada
	 */
	private static function searchController($array, $_startFolder = "")
	{
		// @UPGRADE!
		//iniciando o objeto de retorno
		$returnReturnSearchClassVO = new ARMReturnSearchClassVO();
		$searchFileOrFolderName = ARMConfig::getDefaultInstance()->getDefaultController();
		//pra otimizar
		$arrayCount = count($array);
		$i = $arrayCount - 1;
		$currentFolder = "";
		if ($i >= 0) {

			while (!$returnReturnSearchClassVO->success && $i >= 0) {

				$stringPath = implode("/", array_slice($array, 0, $i));

				$currentFolder = ARMDataHandler::removeSpecialCharacters($array[$i]);
				//procurando folder
				$searchFileOrFolderName = ARMDataHandler::urlFolderNameToClassName($currentFolder);

				if ($searchFileOrFolderName == "") {
					//o nome do arquivo é nada, próxima...
					$i--;
					continue;
				}
				//busca o arquivo
				$folderController = ARMDataHandler::removeDoubleBars($_startFolder . "/" . $stringPath . "/" . $searchFileOrFolderName . ".php");

				ARMDebug::ifLi("Buscando controller: " . $folderController);

				$returnReturnSearchClassVO->success = file_exists($folderController);
				if (!$returnReturnSearchClassVO->success)
					$i--;
			}

		} // end if exite algo na array folder
		//não encontrou controller então a currentFolder nao existe
		if (!$returnReturnSearchClassVO->success) {
			$currentFolder = "";
		}
		$resolvedControllerFolder = ARMConfig::getDefaultInstance()->getAppUrl($currentFolder);
		ARMNavigation::$urlResolvedController = $resolvedControllerFolder;

		$tempMetodo = "init";
// 		ARMDebug::print_r( $arrayCount );
// 		ARMDebug::print_r($i );
		if (($i + 1) < $arrayCount) {
			// verifica se a próxima pasta exite, sendo assim, ela seria o nome sugerido para o metodo procurado
			$tempMetodo = ARMDataHandler::urlFolderNameToMethodName($array[$i + 1]);
		}
// 		ARMDebug::print_r( $tempMetodo );

		$arrayRestFolder = array_slice($array, $i + 1, $arrayCount);

		$returnReturnSearchClassVO->className = $searchFileOrFolderName;
		$returnReturnSearchClassVO->methodName = $tempMetodo;
		$returnReturnSearchClassVO->arrayRestFolder = $arrayRestFolder;

		return $returnReturnSearchClassVO;
	}

	/**
	 * Tratamento de erros e excessão
	 * @param $errno
	 * @param $errstr
	 * @throws ErrorException
	 */
	public static function errorHandler($errno, $errstr)
	{
		if (!self::$lastConfigInstance || !self::$lastConfigInstance->getCurrentConfigVO()) {
			throw new ErrorException($errstr, $errno);
			die;
		}
		if (self::$lastConfigInstance->getCurrentConfigVO()->throwErrors) {
			throw new ErrorException($errstr, $errno);
			die;
		}
		self::displayErrorOnView($errno, $errstr);
	}

	public static function errorExceptionHandler(ErrorException $e)
	{

		if (!self::$lastConfigInstance || !self::$lastConfigInstance->getCurrentConfigVO()) {
			throw $e;
			die;
		}
		if (self::$lastConfigInstance->getCurrentConfigVO()->throwErrors) {
			throw $e;
			die;
		}
		self::displayErrorOnView($e->getCode(), $e->getMessage());
	}

	/**
	 * @param $errno
	 * @param $errstr
	 * @throws ErrorException
	 */
	public static function displayErrorOnView($errno, $errstr)
	{
		$result = new ARMReturnResultVO();
		$result->result = $errno;
		$result->addMessage($errstr);
		$ARMHttpRequestDataVO = new ARMHttpRequestDataVO();
		//internal server error 500
		$ARMHttpRequestDataVO->code = 500;
		//resultado em ARMReturnResultVO
		$ARMHttpRequestDataVO->result = $result;
		self::renderView($ARMHttpRequestDataVO);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
