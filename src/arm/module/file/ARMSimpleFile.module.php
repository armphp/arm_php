<?php
/**
 * 
 * User: renatomiawaki
 * Date: 1/7/14
 * 
 */

class ARMSimpleFileModule extends ARMBaseModuleAbstract {
	/**
	 * @param null $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMSimpleFileModule
	 */
	public static function getInstance($alias = NULL, $useDefaultIfNotFound = FALSE){
		return parent::getInstance($alias, $useDefaultIfNotFound) ;
	}

	/**
	 * @return ARMSimpleFileModule
	 */
	public static function getDefaultInstance() {
		return parent::getDefaultInstance();
	}

	/**
	 * @return ARMSimpleFileModule
	 */
	public static function getLastInstance() {
		return parent::getLastInstance();
	}
	/**
	 * @return ARMSimpleFileConfigVO
	 */
	protected function getConfig(){
		return $this->_config ;
	}

	public function addFile( $tempPath ){
		//
	}
}