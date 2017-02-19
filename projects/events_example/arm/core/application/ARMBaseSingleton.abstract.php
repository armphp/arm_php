<?php
/**
 * 
 * Instancia uma classe baseado no class_name enviado
 * Serve para classes do tipo singleton
 * 
 * @author renatomiawaki
 *
 */
abstract class ARMBaseSingletonAbstract implements ARMSingletonInterface {
	
	protected static $instance;
	protected $__alias ;
	/**
	 *  Singleton -  Método padrão para toda classe Singleton.
	 *  @return ARMBaseSingletonAbstract
	 */
	public static function getInstance( $alias = "" ){
		$class_name = get_called_class();
		if(!isset(self::$instance[$class_name])){
			self::$instance[$class_name] = array();
		}
		if(!isset(self::$instance[$class_name][$alias])){
			self::$instance[$class_name][$alias] = new $class_name();
			self::$instance[$class_name][$alias]->__alias = $alias ;
		}
		return self::$instance[$class_name][$alias];
	}
	
}