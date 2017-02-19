<?php

/** 
 * @author alanlucian
 * 
 */
class ARMCacheDataVO extends ARMBaseCacheVO {
	
	/**
	 * (non-PHPdoc)
	 * @see ARMCacheVOInterface::getFileName()
	 */
	public function getFileName(){
		return md5( serialize( $this) ) . ".data" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMCacheVOInterface::getFilePath(getFileDirectory
	 */
	public function getFileDirectory(){

		return $this->cacheFolder . DIRECTORY_SEPARATOR  .  get_class( $this->class ) . DIRECTORY_SEPARATOR  . $this->method . DIRECTORY_SEPARATOR  ;
	}
	
	public function parseFileContent( $fileContent ){
		return unserialize( $fileContent );
	}
	
	public function parseDataToFileContent( $data ){
		return serialize( $data );
	}
	
	/**
	 * @var string or class instance
	 */
	public $class;

	/**
	 * 
	 * @var string
	 */
	public $method;
	
}

?>