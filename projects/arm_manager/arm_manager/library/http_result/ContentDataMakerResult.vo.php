<?php

class ContentDataMakerResultVO{
	
	/**
	 * @var ARMReturnResultVO
	 */
	public $result ;
	
	public $driver_module ;
	public $databases  = array();
	
	public $tables = array() ;
	
	/**
	 * 
	 * @var ARMModelGatewayConfigToMakeVO
	 */
	public $configToMake ; 
	
	public function __construct(){
		
		$this->result = new ARMReturnResultVO() ;
		
		
	}
} 