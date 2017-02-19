<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alanlucian
 * Date: 11/20/13
 * Time: 11:07 AM
 */

abstract class ARMHtmlBaseElementAbstract implements ARMHtmlElementInterface {
	############################################################

	/**
	 *
	 * @var string
	 */
	protected $id;


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::setId()
	 */
	public function setId( $value ) {
		ARMValidation::isString( $value , TRUE ) ;
		$this->id = $value;

	}



	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::getId()
	 */
	public function getId() {
		if( !isset( $this->id ) )
			return $this->getName();
		return $this->id;
	}

############################################################

	protected $cssClass = array();

	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::addCSSClass()
	 */
	public function addCSSClass( $value ) {

		if( is_array( $value ) ) {
			$this->cssClass  = array_merge( $this->cssClass ,  $value ) ;
			return $this;
		}

		if( ARMValidation::isString( $value, TRUE) && !in_array( $value, $this->cssClass ) )
			$this->cssClass[] = $value ;

		return $this;
	}


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::hasCSSClass()
	 */
	public function hasCSSClass($value) {
		if( in_array( $value, $this->cssClass ) )
			return TRUE;

		return FALSE;

	}


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::removeCSSClass()
	 */
	public function removeCSSClass($value) {
		$index = array_search( $value , $this->cssClass ) ;

		if(  $index !== FALSE )
			unset( $this->cssClass[ $index ] );

	}


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::replaceCSSClass()
	 */
	public function replaceCSSClass( $old_class, $new_class ) {
		$index = array_search( $old_class , $this->cssClass ) ;

		if(  $index !== FALSE )
			$this->cssClass[ $index ] =  $new_class ;

	}

	/**
	 * @return string
	 */
	public function getCssClassString(){
		return implode( " " , $this->cssClass ) ;
	}

############################################################

	/**
	 * An List of all custom HTML5 attributes
	 * @var array
	 */
	protected $HTML5CustomAttributes ;

	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::getAttributes()
	 */
	public function getAttributes() {
		return $this->HTML5CustomAttributes ;

	}


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::getAttribute()
	 */
	public function getAttribute($name) {
		if( isset($this->HTML5CustomAttributes) && isset( $this->HTML5CustomAttributes[$name] ) )
			return $this->HTML5CustomAttributes[$name] ;

		return NULL ;
	}


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::removeAttribute()
	 */
	public function removeAttribute($name) {
		if( isset($this->HTML5CustomAttributes) && isset( $this->HTML5CustomAttributes[$name] ) )
			unset( $this->HTML5CustomAttributes[$name] );


	}


	/* (non-PHPdoc)
	 * @see ARMHtmlFormElementInterface::addAttribute()
	 */
	public function addAttribute($name, $value) {
		if( !isset($this->HTML5CustomAttributes) )
			$this->HTML5CustomAttributes = array();

		$this->HTML5CustomAttributes[$name] = $value ;
	}


############  HTML BUILDER FUNCTIONS
	protected function addBaseDOMData( DOMElement &$el ){
		$el->setAttribute( "name" , $this->getName() );

		$el->setAttribute( "id" , $this->getId() );

		$el->setAttribute( "class" , implode( " ", $this->cssClass ) );

		if( isset( $this->HTML5CustomAttributes)  && count( $this->HTML5CustomAttributes ) > 0 ){
			foreach( $this->HTML5CustomAttributes  as $name => $value)
				$el->setAttribute( "data-" . $name ,  $value );
		}

	}
}