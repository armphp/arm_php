<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * ! Please, don't change this file
	 * insted change ARMARMFileDAO class
	 * ARMFile
	 * @date 07/01/2014 06:01:52 
	 */ 
	abstract class ARMBaseARMFileDAOAbstract extends  ARMBaseDAOAbstract {
		
		protected $TABLE_NAME = 'file';
		
		
		/**
		* type : int(10) unsigned
		*/
		const FIELD_id = 'id';
		/**
		* type : tinyint(4)
		*/
		const FIELD_active = 'active';
		/**
		* type : int(11)
		*/
		const FIELD_order = 'order';
		/**
		* type : varchar(100)
		*/
		const FIELD_type = 'type';
		/**
		* type : varchar(200)
		*/
		const FIELD_ref_alias = 'ref_alias';
		/**
		* type : int(11)
		*/
		const FIELD_ref_id = 'ref_id';
		/**
		* type : varchar(255)
		*/
		const FIELD_url = 'url';
		/**
		* type : varchar(255)
		*/
		const FIELD_name = 'name';
		/**
		* type : text
		*/
		const FIELD_description = 'description';
		
		/**
		* @return ARMFileDAO 
		*/
		public static function getInstance( $alias = ""){
			return parent::getInstance( $alias  ) ;
		}
		/**
		 *  @return ARMFileDAO 
		 */
		public static function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_INSTANCE_NAME ){
			return parent::getInstaceByConfigVO( $configVO , $alias ) ;
		}
		/**
		 * @return ARMFileDAO
		 */
		public static function getDefaultInstance() {
		 	return parent::getDefaultInstance() ;
		}
	}