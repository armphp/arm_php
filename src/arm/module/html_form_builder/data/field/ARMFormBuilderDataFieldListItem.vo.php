<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 5:55 PM
 *
 * List of label and field
 *  checkbox and radio usage
 *
 * Ex: a list of animals would be like
 * [
 * 	{ ARMFormBuilderDataFieldListItemVO
 *  	$label=> "Dog"
 * 		$field=> "<input type='checkbox' name='animals[]' value='dog'/>"
 *  },
 *  { ARMFormBuilderDataFieldListItemVO
 *  	$label=> "Cat"
 * 		$field=> "<input type='checkbox' name='animals[]' value='Cat'/>"
 *  }
 * ]
 */

class ARMFormBuilderDataFieldListItemVO {

	public $label;

	public $field;

}