<?php
/**
 * User: alanlucian
 * Date: 11/25/13
 * Time: 2:24 PM
 */

class ARMHtmlSimpleFormVO {

	/**
	 * form name
	 * @var string
	 */
	public $name;

	/**
	 * form ID
	 * @var string
	 */
	public $id;

	/**
	 * @var ARMHtmlSimpleFormActionVO
	 */
	public $action ;

	/**
	 * GET or POST
	 * @var string
	 */
	public $method ;


	/**
	 * @var ARMHtmlSimpleFormContentVO
	 */
	public $content;
}