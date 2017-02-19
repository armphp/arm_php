<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date 07/01/2014 06:01:52
 * @from_table file 
 */ 
class ARMFileVO extends ARMAutoParseAbstract{
	
	 
	
	/**
	 * @type : int(10) unsigned			
	 */
	public $id;
	
	/**
	 * @type : tinyint(4)			
	 */
	public $active;
	
	/**
	 * @type : int(11)			
	 */
	public $order;
	
	/**
	 * @type : varchar(100)			
	 */
	public $type;
	
	/**
	 * @type : varchar(200)			
	 */
	public $ref_alias;
	
	/**
	 * @type : int(11)			
	 */
	public $ref_id;
	
	/**
	 * @type : varchar(255)			
	 */
	public $url;
	
	/**
	 * @type : varchar(255)			
	 */
	public $name;
	
	/**
	 * @type : text			
	 */
	public $description;
}
	