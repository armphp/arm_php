<?php
/**
 * User: alanlucian
 * Date: 3/14/14
 * Time: 2:42 PM
 */

interface ARMFormBuiderDataFieldSetInterface {

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function hidden( $field_html_VO );

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function text( $field_html_VO );

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function password( $field_html_VO  );


	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function long_text( $field_html_VO  );


	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return array
	 */
	function radio( $field_html_VO  );


	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return array
	 */
	function checkbox( $field_html_VO  );


	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function select( $field_html_VO  );
}
