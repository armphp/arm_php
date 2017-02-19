<?php

/**
 * (pt-br) 	Classe que resolve resultados que atendem por http
 * 			Essa é a penas uma possibilidade, caso precise de um tipo específico, crie sua class e sete no config
 * @author renatomiawaki
 *
 */
class ARMSmartViewModule extends ARMBaseModuleAbstract implements ARMViewResolverInterface  {

	const DEBUG_VAR = "smart" ;

	/**
	 *
	 * @var ARMSmartViewConfigVO
	 */
	protected  $_config;

	/**
	 * @param string $alias
	 * @return ARMSmartViewModule
	 */
	public static function getInstance($alias = self::DEFAULT_GLOBAL_ALIAS) {
		return parent::getInstance($alias);
	}
	/**
	 * @param string $alias
	 * @return ARMSmartViewModule
	 */
	public static function getLastInstance()
	{
		return parent::getLastInstance();
	}
	/**
	 * @param string $alias
	 * @return ARMSmartViewModule
	 */
	public static function getInstaceByConfigVO($configVO, $alias = self::DEFAULT_GLOBAL_ALIAS){
		return parent::getInstaceByConfigVO($configVO, $alias);
	}
	/**
	 * @param string $alias
	 * @return ARMSmartViewModule
	 */
	public static function getInstanceByPath($pathToFindConfig) {
		return parent::getInstanceByPath($pathToFindConfig);
	}


	/**
	 *
	 * @return ARMSmartViewConfigVO
	 */
	public function getConfig(){
		return $this->_config;
	}

	/**
	 * @param $result HttpResponse
	 * @param array $arrayPathFolder
	 * @throws ErrorException
	 */
	public function show( $result, $arrayPathFolder ) {
		$BaseContentViewVO = new ARMBaseContentViewVO() ;

		$BaseContentViewVO->data = $result ;

		if( !is_null( $this->_config->defaultViewController  ) ){
			ARMClassIncludeManager::loadByFile( ARMDataHandler::removeDoubleBars( $this->_config->getViewControllerFolder()  . "/" . $this->_config->defaultViewController . ".class.php" ) ) ;
			$BaseContentViewVO->global_data = call_user_func($this->_config->defaultViewController . "::getGlobalData" ) ;
		}

		//Search and uses an valid CommonData class
		$BaseContentViewVO->common_view_data = ARMSmartCommonDataManager::getData( $this->_config , $arrayPathFolder , $BaseContentViewVO->data )  ;



		//Search and uses an valid ViewController
		$BaseContentViewVO->view_data = ARMSmartViewControllerManager::getData( $this->_config , $arrayPathFolder , $BaseContentViewVO->data , $BaseContentViewVO->common_view_data )  ;

		$ViewFile = ARMFileFinder::searchByFolder( $this->_config->getViewFolder() , $arrayPathFolder, "index.php") ;



		$BaseContentViewVO->asset_path	= ARMConfig::getDefaultInstance()->getRootUrl($this->_config->getAssetsFolder() ) ;

		$BaseContentViewVO->folder_view = $this->_config->getViewFolder() ;

		$BaseContentViewVO->app_url		= ARMConfig::getDefaultInstance()->getAppUrl() ;

		$BaseContentViewVO->current_controller_url = ARMNavigation::getCurrentControllerURL() ;

		$ASSET_PATH 				= ARMConfig::getDefaultInstance()->getRootUrl($this->_config->getAssetsFolder() ) ;

		$FOLDER_VIEW 				= $this->_config->getViewFolder() ;

		$APP_URL 					= ARMConfig::getDefaultInstance()->getAppUrl() ;

		$CURRENT_CONTROLLER_URL 	= ARMNavigation::getCurrentControllerURL() ;


		if( !is_null( $this->_config->B4ViewMannager ) ) {
			ARMClassIncludeManager::load(  $this->_config->B4ViewMannager ) ;

			if( ! ARMClassHandler::classImplements( $this->_config->B4ViewMannager  , "ARMB4ViewManagerInterface" ) ){
				throw new ErrorException("The class {$this->_config->B4ViewMannager} need to implement ARMB4ViewManagerInterface interface ") ;
			}

			$B4View = new $this->_config->B4ViewMannager( $this->_config );
			/*@var $B4View ARMB4ViewManagerInterface */
			$B4View->loadHtmlHeader( $ViewFile , $arrayPathFolder , $BaseContentViewVO ) ;
		}


		ARMDebug::ifLi( __CLASS__ . " View File  [ $ViewFile ] " , "view") ;

// 		@TODO:   em dev pegar tipos e tipar tudo na view p/ facilitar a programacao
//		@TODO: escrebe na view os comentarios necessarios
		/* @var $this ARMSmartViewModule */

		ARMClassIncludeManager::loadByFile( $ViewFile , $includeFile = FALSE );
        $result = $BaseContentViewVO ;
        $result->controller_result = $BaseContentViewVO->data->result;
		if( $ViewFile  ){


			if( ARMConfig::getDefaultInstance()->isDev() ){
                ARMSmartViewModule::addResultComment($ViewFile, $result );
			}

			include $ViewFile  ;
		}

	}


	/**
	 * @return string
	 */
	public function getViewFolder(){
		return  $this->_config->getViewFolder() ;
	}

    /**
     * @param $file
     * @param $ARMBaseContentViewVO
     */
    public static function addResultComment( $file, $ARMBaseContentViewVO = NULL ) {
		return ;
        $end_comment = "/** SMART_VIEW_DATA EOF*/";

        $vars = self::extractObjectToComment("result", $ARMBaseContentViewVO);
        $hash = md5($vars);
		$comment = "
<?php
/** SMART VIEW VARS  #{$hash}
 * @var \$this ARMSmartViewModule
 * @var \$result ARMBaseContentViewVO
 */
{$vars}
{$end_comment}
?>" ;
        $file_contents = file_get_contents( $file );

        $comment_pos = strpos( $file_contents , $end_comment ) ;
        if( strpos( $file_contents , $hash ) === FALSE &&  $comment_pos != FALSE ){
            //deleta o primeiro bloco de comentário que é velho
            $file_contents = substr_replace($file_contents,"",0, strpos($file_contents,"?>")+2 );
        }

		if( strpos( $file_contents , $hash ) === FALSE ){
			ARMDataHandler::writeFile($file, "", trim($comment)  . "\n" .$file_contents , "w+");
		}

	}

    /**
     * @param $obj_var_name  string nome da variavel que o objeto é para ser acessado na view
     * @param $obj Object o objeto em si para ser acessado
     * @return string
     */
    private static function extractObjectToComment( $obj_var_name , $obj ){
        $return="";
        if( is_null($obj)){
            return $return;
        }


        foreach( $obj as $key=>$value ){

            if( is_null( $value ) ){
                continue;
            }



            $type = gettype($value) == "object" ?  ARMClassHandler::getClassName($value) : gettype($value);
$return.="/** @var \${$key} {$type} */
\${$key} = \${$obj_var_name}->{$key};
";

        }

        return $return;
    }

	/**
	 * (non-PHPdoc)
	 * @see ARMBaseModuleAbstract::getParsedConfigData()
	 */
	public function getParsedConfigData( $configVOFromFile ){

		$configVO  = ARMDataHandler::objectMerge( new ARMSmartViewConfigVO() ,  $configVOFromFile , TRUE, TRUE ) ;
		IF( FALSE )$configVO = new ARMSmartViewConfigVO() ;

		if( is_null( $configVO->assets_folder ) ){
			$configVO->assets_folder = $configVO->view_folder ;
		}

		if( is_null( $configVO->b4view_folder ) ){
			$configVO->b4view_folder = $configVO->view_folder ;
		}

		if( is_null( $configVO->view_controller_folder ) ){
			$configVO->view_controller_folder = $configVO->view_folder ;
		}

		if( is_null( $configVO->smart_commmon_data_folder ) ){
			$configVO->smart_commmon_data_folder = $configVO->view_folder ;
		}

		return $configVO ;

	}

}