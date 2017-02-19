<?php

/** 
 * @author alanlucian
 * 
 */
class ARMCacheHttpVO extends ARMBaseCacheVO {
	
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
		return $this->cacheFolder .  DIRECTORY_SEPARATOR   . "http" . DIRECTORY_SEPARATOR   ; 
	}
	
	/**
	 * 
	 * @var string
	 */
	public $urlRequest;

}

?>