<?php

class ARMDataMaskDefaultMySQL extends ARMDataMask {
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getDate()
	 */
	public function getDate(){
		return "Y-m-d" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getTime()
	 */
	public function getTime(){
		return "H:i:s";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getDateTime()
	 */
	public function getDateTime(){
		return "Y-m-d H:i:s" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencyNumberFormat()
	 */
	public function getCurrencyNumberFormat(){
		return NULL ;	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencyValidation()
	 */
	public function getCurrencyValidation(){
		return "/^([0-9]+)\.([0-9]+)$/";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencySymbolTemplate()
	 */
	public function getCurrencySymbolTemplate(){
		return "%s" ;
	}
}