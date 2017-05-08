<?php

/**
 *
 * Classe que resolve configs salvando arquios físicos em json
 *
 * @author renatomiawaki
 *
 *
 * @version 1.2 - Compatibilidade com versões anteriores
 */


include_once 'arm/core/modules/interface/ARMConfigResolver.interface.php';

class ARMModuleJsonConfigLoader implements ARMConfigResolverInterface
{
	/**
	 *
	 * @param string $className
	 * @param string $alias
	 * @return object
	 */
	public static function getConfig($className, $alias = "")
	{
		//TODO aqui que tem que fazer o role de config global @@@@LEAL

		$alias = ($alias == "") ? "default" : $alias;

		$configFile = self::getFolderWithFile($className, $alias);

//		dd($configFile);
		return self::getConfigData($configFile);
	}


	/**
	 *
	 * @param string $className
	 * @param string $alias
	 * @return object
	 */
	public static function getConfigByPath($className, $arrayPathFolder)
	{


		$dir = self::getFolder($className);

		$configFile = ARMFileFinder::searchByFolder($dir, $arrayPathFolder, "default.json", "json");

		return self::getConfigData($configFile);
	}

	protected static function getConfigData($configFile)
	{
		ARMDebug::ifLi("ARMModuleJsonConfigLoader config file_exists ( {$configFile} )?  " . (file_exists($configFile) ? "YES" : "NO"), "module");

		if (file_exists($configFile)) {
			$data = file_get_contents($configFile);
			$dataObject = json_decode($data);
			if (is_null($dataObject)) {
				ARMDebug::error("JSON sintax ERROR on file :: " . $configFile);
				ARMDebug::li("File content: ");
				ARMDebug::print_r($data);

				die;
			}

			return $dataObject;
		}
		if( ARMDataHandler::getValueByStdObjectIndex( ARMConfig::getLastInstance()->getCurrentConfigVO(), "modules_config_throw_errors" ) ){
			throw new Exception("Config ".$configFile." não encontrada.") ;
		}

		return NULL;
	}

	/**
	 * retorna a string suposta do caminho do arquivo
	 * @param unknown $className
	 * @param unknown $alias
	 * @return string
	 */
	protected static function getFolderWithFile($className, $alias)
	{
		foreach (self::getFolder($className) as $path){
			$filePaths[] = ARMDataHandler::removeDoubleBars($path . $alias . ".json");
		}

		$paths = array_filter($filePaths, function ($path) {
			return file_exists($path);
		});

		$path = array_shift($paths);

		if ($path) {
			return $path;
		}

		ARMDebug::ifPrint(['Could not find any of the expected module paths.', $filePaths]);
	}

	protected static function getFolder($className)
	{
		$folders = ARMConfig::getDefaultInstance()->getFolderModulesConfig() ;
		if(!is_array($folders)){
			//para funcionar com versões anteriores
			$folders = [$folders] ;
		}
		foreach ($folders as $path){
			$paths[] = $path.$className."/";
		}
		return $paths;
	}


	/**
	 * Salva o config
	 * @param unknown $className
	 * @param unknown $alias
	 * @param object $data
	 */
	public static function saveConfig($className, $alias, $data)
	{
		$folder = self::getFolder($className);
		ARMDataHandler::createRecursiveFoldersIfNotExists($folder);

		$file = self::getFolderWithFile($className, $alias);
		ARMDataHandler::writeFile($file, "", json_encode($data), "w+");
	}
}