<?php

/** 
 * @author alanlucian
 * 
 */
abstract class ARMBaseCacheVO implements ARMCacheVOInterface {

	/**
	 * @var array
	 */
	public $params;


	public $duration;
	
	/**
	 * 
	 * @var string
	 */
	public $cacheFolder;
	
	public function parseFileContent( $fileContent ){
		return $fileContent ;
	}
	
	public function parseDataToFileContent( $data ){
		return $data ;
	}
}

?>