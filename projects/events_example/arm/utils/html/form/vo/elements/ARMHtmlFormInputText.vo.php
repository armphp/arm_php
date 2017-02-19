<?php

/**
 * This class represents an simple InputText 
 * @author alanlucian
 *
 */
class ARMHtmlFormInputTextVO  extends ARMHtmlFormBaseElemtentVO {
	

	/**
	 *
	 * @var string
	 */
	protected $value ;
	
	
	public function __construct( $name , $value = NULL ) {
		
		ARMValidation::isString( $name , TRUE );
		
		$this->name = $name ;
		
		$this->label_name = $name ;
		
		if( $value !== NULL ) {
			$this->setValue( $value ) ;
		}
	}

	/**
	 * @return the string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @param  $value
	 */
	public function setValue( $value) {
		ARMValidation::isString( $value , TRUE );
		
		$this->value = $value;
		return $this;
	}

	/**
	*
	* @param DOMDocument $DOMDocument
	* @return DOMElement
	*/
	public function build( DOMDocument $DOMDocument ){
		$el = $DOMDocument->createElement( "input" );
		//adiciona os atributos no elemento
		parent::addBaseDOMData( $el ) ;
		//retorna o DOMElement para que coloque o appendChild
		return  $el ;
	}	
}