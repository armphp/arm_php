<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * ! Please, don't change this file
	 * insted change ARMArmLogDAO class
	 * ArmLog
	 * @date 11/12/2013 07:12:18 
	 */ 
	abstract class ARMBaseArmLogDAOAbstract extends  ARMGenericDAO {

		/**
		* type : int(10) unsigned
		*/
		const FIELD_id = 'id';
		/**
		* type : datetime
		*/
		const FIELD_date_in = 'date_in';
		/**
		* type : int(10) unsigned
		*/
		const FIELD_user_id = 'user_id';
		/**
		* type : int(11)
		*/
		const FIELD_ref_id = 'ref_id';
		/**
		* type : varchar(255)
		*/
		const FIELD_ref_alias = 'ref_alias';
		/**
		* type : varchar(255)
		*/
		const FIELD_action = 'action';
		/**
		* type : varchar(255)
		*/
		const FIELD_action_label = 'action_label';
		/**
		* type : text
		*/
		const FIELD_data = 'data';
		
		/**
		* @return ArmLogDAO 
		*/
		public static function getInstance( $alias = ""){
			return parent::getInstance( $alias  ) ;
		}
		/**
		 *  @return ArmLogDAO 
		 */
		public static function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_INSTANCE_NAME ){
			return parent::getInstaceByConfigVO( $configVO , $alias ) ;
		}
		/**
		 * @return ArmLogDAO
		 */
		public static function getDefaultInstance() {
		 	return parent::getDefaultInstance() ;
		}
	}