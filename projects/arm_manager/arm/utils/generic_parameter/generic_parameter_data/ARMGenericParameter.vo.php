<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date 18/03/2014 01:03:21
 * @from_table generic_parameter 
 */ 
class ARMGenericParameterVO extends ARMAutoParseAbstract{
	
	 
	
	/**
	 * @type : bigint(20) unsigned			
	 */
	public $id;
	
	/**
	 * @type : int(11)			
	 */
	public $active;
	
	/**
	 * @type : int(10) unsigned			
	 */
	public $ref_id;
	
	/**
	 * @type : varchar(255)			
	 */
	public $ref_name;

//	/**
//	 * @type : varchar(255)
//	 */
//	public $data_name;


	/**
	 * @type : longtext			
	 */
	public $data;
}
	