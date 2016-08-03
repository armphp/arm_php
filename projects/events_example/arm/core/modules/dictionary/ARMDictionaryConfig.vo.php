<?php
/**
 * 
 * User: renatomiawaki
 * Date: 11/25/13
 *
 * As propriedades do vo (totalmente livre) são as chaves do dictionary
 *
 */

class ARMDictionaryConfigVO extends ARMAutoParseAbstract{

	/**
	 * @override
	 */
	public function parseObject( $object ) {

		if( is_object( $object ) ){
			$vars = get_object_vars( $object ) ;

			foreach( $vars as $key => $value ){

				if( $key && $value !== NULL ){
					$this->$key = $value ;
				}
			}
		}
	}
}