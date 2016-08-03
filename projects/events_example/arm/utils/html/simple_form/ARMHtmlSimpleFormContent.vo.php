<?php
/**
 * User: alanlucian
 * Date: 11/25/13
 * Time: 2:31 PM
 */

class ARMHtmlSimpleFormContentVO {

	/**
	 * html tag ID
	 * @var string
	 */
	public $id ;

	/**
	 * HTML tag of form element
	 * @var string
	 */
	public $tag ;

	/**
	 * form item type
	 * eg:  text for input
	 * @var string
	 */
	public $type ;

	/**
	 * the form item name
	 * @var string
	 */
	public $name ;

	/**
	 * @var string
	 */
	public $value ;

	/**
	 * form item label
	 * @var string
	 */
	public $label ;

	/**
	 * Optional
	 * @var mixed
	 */
	public $options ;

}