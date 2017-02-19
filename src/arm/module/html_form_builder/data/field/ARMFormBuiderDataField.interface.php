<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:59 PM
 */

interface ARMFormBuiderDataFieldInterface {


	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 */
	function hidden( $fieldVO );

	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 */
	function text( $fieldVO );

	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 */
	function password( $fieldVO  );


	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 */
	function long_text( $fieldVO  );


	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 * @return array
	 */
	function radio( $fieldVO  );


	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 * @return array
	 */
	function checkbox( $fieldVO  );


	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 */
	function select( $fieldVO  );

}