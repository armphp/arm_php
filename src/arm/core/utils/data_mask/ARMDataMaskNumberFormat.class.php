<?php

/**
 * This class represents the necessary data for the number_format class
 * @see IARMDataMask, ARMDataHandler
 * @author alanlucian
 *
 */

class ARMDataMaskNumberFormat {
	
	public $decimals = NULL ;
	public $dec_point = NULL ;
	public $thousands_sep = NULL ;
	
	function __construct(  $decimals ,  $dec_point ,  $thousands_sep  ){
		$this->decimals = $decimals ;
		$this->dec_point = $dec_point ;
		$this->thousands_sep = $thousands_sep ;
		
	}
	
}