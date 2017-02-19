<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date 17/01/2014 05:01:07
 * @from_table arm_parameter 
 */ 
class ArmParameterVO extends ARMAutoParseAbstract{
	
	 
	
	/**
	 * @type : int(10) unsigned			
	 */
	public $id;
	
	/**
	 * @type : int(11)			
	 */
	public $active;
	
	/**
	 * @type : int(11)			
	 */
	public $order;
	
	/**
	 * @type : varchar(400)			
	 */
	public $ref_alias;
	
	/**
	 * @type : int(11)			
	 */
	public $ref_id;
	
	/**
	 * @type : text			
	 */
	public $value;
}
	