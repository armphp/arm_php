<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date 14/05/2013 05:05:55
 * @from_table callback 
 */ 
class ARMCallbackVO extends ARMAutoParseAbstract{
	/**
	 * @type : int(10) unsigned			
	 */
	public $id;
	/**
	 * aqui active 1 é para quando o cron já pode executar a trigger
	 * na verdade significa que a trigger já aconteceu na qual ela devesse ser executada
	 * 
	 * @type : int(1)			
	 */
	public $active;
	/**
	 * @type : int(1)			
	 */
	public $task_status;
	/**
	 * Quantidade de vezes que a trigger será executada até ser considerada DONE
	 * se for ZERO significa infinito
	 * @type : int(3)
	 */
	public $execution_limit ;
	/**
	 * Grava a quantidade de vezes em que essa trigger já aconteceu e foi executado o callback
	 * 
	 * @type : int(3)
	 */
	public $execution_count ;
	/**
	 * @type : varchar(200)			
	 */
	public $trigger_action;
	/**
	 * @type : varchar(255)			
	 */
	public $slug;
	
	/**
	 * @type : varchar(10)			
	 */
	public $callback_type;

	/**
	 * @type : varchar(1000)
	 */
	public $callback_url;
	/**
	 * @type : varchar(255)
	 */
	public $callback_module;
	/**
	 * @type : varchar(255)
	 */
	public $callback_method;
	/**
	 * se 1, sim, o cron irá executar esse callback
	 * @type : int(1)			
	 */
	public $is_cron_task;
	/**
	 *
	 * @type : int(3)
	 */
	public $executions_remain ;
	/**
	 * @type : datetime
	 */
	public $start_date ;
	/**
	 * @type : text			
	 */
	public $data_info;
	/**
	 * @type : int(1)
	 */
	public $is_conditional_auto_done;

}
	