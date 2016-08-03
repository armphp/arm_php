<?php

/** 
 * @author alanlucian
 * 
 */
interface ARMCacheVOInterface {
	/**
	 * @return string
	 */
	function getFileName();
	
	/**
	 * @return string
	 */
	function getFileDirectory();
	
	/**
	 * @return unknow
	 */
	function parseFileContent( $fileContent );
}

?>