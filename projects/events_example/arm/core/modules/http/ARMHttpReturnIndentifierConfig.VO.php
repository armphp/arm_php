<?php

/**
 * 
 * VO do HttpReturnIndentifierModule
 * @author renatomiawaki
 *
 */
class ARMHttpReturnIndentifierConfigVO{
	public $default_variable_name = "return";
	public function __construct( $data = NULL ){
		if( $data ){
			$this->default_variable_name = ARMDataHandler::getValueByStdObjectIndex( $data , "default_variable_name" ) ;
		}
	}
}