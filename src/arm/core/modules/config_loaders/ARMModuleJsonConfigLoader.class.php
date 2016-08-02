<?php

/**
 * 
 * Classe que resolve configs salvando arquios físicos em json
 * 
 * @author renatomiawaki
 *
 */


include_once 'arm/core/modules/interface/ARMConfigResolver.interface.php';
class ARMModuleJsonConfigLoader implements ARMConfigResolverInterface {
	/**
	 *
	 * @param string $className
	 * @param string $alias
	 * @return object
	 */
	public static function getConfig( $className ,  $alias = "" ){
		$alias = ( $alias == "" )?"default":$alias ;
		
		$configFile = self::getFolderWithFile( $className, $alias ) ;
		
		return self::getConfigData( $configFile ) ;
	}
	
	
	/**
	 *
	 * @param string $className
	 * @param string $alias
	 * @return object
	 */
	public static function getConfigByPath( $className ,  $arrayPathFolder ){
	
		$dir = self::getFolder($className) ;
		$configFile = ARMFileFinder::searchByFolder( $dir , $arrayPathFolder , "default.json" , "json" ) ;
	
		return self::getConfigData( $configFile ) ;
	}
	
	protected static function getConfigData( $configFile ){
		ARMDebug::ifLi( "ARMModuleJsonConfigLoader config file_exists ( {$configFile} )?  " . ( file_exists( $configFile ) ? "YES" : "NO" )  ,  "module" );
		
		if( file_exists( $configFile ) ){
			$data = file_get_contents( $configFile ) ;
			$dataObject = json_decode( $data ) ;
			if( is_null($dataObject) ){
				ARMDebug::error( "JSON sintax ERROR on file :: " .  $configFile ) ;
				ARMDebug::li( "File content: " );
				ARMDebug::print_r( $data );
		
				die ;
			}
			return $dataObject ;
		}
		
		return NULL ;
	}
	
	/**
	 * retorna a string suposta do caminho do arquivo
	 * @param unknown $className
	 * @param unknown $alias
	 * @return string
	 */
	protected static function getFolderWithFile( $className, $alias ){
		
		$filePath = ARMDataHandler::removeDoubleBars( self::getFolder($className).$alias.".json" ) ;
		ARMDebug::ifLi( "ARMModuleJsonConfigLoader getFolderWithFile :: " . $filePath  ,  "module" );
		return $filePath ;
	}
	protected static function getFolder( $className ){
		return ARMDataHandler::removeDoubleBars( ARMConfig::getDefaultInstance()->getFolderModulesConfig()."/".$className."/" ) ;
	}
	/**
	 * Salva o config
	 * @param unknown $className
	 * @param unknown $alias
	 * @param object $data
	 */
	public static function saveConfig( $className ,  $alias , $data ){
		$folder = self::getFolder( $className ) ;
		ARMDataHandler::createRecursiveFoldersIfNotExists( $folder ) ;
		
		$file = self::getFolderWithFile($className, $alias) ;
		ARMDataHandler::writeFile( $file , "", json_encode( $data ) , "w+") ;
	}
}