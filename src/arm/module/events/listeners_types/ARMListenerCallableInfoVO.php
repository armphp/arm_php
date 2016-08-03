<?php
/**
 * @author : Renato Seiji Miawaki
 * Date: 26/02/16
 */

class ARMListenerCallableInfoVO extends ARMEventListenerInfoVO{
	/**
	 * just for MODE_CALLABLE
	 * @var callable
	 */
	public $callebleMethod ;
	/**
	 * Para metodos
	 * @param ARMEventReciveInfoVO $eventInfo
	 */
	protected function doCall( ARMEventReciveInfoVO $eventInfo ){
		if(!$this->callebleMethod || ! is_callable( $this->callebleMethod ) ){
			return;
		}
		call_user_func($this->callebleMethod, $eventInfo) ;
		dd( 1 ) ;
	}
	/**
	 * Override method
	 * Simbolia esse evento para fácil localização
	 * @return string
	 */
	public function getToken(){
		return md5( $this->eventName.print_r( $this->callebleMethod , true ).$this->listenerData ) ;
	}
}