<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date 11/12/2013 07:12:18
 * @from_table arm_log 
 */ 
class ArmLogVO extends ARMAutoParseAbstract{
	
	 
	
	/**
	 * @type : int(10) unsigned			
	 */
	public $id;
	
	/**
	 * @type : datetime			
	 */
	public $date_in;
	
	/**
	 * @type : int(10) unsigned			
	 */
	public $user_id;
	
	/**
	 * @type : int(11)			
	 */
	public $ref_id;
	
	/**
	 * @type : varchar(255)			
	 */
	public $ref_alias;
	
	/**
	 * @type : varchar(255)			
	 */
	public $action;

	/**
	 * @type : varchar(255)
	 */
	public $action_label;

	/**
	 * @type : varchar(255)
	 */
	public $data_resolver_class;

	/**
	 * @type : text			
	 */
	public $data;
}
	