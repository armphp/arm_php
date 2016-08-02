<?php

/**
 *
 * @author alanlucian
 *
 */

interface ARMB4ViewInterface {

	function setBaseContentViewVO( $data ) ;

	function setJsFiles( $js_files );

	function setCssFiles( $css_files );

	function getHtml();

}