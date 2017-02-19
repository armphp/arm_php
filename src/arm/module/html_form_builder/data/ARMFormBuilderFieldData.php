<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:33 PM
 *
 * Data container for a form building
 *
 */


class ARMFormBuilderFieldData {


	/**
	 * @var ARMFormBuilderDataFieldVO[]
	 */
	public $fields;


	public function addField( $field ){
		if( !is_array( $this->fields ) ){
			$this->fields = array() ;
		}
		$this->fields[] = $field ;
	}

}