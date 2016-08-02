<?php
/**
 * Classe para usar como base para pool managers
 * @author renatomiawaki
 *
 */
include_once 'arm/core/interface/ARMPoolManager.interface.php';
abstract class ARMBasePoolManager implements ARMPoolManagerInterface {
	private static $pull ;
	private static $default ;
	/**
	 *  Singleton -  Método padrão para toda classe Singleton.
	 *  @return *
	 */
	public static function getByAlias( $alias = "" ){
		$class_name = get_called_class() ;
		if( ! isset ( self::$pull[$class_name]["alias"][$alias] ) ){
			return NULL ;
		}
		return self::$pull[ $class_name ]["alias"][ $alias ];
	}
	public static function setDefault( &$item ){
		$class_name = get_called_class() ;
		if(!isset(self::$default[$class_name])){
			self::$default[$class_name] 			= $item;
		}
	}
	/**
	 * 
	 * @return Multiple NULL | Object
	 */
	public static function getDefault(){
		$class_name = get_called_class() ;
		if(!isset(self::$default[$class_name])){
			return NULL ;
		}
		return self::$default[$class_name] ;
	}
	/**
	 * 
	 * @param * $item
	 * @return number (int) simbolizando o index do item adicionado no pull
	 */
	public static function add( $item , $alias = ""){
		$class_name = get_called_class() ;
		if(!isset(self::$pull[$class_name])){
			self::$pull[$class_name] 			= array() ;
		}
		if( ! isset(self::$pull[$class_name]["indexed"] ) ){
			self::$pull[$class_name]["indexed"] = array() ;
		}
		if( ! isset(self::$pull[$class_name]["alias"] ) ){
			self::$pull[$class_name]["alias"] 	= array() ;
		}
		$index = count( self::$pull[$class_name]["indexed"] ) ;
		self::$pull[$class_name]["indexed"][ $index ] 	= $item ;
		self::$pull[$class_name]["alias"][ $alias ] 	= & self::$pull[$class_name]["indexed"][ $index ] ;
		if( $index == 0 ){
			//setando o primeiro item como default
			self::setDefault( $item );
		}
		return $index ;
	}
	/**
	 *
	 * @return *
	 */
	static function getByIndex( $index = NULL ){
		$class_name = get_called_class() ;
		if( ! isset ( self::$pull[$class_name]["indexed"][$index] ) ){
			return NULL ;
		}
		return self::$pull[ $class_name ]["indexed"][ $index ];
	}
}