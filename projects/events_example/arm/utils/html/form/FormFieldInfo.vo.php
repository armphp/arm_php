<?php

/**
 * Classe que guarda os dados necessários p/ gerar um formulário
 * @author alanlucian
 *
 */
class FormFieldInfoVO {
	
	public $name;
	
	public $value;
	
	public function __construct( $field_name , $value , $has_many  = FALSE ){
		$this->name = $field_name . ( $has_many  ? "[]" : "" ) ;
		$this->value = $value ;
	}
	
	
	/**
	 * Pega um valor do array de acorod com o indice, se o indice nao tiver valor nao retorna nada
	 * @param int $array_index
	 */
	public function getArrayValue( $index = NULL ){
		if( !is_array( $this->value) )
			return  $this->value;
		
		
		if(  is_numeric( $index  ) ){
			
			if( isset( $this->value[$index]) ){
				return $this->value[$index] ;
			}else{
				return NULL ;
			}
			
		}
		
		return $this->value;
	}
	
	function __toString(){
		return $this->name ;
	}
}