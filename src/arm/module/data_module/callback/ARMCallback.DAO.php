<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * Callback
	 * @date 14/05/2013 05:05:55 
	 */ 
	class ARMCallbackDAO extends  ARMBaseDAOAbstract {
		
		protected $TABLE_NAME = 'callback';
		/**
		* type : int(10) unsigned
		*/
		const FIELD_id = 'id';
		/**
		* type : int(1)
		*/
		const FIELD_active = 'active';
		/**
		* type : int(1)
		*/
		const FIELD_task_status = 'task_status';
		/**
		* type : varchar(200)
		*/
		const FIELD_trigger_action = 'trigger_action';
		/**
		* type : varchar(255)
		*/
		const FIELD_slug = 'slug';
		/**
		* type : varchar(10)
		*/
		const FIELD_callback_type = 'callback_type';
		/**
		* type : varchar(1000)
		*/
		const FIELD_callback_url = 'callback_url';
		/**
		* type : varchar(255)
		*/
		const FIELD_callback_module = 'callback_module';
		/**
		* type : varchar(255)
		*/
		const FIELD_callback_method = 'callback_method';
		/**
		* type : text
		*/
		const FIELD_data_info = 'data_info';
		
		
		/**
		 * aqui, zero é infinito
		 * type : int(3)
		 */
		const FIELD_execution_limit = 'execution_limit';
		
		/**
		 * type : int(3)
		 */
		const FIELD_execution_count = 'execution_count';

		/**
		 * se zero, não é pro cron executar e sim instantaneo
		 * type : int(1)
		 */
		const FIELD_is_cron_task = 'is_cron_task';
		
		/**
		 * se zero, não é pro cron executar e sim instantaneo
		 * type : int(1)
		 */
		const FIELD_is_conditional_auto_done = 'is_conditional_auto_done';
		
		/**
		 * quantas vezes ainda precisa executar essa trigger - só é útil para o cron na realidade
		 * type : int(3)
		 */
		const FIELD_executions_remain = 'executions_remain';
		/**
		 * a partir de quando, caso seja cron, ele já pode executar esse callback
		 * type : datetime
		 */
		const FIELD_start_date = 'start_date';
		/**
		* @return ARMCallbackDAO 
		*/
		public static function getInstance( $alias = NULL ){
			return parent::getInstance( $alias ) ;
		}
		
		public function setCallbackListStatus( $array_ids , $status ){
			$status = ARMDataHandler::forceInt( $status ) ;
			$ids  =  implode(",", $array_ids );
			$query = "UPDATE {$this->TABLE_NAME} SET " . self::FIELD_task_status . " = '{$status}' WHERE {$this->PRIMARY_KEY}  IN ( {$ids} )";
			return $this->query( $query );
		}
		public function selectCurrentsByVO( $VO ){
			$infoQuery = $this->getQueryFilteredByVO( $VO );
			//query basica no indice 0 na array
			$query 				= $infoQuery[0];
			$array_parameters	= $infoQuery[1];
			
			$query .= " AND  (".self::FIELD_start_date." IS NULL OR ".self::FIELD_start_date." <= NOW() )" ;
			
			return $this->select($query, $array_parameters);
		}
	}
	