<?php
/**
 * 
 * Interface basica de uma classe que resolve e busca configs
 * 
 * @author renatomiawaki
 *
 */
interface ARMConfigResolverInterface {
	/**
	 * 
	 * @param string $className
	 * @param string $alias
	 * 
	 * @return object 
	 */
	static function getConfig( $className ,  $alias = "" ) ;
	
	static function saveConfig( $className ,  $alias , $data );
}