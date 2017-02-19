<?php
	/**
	* created by ARMModelGatewayMaker ( automated system ) 
	*
	* @date 08/01/2014 03:01:43 
	* @baseclass ARMBaseSingletonAbstract 
	*/
	class ARMFileModelGateway extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
		/**
		* @return ARMFileModelGateway 
		*/
		public static function getInstance( $alias = "" ){
			return parent::getInstance( $alias ) ;
		}
		/**
		* @return ARMFileEntity
		*/
		function getEntity(){
			return new ARMFileEntity() ;
		}
		/**
		* @return ARMFileVO
		*/
		function getVO(){
			return new ARMFileVO() ;
		}
		/**
		* @return ARMFileDAO
		*/
		function getDAO( $alias = NULL ){
			//se nao foi enviado alias, tenta usar padrao
			if( ! $alias ){
				$default = ARMFileDAO::getDefaultInstance() ;
				if( $default ){
					return $default ;
				}
				//se não foi setado default, vai buscar a instance por nada
			}
			return ARMFileDAO::getInstance( $alias ) ;
		}
	}
		