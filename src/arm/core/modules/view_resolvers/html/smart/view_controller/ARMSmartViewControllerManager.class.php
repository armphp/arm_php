<?php

/**
 *
 * @author alanlucian
 *
 */

class ARMSmartViewControllerManager {

	public static function getData( ARMSmartViewConfigVO $configVO , $arrayPathFolder  , $data_controler_result , $common_data ){

		foreach( $arrayPathFolder as &$item){
			$item = ARMDataHandler::classNameToUrlFolderName( $item );
		}

		ARMDebug::ifLi( __CLASS__ . "  ViewControllerFile sendo buscado em  [ " . $configVO->getViewControllerFolder() . " ] " , ARMSmartViewModule::DEBUG_VAR ) ;
		ARMDebug::ifPrint( $arrayPathFolder  ) ;

		$ViewControllerFile = ARMFileFinder::searchByFolder( $configVO->getViewControllerFolder() , $arrayPathFolder, NULL , ".php" , TRUE) ;


//		ARMDebug::ifPrint (__CLASS__ . "  ViewControllerFile  que deveria existir em: ". $ViewControllerFile, ARMSmartViewModule::DEBUG_VAR );




		if( !$ViewControllerFile  ){
			if( ARMClassIncludeManager::loadByFile( ARMDataHandler::removeDoubleBars( $configVO->getViewControllerFolder()  . "/" . $configVO->defaultViewController . ".class.php" ) ) ){
				$ViewControllerInstance  = new $configVO->defaultViewController() ;
				return $ViewControllerInstance->init( $data_controler_result , $common_data);
			}
			return NULL;
		}



		$class_file_dir  = dirname( $ViewControllerFile ) ;

		$ViewControllerFile =  $class_file_dir  . "/" . ucfirst( basename( $ViewControllerFile ) ) ;




		ARMClassIncludeManager::loadByFile( $ViewControllerFile  ) ;

		//remove view folder to find a method to execute
		$slimFileFoundPath  = trim( str_replace(
				ARMDataHandler::removeLastBar( $configVO->getViewControllerFolder() ) , "", $class_file_dir
		) ) ;

		// remove all from the request before the class path reference
		$fileInfo = explode( "/" , $slimFileFoundPath );
		$fileInfo =  array_filter( $fileInfo, 'strlen' );

		$data_to_use = array_diff( $arrayPathFolder , $fileInfo) ;

		$className = self::getClassName( $data_to_use ) ;

		$methodName = self::getMethodName( $className, $data_to_use ) ;


		if(  !ARMClassHandler::classImplements( $className , "ARMSmartViewControllerInterface" ) ) {
			throw new ErrorException(  "{$className} must implements ARMSmartViewControllerInterface" );
		}

		ARMDebug::ifLi( __CLASS__ . " ViewControllerFile encontrado [ $ViewControllerFile ] " , ARMSmartViewModule::DEBUG_VAR ) ;

		ARMDebug::ifLi( __CLASS__ . " ViewControllerManager $className->$methodName " , ARMSmartViewModule::DEBUG_VAR ) ;

		$ViewControllerInstance  = new $className() ;

		return $ViewControllerInstance->{$methodName}( $data_controler_result , $common_data);

	}

	private static function getMethodName( $className, &$data_info ) {
		$method = ARMDataHandler::urlFolderNameToClassName( array_shift( $data_info ) ) ;




		if( ARMClassHandler::hasMethod(  $className , $method ) ){
			return $method ;
		}


		return "init" ;
	}

	private static function getClassName( &$data_info ){
		$classNameSufix = "ViewController" ;

		$currentClassName  = ARMDataHandler::urlFolderNameToClassName( array_shift( $data_info ) ) . $classNameSufix ;



		if( class_exists($currentClassName) )
			return $currentClassName ;

	}
}