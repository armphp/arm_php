<?php
/**
 * Listener que chama um metodo de uma instancia já existente
 *
 * Esse tipo de modo não pode ser iniciado diretamente pelo config json
 * @author: Renato Miawaki
 * Date: 26/02/16
 */

class ARMListenerInstanceInfoVO extends ARMEventListenerInfoVO{

	/**
	 * just for MODE_INSTANCE
	 * instance of a object. Need to have a method to call
	 * @var object
	 */
	public $instance ;
	/**
	 * for modes:
	 * MODE_ARMMODULE | MODE_INSTANCE | MODE_STRING_NAME | MODE_CLASS_TO_INSTANCE
	 * if is a static use StaticName::methodName
	 * if a method of instance use string name
	 * @var string
	 */
	public $methodName ;

	/**
	 * para o MODO de instancias de objetos já existentes
	 * @param $eventInfo ARMEventReciveInfoVO
	 */
	protected function doCall( ARMEventReciveInfoVO $eventInfo ){
		if(!$this->instance){
			return ;
		}
		if( ! method_exists( $this->instance , $this->methodName ) ){
			return ;
		}
		$this->instance->{$this->methodName}($eventInfo);
	}
	/**
	 * Override method
	 * Simbolia esse evento para fácil localização
	 * @return string
	 */
	public function getToken(){
		return md5( $this->eventName. print_r( $this->instance , TRUE ).$this->listenerData . $this->methodName) ;
	}
}