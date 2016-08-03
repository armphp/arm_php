<?php
/**
 * 
 * User: renatomiawaki
 * Date: 12/11/13
 * 
 */

class ARMLogInfoVO extends ARMAutoParseAbstract{

	/**
	 * @type : int(10) unsigned
	 */
	public $user_id;

	/**
	 * @type : int(11)
	 */
	public $ref_id;

	/**
	 * @type : date
	 */
	public $date_in;

	/**
	 * @type : varchar(255)
	 */
	public $ref_alias;

	/**
	 * @type : varchar(255)
	 */
	public $action_label;

	/**
	 * @type : varchar(255)
	 */
	public $action;
	/**
	 * @type : varchar(255)
	 */
	public $data_resolver_class;
	/**
	 * @type : text
	 */
	public $data;
}