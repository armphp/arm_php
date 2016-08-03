<?php
/**
 * User: alanlucian
 * Date: 3/14/14
 * Time: 11:04 AM
 */

class ARMFormBuilderDataFieldHTMLVO {

	/**
	 * @var ARMFormBuilderDataFieldVO
	 */
	public $fieldVO;

	/**
	 *
	 * input em HTML se for checkbox ou radio vem array de "label" => "value"
	 * @var string|array
	 */
	public $html_field;


	/**
	 * hidden com os dados do field em json
	 * @var string
	 */
	public $html_field_data;

	/**
	 * @var ARMFormBuilderDataFieldHTMLVO
	 */
	public $aditional_fields;

}