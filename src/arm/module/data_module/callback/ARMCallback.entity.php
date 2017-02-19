<?php
/**
 * created by ARMEntityMaker ( automated system )
 * Callback
 * @date 14/05/2013 05:05:55
 */ 

class ARMCallbackEntity extends ARMBaseEntityAbstract{
	/**
	* @return ARMCallbackDAO
	*/
	
	public function getDAO( $alias = "" ){
		return ARMCallbackDAO::getInstance();
	}
	protected function startVO(){
		if(!$this->VO){
			$this->VO = ARMCallbackModelGateway::getInstance()->getVO();
		}
	}
	
	/**
	 * @return ARMCallbackVO
	 */
	public function getVO(){
		return parent::getVO();
	}
}
	