<?php

class ARMDataMaskUSA extends ARMDataMask {
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getDate()
	 */
	public function getDate(){
		return "m/d/Y" ;
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
		return "m/d/Y H:i:s" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencyNumberFormat()
	 */
	public function getCurrencyNumberFormat(){
		return new ARMDataMaskNumberFormat( $decimals = 2, $dec_point = "." , $thousands_sep = "," );	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencyValidation()
	 */
	public function getCurrencyValidation(){
		return "/^(?:[1-9](?:[\d]{0,2}(?:,[\d]{3})*|[\d]+)|0)(?:\.[\d]{0,2})?$/" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencySymbolTemplate()
	 */
	public function getCurrencySymbolTemplate(){
		return "$ %s" ;
	}
}