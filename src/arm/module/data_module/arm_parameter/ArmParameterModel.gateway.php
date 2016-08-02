<?php
	/**
	* created by ARMModelGatewayMaker ( automated system ) 
	*
	* @date 17/01/2014 05:01:07 
	* @baseclass ARMBaseSingletonAbstract 
	*/
	class ArmParameterModelGateway extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
		/**
		* @return ArmParameterModelGateway 
		*/
		public static function getInstance( $alias = "" ){
			return parent::getInstance( $alias ) ;
		}
		/**
		* @return ArmParameterEntity
		*/
		function getEntity(){
			return new ArmParameterEntity() ;
		}
		/**
		* @return ArmParameterVO
		*/
		function getVO(){
			return new ArmParameterVO() ;
		}
		/**
		* @return ArmParameterDAO
		*/
		function getDAO( $alias = NULL ){
			//se nao foi enviado alias, tenta usar padrao
			if( ! $alias ){
				$default = ArmParameterDAO::getDefaultInstance() ;
				if( $default ){
					return $default ;
				}
				//se não foi setado default, vai buscar a instance por nada
			}
			return ArmParameterDAO::getInstance( $alias ) ;
		}
	}
		