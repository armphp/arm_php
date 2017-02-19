<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:55 PM
 */

class ARMViewRenderFieldDataConfigVO {

	public $fieldBuildModule = "ARMFormBuiderDataField" ;

	/**
	 * Configure aqui qual será seu RENDER de FORMULARIO
	 * @var string de uma classe que interfaceia ARMFormBuiderDataFieldSetInterface
	 */
	public $formFieldSetBuildModule  ;

	/**
	 * Configure aqui qual será seu RENDER de FORMULARIO
	 * @var string de uma classe que interfaceia ARMFormBuiderDataFieldSetInterface
	 */
	public $stringFieldSetBuildModule  ;

	public $fieldNamePrefix = "generic_fields";
}