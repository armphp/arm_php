<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * ! Please, don't change this file
	 * insted change ARMARMCronEventDAO class
	 * ARMCronEvent
	 * @date 26/02/2016 04:02:34 
	 */ 
	abstract class ARMBaseARMCronEventDAOAbstract extends  ARMBaseDAOAbstract {
		
		protected $TABLE_NAME = 'cron_event';
		
		
		/**
		* type : int(10) unsigned
		*/
		const FIELD_id = 'id';
		/**
		* type : int(11)
		*/
		const FIELD_active = 'active';
		/**
		* type : datetime
		*/
		const FIELD_date_in = 'date_in';
		/**
		* type : datetime
		*/
		const FIELD_date_to_show = 'date_to_show';
		/**
		* type : text
		*/
		const FIELD_event_info = 'event_info';
		/**
		 * type : varchar(255)
		 */
		const FIELD_token = 'token';
		/**
		* type : varchar(255)
		*/
		const FIELD_event_name = 'event_name';
		
		/**
		* @return ARMCronEventDAO 
		*/
		public static function getInstance( $alias = ""){
			return parent::getInstance( $alias  ) ;
		}
		/**
		 *  @return ARMCronEventDAO 
		 */
		public static function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_INSTANCE_NAME ){
			return parent::getInstaceByConfigVO( $configVO , $alias ) ;
		}
		/**
		 * @return ARMCronEventDAO
		 */
		public static function getDefaultInstance() {
		 	return parent::getDefaultInstance() ;
		}
	}