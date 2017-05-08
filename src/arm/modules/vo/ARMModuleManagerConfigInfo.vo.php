<?php


/**
 * 
 * Classe VO de configuração do módulo de configuração de módulos
 * 
 * @author renatomiawaki
 *
 */
class ARMModuleManagerConfigInfoVO extends ARMConfigModuleInfoVO implements ARMFetchedClassInterface {

	public $className ;

	public function fetchObject( object $object ){
		$this->className 	= ( isset( $object->className ) ) ? $object->className : NULL ;
		$this->enable 		= ( isset( $object->enable ) ) ? $object->enable : TRUE ;
		
	}
}