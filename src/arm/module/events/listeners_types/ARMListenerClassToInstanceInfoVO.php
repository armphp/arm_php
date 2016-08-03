<?php
/**
 * Listener que chama um metodo de uma classe que ele mesmo instancia
 *
 * Esse tipo de modo não pode ser iniciado diretamente pelo config json
 * @author: Renato Miawaki
 * Date: 26/02/16
 */

class ARMListenerClassToInstanceInfoVO  extends ARMEventListenerInfoVO{
	/**
	 * for MODE_ARMMODULE | MODE_CLASS_TO_INSTANCE
	 * @var string
	 */
	public $className ;

	/**
	 * for modes:
	 * MODE_ARMMODULE | MODE_INSTANCE | MODE_STRING_NAME | MODE_CLASS_TO_INSTANCE
	 * if is a static use StaticName::methodName
	 * if a method of instance use string name
	 * @var string
	 */
	public $methodName ;

	/**
	 * Para classes que precisam ser instanciadas
	 * @param ARMEventReciveInfoVO $eventInfo
	 */
	protected function doCall( ARMEventReciveInfoVO $eventInfo ){
		if( !$this->methodName ){
			return;
		}
		if(!$this->className){
			return;
		}
		$className = "{$this->className}" ;
		ARMClassIncludeManager::load( $this->className ) ;
		$instance = new $className() ;
		$methodName = "{$this->mehodName}" ;
		$instance->$methodName($eventInfo) ;
	}
	/**
	 * Override method
	 * Simbolia esse evento para fácil localização
	 * @return string
	 */
	public function getToken(){
		return md5( $this->eventName.$this->className .$this->listenerData . $this->methodName) ;
	}
}