<?php
	/**
	* created by ARMModelGatewayMaker ( automated system ) 
	*
	* @date 18/03/2014 01:03:21 
	* @baseclass ARMBaseSingletonAbstract 
	*/
	class ARMGenericParameterModelGateway extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
		/**
		* @return ARMGenericParameterModelGateway 
		*/
		public static function getInstance( $alias = "" ){
			return parent::getInstance( $alias ) ;
		}
		/**
		* @return ARMGenericParameterEntity
		*/
		function getEntity(){
			return new ARMGenericParameterEntity() ;
		}
		/**
		* @return ARMGenericParameterVO
		*/
		function getVO(){
			return new ARMGenericParameterVO() ;
		}
		/**
		* @return ARMGenericParameterDAO
		*/
		function getDAO( $alias = NULL ){
			//se nao foi enviado alias, tenta usar padrao
			if( ! $alias ){
				$default = ARMGenericParameterDAO::getDefaultInstance() ;
				if( $default ){
					return $default ;
				}
				//se não foi setado default, vai buscar a instance por nada
			}
			return ARMGenericParameterDAO::getInstance( $alias ) ;
		}
	}
		