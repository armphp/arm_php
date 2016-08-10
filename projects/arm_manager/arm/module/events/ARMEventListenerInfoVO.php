<?php
/**
 * ARM PHP Framework
 * @author: Renato Seiji Miawaki
 * Date: 20/02/16
 *
 * Um event listener pode ser:
 *
 * ARMListenerArmModuleInfoVO		| um módulo do arm para fazer um getInstance
 * ARMListenerInstanceInfoVO		| uma instancia de objeto com metodo
 * ARMListenerMethodStringInfoVO	| um metodo passado como string do nome ou metodo statico passado como string
 * ARMListenerCallableInfoVO		| um metodo calleble
 * ARMListenerClassToInstanceInfoVO	| um objeto para dar new e chamar o metodo
 *
 */



abstract class ARMEventListenerInfoVO extends ARMAutoParseAbstract{
	public $eventName = "" ;
	/**
	 * if not true, it does not execute the event
	 * @var bool
	 */
	public $active = TRUE ;
	/**
	 * do not use this, its an automatic value
	 * token of method name and config of this listener
	 * @var string
	 */
	public $token ;

	/**
	 * optional value to recive when event happens
	 * @var ?
	 */
	public $listenerData ;

	/**
	 * Call this listener
	 */
	public function call( $data = NULL ){
		if( ! $this->active ){
			return ;
		}
		$eventInfo = new ARMEventReciveInfoVO($this->count++, $data);
		$eventInfo->listenerData = $this->listenerData;
		$this->doCall( $eventInfo );
	}

	/**
	 * implemente o metodo doCall
	 * @param ARMEventReciveInfoVO $e
	 * @return mixed
	 */
	protected abstract function doCall( ARMEventReciveInfoVO $e ) ;
	protected $count = 0 ;

	/**
	 * Unique token
	 * @return string
	 */
	public abstract function getToken() ;
}