<?php

/**
 *
 * Classe que resolve a view simplesmente procurando um script php que responda pela url
 * Utiliza o sistema de busca inversa
 *
 * @author renatomiawaki
 *
 * Upgrade! Agora evitando loop utilizando session para quando se usa o metodo de pegar content por get_file_contents.
 * Pois se a pessoa configurar errado e apontar para a própria pasta e cair de novo na view que faz a chamada, ele poderia travar o sistema
 * @version 1.1
 *
 *
 */
class ARMSimplePHPResult extends ARMBaseModuleAbstract implements ARMViewResolverInterface {


    public function hasLogin(){

//        try{
//            BetterDev\AccountClientSDK\AccountApiClientUser::getToken();
//        }
//        catch (Exception $e){
//            BetterDev\AccountClientSDK\AccountApiClientUser::getSession()
//        }

        if(!UserInfoSession::isAlive() ){
            $redirect = ARMNavigation::getAppUrl() ;
            $url = AccountManagerModule::getInstance()->getRedirectUrlToAccount() ;
            ARMNavigation::redirect($url, TRUE);
            return false ;
        }

        return true;
    }

    /**
     * exibe as mensagens do ARMReturnResultVO com estilo
     * @param $result
     * @param array $arrayPathFolder é a array da requisição para busca lógica
     * @throws ErrorException
     */
    public function show( $result , $arrayPathFolder ){

        if (!$this->hasLogin()){
            throw new Exception('Sem informações de login');
        }

		$code = ARMDataHandler::forceInt( ARMDataHandler::getValueByStdObjectIndex( $result , "code" ) ) ;

		//d($arrayPathFolder);
        $HtmlResult = $result ;
        ARMDebug::ifPrint( $this->getFolderView() , "arm_view" );
        $searchFileResult 			= ARMFileFinder::searchByFolder( $this->getFolderView() , $arrayPathFolder ) ;
		ARMDebug::ifPrint( $arrayPathFolder , "arm_view" ) ;
        $ASSET_PATH 				= ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->asset_path) ;

        $FOLDER_VIEW 				= $this->getFolderView() ;

        $APP_URL 					= ARMConfig::getDefaultInstance()->getAppUrl() ;
        if( $code != 200 ){
			//deu erro, exibe o erro
			dd( $result ) ;
		}
        $CURRENT_CONTROLLER_URL 	= ARMNavigation::getCurrentControllerURL() ;
		ARMDebug::ifLi( "folder view $FOLDER_VIEW " , "arm_view") ;
		ARMDebug::ifLi( "app url  $APP_URL " , "arm_view") ;
		ARMDebug::ifLi( "asset path $ASSET_PATH " , "arm_view") ;
        if( ! $searchFileResult ){

            //não encontrou nenhuma view para resover a página
            if( ARMConfig::getDefaultInstance()->isDev() ){
				d( $this->_config ) ;
                ARMDebug::error( "Warning! View not found!" ) ;
                ARMDebug::error( $this->getFolderView() );
                ARMDebug::dump( $HtmlResult ) ;
                die ;
            }
            //página não encontrada...

            throw new ErrorException( "View Not Found ! " ) ;
            die ;
        }
        ARMClassIncludeManager::loadByFile( $searchFileResult , $includeFile = FALSE );

        include $searchFileResult ;

    }

    /**
     * @param string $relative_path
     * @return string
     */
    public function getFolderView( $relative_path = "" ){
        return ARMDataHandler::removeDoubleBars( $this->getConfig()->view_folder."/".$relative_path ) ;
    }
    /**
     * @return ARMSimplePHPResultConfigVO
     */
    protected function getConfig(){
        return $this->_config ;
    }
    /**
     *
     * @return ARMSimplePHPResultVO
     */
    public function getParsedConfigData( $configResult ){
        $config = new ARMSimplePHPResultConfigVO() ;
        $config->parseObject( $configResult ) ;

        return $config ;
    }

	/**
	 * Para funcionar precisa iniciar a session antes de chamar esse metodo
	 * @return bool
	 */
	public static function isLoop(){
		if(!session_id()){
			//não tem como saber sem session
			return ;
		}
		$total = ARMSession::getVar("ARM_SIMPLE_PHP_RESULT_LOOP_COUNT");
		if(!$total){
			$total++;
			ARMSession::setVar("ARM_SIMPLE_PHP_RESULT_LOOP_COUNT", $total) ;
		}
		if($total > 50){
			return TRUE;
		}
		return FALSE;
	}
    public static function getPageResult( $project_folder ){
		if(self::isLoop()){
			return "loop";
		}

		$url = ARMConfig::getDefaultInstance()->getAppUrl() . $project_folder ;
		echo file_get_contents( $url ) ;
        return ;



        //abaixo outra maneira otimizada de fazer
        $armConfigVO = ARMConfig::getDefaultInstance()->getCurrentConfigVO();
        $vo = new ARMConfigVO();
        $vo->parseObject( $armConfigVO ) ;
        new ARMHttpRequestController( $vo , $project_folder ) ;
    }

    public static function getFullUrlResult( $full_project_folder ){

	    $opts = array( 'http'=>array( 'method'=>"GET",
		    'header'=>
			    "Cookie: ".session_name()."=".session_id()."\r\n" ) );

	    $context = stream_context_create($opts);
	    session_write_close();   // this is the key

        echo file_get_contents( $full_project_folder , FALSE, $context ) ;
    }

    /**
     * @return string
     */
    public function getAppUrl(){
        return ARMConfig::getDefaultInstance()->getAppUrl() ;
    }


    /**
     * @return string
     */
    public function getAssetPath(){
        return ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->view_folder) ;
    }


    /**
     * @return string
     */
    public function getCurrentControllerUrl(){
        return ARMNavigation::getCurrentControllerURL() ;
    }


}
