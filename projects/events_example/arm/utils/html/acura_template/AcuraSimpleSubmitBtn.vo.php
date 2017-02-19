<?php
/**
 * User: alanlucian
 * Date: 11/20/13
 * Time: 10:59 AM
 *
 * expected output:

<a href="#LINK">
	<div title="HOVER LABEL" class="tipsy-s btn btn-o-icon btn-small btn-submit"><i>ICON LABEL</i></div>
</a>
 */

class AcuraSimpleSubmitBtnVO extends ARMHtmlBaseElementAbstract  {

	public $link  ;

	public $hover_message ;

	public $icon ;

	public $label ;

	/**
	 * @param $link
	 * @param null $label
	 * @param null $hover_message
	 * @param null $icon
	 */
	function __construct( $link , $label = NULL , $hover_message = NULL , $icon = NULL )	{

		$this->hover_message = $hover_message;

		$this->icon = $icon;

		$this->label = $label;

		$this->link = $link;

		$this->addCSSClass( explode( " " ,  "btn btn-o-icon btn-small btn-submit") ) ;

		return $this;
	}


	/**
	 * @return string
	 */
	public function build() {

		if( $this->hover_message )
			$this->addCSSClass( "tipsy-s" );


		$htmlElement = <<<EOF
<a id="$this->id" href="$this->link">
	<div title="$this->hover_message" class="$this->getCssClassString()"><i>$this->icon $this->label</i></div>
</a>
EOF;

		return $htmlElement ;

	}

}


