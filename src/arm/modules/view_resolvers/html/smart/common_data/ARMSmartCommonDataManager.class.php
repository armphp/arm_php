<?php

/**
 *
 * @author alanlucian

 * @legendadopor: Renato Miawaki
 * Essa classe procura arquivos de common data, que provavelmente apenas 1 serviria para o projeto inteiro,
 * porém caso queira ter um especial pela url, basta criar uma pasta com o nome da url, e colocar lá dentro uma
 * classe chamada SmartCommonData e o arquivo chamado SmartCommonData.class.php (exatamente assim)
 * E essa classe precisa implementar a interface que o ARMSmartCommonDataManager exige
 * ARMSmartCommonDataInterface
 */

class ARMSmartCommonDataManager {

	public static function getData( ARMSmartViewConfigVO $configVO , $arrayPathFolder  , $data_controler_result){
		//procurando o arquivo SmartCommonData.class.php dentro das pastas, precisa ser esse nome na classe
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