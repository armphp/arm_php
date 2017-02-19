<?php
class ARMDataCharHandler extends ARMDataNumberHandler  {


	/**
	 * @param string $char
	 * @return Boolean
	 */
	static function isUpper($char){
		return ctype_upper($char);
	}
	/**
	 * @param string $char
	 * @return Boolean
	 */
	static function isLower($char){
		return !ARMDataHandler::isUpper($char);
	}
	
	public static function removeFirstChar( $string , $firstChar = NULL ){
	
		if( $firstChar == NULL ) $firstChar = ".";
	
		return preg_replace("|^({$firstChar}){1}|", "", $string ) ;
	
	}
}