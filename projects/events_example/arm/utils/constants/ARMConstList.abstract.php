<?php

/**
 * 
 * Para uso em classes de constantes ou que contenham constantes, para facilitar a busca de constantes
 * 
 * @author Alan Lucian
 *
 */
abstract class ARMConstListAbstract {
	/**
	 * 
	 * Verific se a classe tem a constante enviada em string
	 * 
	 * @param string $constant_name 
	 * @return boolean
	 */
	public static function hasConstant( $constant_name ){
		
		return ARMClassHandler::hasConstant( get_called_class() , $constant_name ) ;
	
	}
	
}