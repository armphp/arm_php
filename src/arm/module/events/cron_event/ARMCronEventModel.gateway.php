<?php
	/**
	* created by ARMModelGatewayMaker ( automated system ) 
	*
	* @date 26/02/2016 04:02:34 
	* @baseclass ARMBaseSingletonAbstract 
	*/
	class ARMCronEventModelGateway extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
		/**
		* @return ARMCronEventModelGateway 
		*/
		public static function getInstance( $alias = "" ){
			return parent::getInstance( $alias ) ;
		}
		/**
		* @return ARMCronEventEntity
		*/
		function getEntity(){
			return new ARMCronEventEntity() ;
		}
		/**
		* @return ARMCronEventVO
		*/
		function getVO(){
			return new ARMCronEventVO() ;
		}
		/**
		* @return ARMCronEventDAO
		*/
		function getDAO( $alias = NULL ){
			//se nao foi enviado alias, tenta usar padrao
			if( ! $alias ){
				$default = ARMCronEventDAO::getDefaultInstance() ;
				if( $default ){
					return $default ;
				}
				//se não foi setado default, vai buscar a instance por nada
			}
			return ARMCronEventDAO::getInstance( $alias ) ;
		}
	}
		