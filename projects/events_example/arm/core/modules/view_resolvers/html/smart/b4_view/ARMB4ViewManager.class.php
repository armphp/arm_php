<?php

/**
 *
 * @author alanlucian
 *
 */

class ARMB4ViewManager implements ARMB4ViewManagerInterface{


	/**
	 *
	 * @var ARMSmartViewConfigVO
	 */
	protected  $_config;

	public function __construct( ARMSmartViewConfigVO $configVO ){
		$this->_config  = $configVO ;
	}


	public function loadHtmlHeader( $view_file,  $arrayPathFolder , $BaseContentViewVO ){

		$folder_path = ARMConfig::getDefaultInstance()->getTempFolder("smart_view") ;

		$headerFilePath =  $folder_path . "/" . basename( $view_file , ".php") . ".head.inc.php";

		if( file_exists( $headerFilePath ) && !ARMConfig::getLastInstance()->isDev() ){
			include $headerFilePath ;
			return $headerFilePath ;
		}


		ARMDataHandler::createRecursiveFoldersIfNotExists( $folder_path );

		$js_files = $this->findAssets( $this->_config->getAssetsFolder() . "/" . $this->_config->js_folder_name,  $this->_config->js_file_extension , $arrayPathFolder ) ;
		$js_files_on_css_folder = $this->findAssets( $this->_config->getAssetsFolder() . "/" . $this->_config->css_folder_name,  $this->_config->js_file_extension , $arrayPathFolder ) ;
		$js_files = array_merge( $js_files , $js_files_on_css_folder ) ;


		ARMDebug::ifPrint($js_files , "b4view" );



		$css_files = $this->findAssets( $this->_config->getAssetsFolder() . "/" . $this->_config->css_folder_name,  $this->_config->css_file_extension , $arrayPathFolder ) ;
		$css_files_on_js_folder = $this->findAssets( $this->_config->getAssetsFolder() . "/" . $this->_config->js_folder_name,  $this->_config->css_file_extension , $arrayPathFolder ) ;
		$css_files = array_merge( $css_files , $css_files_on_js_folder ) ;

		ARMDebug::ifPrint($css_files , "b4view" );

		$b4ViewHtml = NULL ;

		$CustomB4ViewClassFile = ARMFileFinder::searchByFolder( $this->_config->getB4viewFolder() , $arrayPathFolder, "Default.b4.php"  , "b4.php") ;

		if( $CustomB4ViewClassFile  ) {

			$CustomB4ViewClassFile =  dirname($CustomB4ViewClassFile) . "/" . ucfirst( basename( $CustomB4ViewClassFile ) ) ;
			ARMClassIncludeManager::loadByFile( $CustomB4ViewClassFile  ) ;

			$className = $this->getClassName( $CustomB4ViewClassFile ) ;


			if( $className ) {

				if(  !ARMClassHandler::classImplements( $className , "ARMB4ViewInterface" ) ) {
					throw new ErrorException(  "{$className} must implements ARMB4ViewInterface" );
				}

				ARMDebug::ifLi( __CLASS__ . " CustomB4ViewClassFile File  [ $CustomB4ViewClassFile ] " , ARMSmartViewModule::DEBUG_VAR ) ;

				/* @var $customB4View ARMB4ViewInterface */
				$customB4View = new $className() ;

				$customB4View->setBaseContentViewVO( $BaseContentViewVO ) ;

				$customB4View->setCssFiles($css_files) ;

				$customB4View->setJsFiles( $js_files ) ;

				$b4ViewHtml = $customB4View->getHtml();
			}

		}


		if(  $b4ViewHtml == NULL ){

			$ArmSmartHtmlHeader = new ARMSmartHtmlHeader();

			$ArmSmartHtmlHeader->addCssFiles( $css_files) ;

			$ArmSmartHtmlHeader->addJsFiles( $js_files ) ;

			$b4ViewHtml = $ArmSmartHtmlHeader->show( TRUE ) ;
		}
		ARMDataHandler::writeFile( $headerFilePath , "", $b4ViewHtml , "w+") ;

		ARMDebug::ifLi( __CLASS__ . " View Header File  [ {$headerFilePath} ] " , ARMSmartViewModule::DEBUG_VAR ) ;


		include $headerFilePath ;

		return $headerFilePath ;
	}


	protected function getClassName($filePath ){
		$classNameSufix  = "B4View" ;
		$className =  ucfirst( basename( $filePath , ".b4.php") ) ;
		return $className . $classNameSufix;
	}

	/**
	 * Searches for an valid asset list to add on view request
	 * @param string $base_dir
	 * @param string $folder_name
	 * @param stringn $file_extension
	 * @param array $request_dir_list
	 * @return multitype:
	 */
	protected function findAssets( $base_dir , $file_extension , $request_dir_list ){
		$result  = array();

		$asset_base_dir =  ARMDataHandler::removeDoubleBars( $base_dir  ) ;


		$auto_load_dir = $asset_base_dir  . "/" . $this->_config->asset_auto_load_folder;

//		ARMDebug::error($asset_base_dir);
//		ARMDebug::error($auto_load_dir);
//		die;

		$globResult = $this->rglob( $auto_load_dir  . "/*.". $file_extension );

//		ARMDebug::dump( $file_extension , $request_dir_list  );
//		ARMDebug::dump($globResult);

		if(  is_array( $globResult ) )
			$result = array_merge( $result, $globResult );
//				$result = array_merge( $result, $this->rglob( $auto_load_dir  . "/*.". $file_extension ));

		if( count($request_dir_list) == 0 ){

			$globResult = $this->rglob( $asset_base_dir . "/*.". $file_extension ) ;
			if(  is_array( $globResult ) )
				$result = array_merge( $result, $globResult );

		}

		if( count($request_dir_list) > 0){

			$base_dir = ARMDataHandler::removeDoubleBars( $base_dir . "/" . array_shift( $request_dir_list ) );
			$result = array_merge( $result, $this->findAssets( $base_dir  , $file_extension , $request_dir_list ) );
		}

		return $result ;
	}

	/**
	 * Search for a patern recursively
	 * @param string $pattern
	 * @param number $flags
	 * @return multitype:
	 */
	protected function rglob($pattern, $flags = 0) {
		$files = glob( $pattern, $flags);
		$files = !is_array( $files ) ? array() : $files ;
		$globResult = glob(dirname($pattern).'/*', GLOB_ONLYDIR ) ;
		if(  is_array( $globResult ) ){

			foreach (  $globResult as $dir) {
//				ARMDebug::error( $dir .'/'.  basename($pattern));
				$globResult = $this->rglob($dir.'/'.basename($pattern), $flags );
//				ARMDebug::dump( $globResult );
				if(  is_array( $globResult ) ){
					$files = array_merge( $files, $globResult );
				}
			}
		}
		return $files;
	}

}