<?php
/**
 * created by ARMModelGatewayMaker ( automated system )
 *
 * @date 21/10/2013 09:10:22
 * @baseclass ARMBaseSingletonAbstract
 */
class ARMCallbackModelGateway extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
	/**
	 * @return ARMCallbackModelGateway
	 */
	public static function getInstance( $alias = "" ){
		return parent::getInstance( $alias ) ;
	}
	/**
	 * @return ARMCallbackEntity
	 */
	function getEntity(){
		return new ARMCallbackEntity() ;
	}
	/**
	 * @return ARMCallbackVO
	 */
	function getVO(){
		return new ARMCallbackVO() ;
	}
	/**
	 * @return ARMCallbackDAO
	 */
	function getDAO(){
		return ARMCallbackDAO::getInstance() ;
	}
}
		