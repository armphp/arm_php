<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date 26/02/2016 04:02:34
 * @from_table cron_event 
 */ 
class ARMCronEventVO extends ARMAutoParseAbstract{
	
	 
	
	/**
	 * @type : int(10) unsigned			
	 */
	public $id;
	
	/**
	 * @type : int(11)			
	 */
	public $active;
	
	/**
	 * @type : datetime			
	 */
	public $date_in;
	
	/**
	 * @type : datetime			
	 */
	public $date_to_show;

	/**
	 * @type : varchar(255)
	 */
	public $token;

	/**
	 * @type : text			
	 */
	public $event_info;
	
	/**
	 * @type : varchar(255)			
	 */
	public $event_name;
}
	