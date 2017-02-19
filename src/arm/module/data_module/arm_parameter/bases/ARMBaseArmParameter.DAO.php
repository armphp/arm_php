<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * ! Please, don't change this file
	 * insted change ARMArmParameterDAO class
	 * ArmParameter
	 * @date 17/01/2014 05:01:07 
	 */ 
	abstract class ARMBaseArmParameterDAOAbstract extends  ARMBaseDAOAbstract {
		
		protected $TABLE_NAME = 'arm_parameter';
		
		
		/**
		* type : int(10) unsigned
		*/
		const FIELD_id = 'id';
		/**
		* type : int(11)
		*/
		const FIELD_active = 'active';
		/**
		* type : int(11)
		*/
		const FIELD_order = 'order';
		/**
		* type : varchar(400)
		*/
		const FIELD_ref_alias = 'ref_alias';
		/**
		* type : int(11)
		*/
		const FIELD_ref_id = 'ref_id';
		/**
		* type : text
		*/
		const FIELD_value = 'value';
		
		/**
		* @return ArmParameterDAO 
		*/
		public static function getInstance( $alias = ""){
			return parent::getInstance( $alias  ) ;
		}
		/**
		 *  @return ArmParameterDAO 
		 */
		public static function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_INSTANCE_NAME ){
			return parent::getInstaceByConfigVO( $configVO , $alias ) ;
		}
		/**
		 * @return ArmParameterDAO
		 */
		public static function getDefaultInstance() {
		 	return parent::getDefaultInstance() ;
		}
	}