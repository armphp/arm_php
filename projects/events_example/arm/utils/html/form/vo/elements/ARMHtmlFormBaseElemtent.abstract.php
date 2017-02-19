<?php

abstract class ARMHtmlFormBaseElemtentVO extends ARMHtmlBaseElementAbstract implements ARMHtmlFormElementInterface {

	/**
	 * Name of the field on the HTML
	 * @var string
	 */
	protected $name;
	
	/**
	 * set the elemenet name
	 * @param string $value
	 */
	public function setName( $value ) {
		ARMValidation::isString( $value , TRUE ) ;
		$this->name = $value ;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}
	
############################################################	
	/**
	 * The Label of input on HTML label
	 * @var string
	 */
	protected $label_name ;
	
	/**
	 * set the elemanet label name
	 * @param string $value
	 */
	public function setLabel( $value ) {
		ARMValidation::isString( $value , TRUE ) ;
		$this->label_name = $value ;
	}
	
	/**
	 * Returns the label string
	 * @return string
	 */
	public function getLabel(){
		return $this->name;
	}

}
	 