<?php
/**
 * User: alanlucian
 * Date: 3/14/14
 * Time: 2:42 PM
 */

interface ARMStringBuiderDataFieldSetInterface {

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function hidden( $fieldDataVO );

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function text( $fieldDataVO );

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function password( $fieldDataVO  );


	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function long_text( $fieldDataVO  );


	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function radio( $fieldDataVO  );


	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function checkbox( $fieldDataVO  );


	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function select( $fieldDataVO  );
}
