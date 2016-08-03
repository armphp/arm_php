<?php
/**
 * Listener que executa um metodo pelo nome, metodo solto ou estático de uma classe
 * @author: Renato Miawaki
 * Date: 26/02/16
 */

class ARMListenerMethodStringInfoVO extends ARMEventListenerInfoVO {
	public $methodName ;
	/**
	 * Para essa classe é opcional
	 * porém se for utilizar uma classe para chamar um metodo estático, escreva o nome da classe para que a mesma esteja no projeto
	 * @var string
	 */
	public $className ;
	/**
	 * pelo nome do metodo estatico ou nao
	 * @param ARMEventReciveInfoVO $eventInfo
	 */
	protected function doCall( ARMEventReciveInfoVO $eventInfo ){
		if( !$this->methodName ){
			return;
		}
		if ($this->className) ARMClassIncludeManager::load( $this->className ) ;
		call_user_func( $this->methodName , $eventInfo ) ;
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