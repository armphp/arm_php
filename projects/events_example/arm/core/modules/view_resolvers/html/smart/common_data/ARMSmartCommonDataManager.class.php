<?php

/**
 *
 * @author alanlucian
 *
 */

class ARMSmartCommonDataManager {

	public static function getData( ARMSmartViewConfigVO $configVO , $arrayPathFolder  , $data_controler_result){

		$fileName  =  ARMFileFinder::seach( $configVO->getSmartCommmonDataFolder() , $arrayPathFolder, "SmartCommonData.class.php") ;

		if( !$fileName )
			return NULL ;

		ARMClassIncludeManager::loadByFile( $fileName );

		if(  !ARMClassHandler::classImplements( "SmartCommonData" , "ARMSmartCommonDataInterface" ) ) {
			throw new ErrorException(  "SmartCommonData ( {$fileName} ) must implements ARMSmartCommonDataInterface" );
		}
		//Used call_user_funct to prevent Automatic Load from classIncludeManager
		return  call_user_func(  array( "SmartCommonData" , "getData") ,  $data_controler_result ) ;
	}
}