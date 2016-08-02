<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alanlucian
 * Date: 11/20/13
 * Time: 4:13 PM
 */

interface ARMHtmlElementInterface {

	function setId( $value ) ;

	function getId() ;



#############   ARM HTML MAKER

	/**
	 * Coloca o elemento (esse elemento) dentro do document
	 * @param DOMDocument $DOMDocument
	 * @return mixed
	 */
	function build( DOMDocument $DOMDocument );

#############   CSS class functions

	function addCSSClass( $value ) ;

	function replaceCSSClass( $old_class, $new_class );

	function removeCSSClass( $value );

	function hasCSSClass(  $value );


#############  HTML5 custom attributes functions

	function addAttribute( $name , $value );

	function getAttribute( $name );

	function removeAttribute( $name );

	function getAttributes();
}