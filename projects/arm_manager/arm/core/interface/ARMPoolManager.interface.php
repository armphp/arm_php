<?php
/**
 * Toda PullManager precisa implementar isso
 * São classes que guardam configs
 * 
 * Todo config precisa implementar o ManagerItemInterface
 * 
 * @author renatomiawaki
 * 
 *
 */
interface ARMPoolManagerInterface {
	/**
	 * 
	 * @param object $item
	 */
	static function add( $item , $alias = "" );
	/**
	 * 
	 * @param unknown $alias
	 * @return ModuleInterface
	 */
	static function getByAlias( $alias );
	
	static function getByIndex( $index );
	
	static function setDefault( &$item );
	
	static function getDefault();
}