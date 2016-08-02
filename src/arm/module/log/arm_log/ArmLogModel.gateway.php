<?php
	/**
	* created by ARMModelGatewayMaker ( automated system ) 
	*
	* @date 11/12/2013 07:12:18 
	* @baseclass ARMBaseSingletonAbstract 
	*/
	class ArmLogModelGateway extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
		/**
		* @return ArmLogModelGateway 
		*/
		public static function getInstance( $alias = "" ){
			return parent::getInstance( $alias ) ;
		}
		/**
		* @return ArmLogEntity
		*/
		function getEntity(){
			return new ArmLogEntity() ;
		}
		/**
		* @return ArmLogVO
		*/
		function getVO(){
			return new ArmLogVO() ;
		}

		/**
		 * @var string
		 */
		public static $current_table ;
		/**
		* @return ArmLogDAO
		*/
		function getDAO( $alias = NULL ){
			//se nao foi enviado alias, tenta usar padrao
			if( ! $alias ){

				$default = ArmLogDAO::getDefaultInstance() ;
				if( $default ){
					return $default ;
				}
				//se não foi setado default, vai buscar a instance por nada
			}

			$instance =  ArmLogDAO::getInstance( $alias ) ;
			$instance->setTable( self::$current_table ) ;
			return $instance ;
		}
	}
		