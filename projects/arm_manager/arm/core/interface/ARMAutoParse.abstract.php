<?php

/**
 * 
 * Classe para facilitar classes do tipo VO para auto parsear objetos e strings json baseado em valores identicos de propriedade
 * 
 * @author renato miawaki
 *
 */
abstract class ARMAutoParseAbstract {
	/**
	 * Faz o parse de um objeto
	 * @param object $object
	 */
	public function parseObject( $object  ){
		if( ! $object ){
			return ;
		}
		if( ! is_object( $object ) ){
			return ;
		}
		foreach( $object as $item => $value ){
			if( ARMClassHandler::hasPublicAttrribute( $this , $item ) ){
				$this->__setAttribute( $item , $value ) ;
			}
		}
	}
	/**
	 * metodo interno para encapsular o momento do parse em que será atribuido o valor da propriedade
	 * Caso precise alterar algum modo de parseamento específico, sobreescreva esse metodo e faça seu caso de uso
	 * @param string $attributeName
	 * @param unknown $value
	 */
	protected function __setAttribute( $attributeName, $value ){
		$this->{$attributeName} = $value ;
	}
	/**
	 * 
	 * Faz o parse automático a partir de um json
	 * 
	 * @param string $string_json
	 */
	public function parseJson( $string_json ){
		if( ! $string_json ){
			return ;
		}
		if( $string_json == "" ){
			return ;
		}		
		if( ! ARMValidation::isString( $string_json ) ){
			return ;
		}
		$decoded = json_decode( $string_json ) ;
		if( is_null( $decoded ) ){
			throw new ErrorException( get_called_class() ." _ parser error ! Verifique a syntax de seu json " ) ;
		}
		return $this->parseObject( $decoded ) ;
	}
}