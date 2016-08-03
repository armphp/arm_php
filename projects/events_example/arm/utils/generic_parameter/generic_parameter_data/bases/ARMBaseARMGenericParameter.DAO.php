<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * ! Please, don't change this file
	 * insted change ARMARMGenericParameterDAO class
	 * ARMGenericParameter
	 * @date 18/03/2014 01:03:21 
	 */ 
	abstract class ARMBaseARMGenericParameterDAOAbstract extends  ARMBaseDAOAbstract {
		
		protected $TABLE_NAME = 'generic_parameter';
		
		
		/**
		* type : bigint(20) unsigned
		*/
		const FIELD_id = 'id';
		/**
		* type : int(11)
		*/
		const FIELD_active = 'active';
		/**
		* type : int(10) unsigned
		*/
		const FIELD_ref_id = 'ref_id';
		/**
		* type : varchar(255)
		*/
		const FIELD_ref_name = 'ref_name';

//		/**
//		 * type : varchar(255)
//		 */
//		const FIELD_data_name = 'data_name';
//

		/**
		* type : longtext
		*/
		const FIELD_data = 'data';
		
		/**
		* @return ARMGenericParameterDAO 
		*/
		public static function getInstance( $alias = ""){
			return parent::getInstance( $alias  ) ;
		}
		/**
		 *  @return ARMGenericParameterDAO 
		 */
		public static function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_INSTANCE_NAME ){
			return parent::getInstaceByConfigVO( $configVO , $alias ) ;
		}
		/**
		 * @return ARMGenericParameterDAO
		 */
		public static function getDefaultInstance() {
		 	return parent::getDefaultInstance() ;
		}
	}