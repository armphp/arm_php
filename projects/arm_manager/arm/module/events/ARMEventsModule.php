<?php
/**
 * @author: Renato Seiji Miawaki
 * Date: 20/02/16
 */

class ARMEventsModule extends ARMBaseModuleAbstract {

	/**
	 * Modos possíveis para um ListenerInfoVO
	 */
	const MODE_ARMMODULE 			= "ArmModule";// um módulo do arm para fazer um getInstance
	const MODE_INSTANCE				= "Instance";// uma instancia de objeto com metodo
	const MODE_STRING_NAME			= "String";// um metodo passado como string do nome ou metodo statico passado como stringpublic const MODE_INSTANCE			| uma instancia de objeto com metodo
	const MODE_CALLEBLE				= "Calleble";// um metodo calleble
	const MODE_CLASS_TO_INSTANCE 	= "ToInstance";// um objeto para dar new e chamar o metodo

	/**
	 * @var ARMDictionary
	 */
	private $listeners ;

	/**
	 * @param $eventName
	 * @param $data
	 */
	public function dispatchEvent( $eventName, $data = NULL ){
		/* @var $listenersToEvent ARMEventListenerInfoVO */
		$listenersToEvent = $this->getListenersOfEvent( $eventName ) ;
		if(!$listenersToEvent){
			return;
		}
		if(!is_array($listenersToEvent)){
			if(!$listenersToEvent->active){
				return;
			}
			$listenersToEvent->call( $data ) ;
			return;
		}
		foreach( $listenersToEvent as $listenerInfo /* @var ARMEventListenerInfoVO $listenerInfo */ ){
			if( ! $listenerInfo->active ){
				continue ;
			}
			$listenerInfo->call( $data ) ;
		}
	}
	/**
	 * @param $eventName
	 * @return ARMEventListenerInfoVO[]
	 */
	protected function getListenersOfEvent( $eventName ){
		return $this->getListeners()->get( $eventName ) ;
	}

	/**
	 *
	 * @param $eventName
	 * @param ARMEventListenerInfoVO $EventListenerInfoVO
	 * @return string token id
	 */
	public function addEventListener( $eventName, ARMEventListenerInfoVO $EventListenerInfoVO ){
		//add token
		$EventListenerInfoVO->token = ( $EventListenerInfoVO->callebleMethod ? "_":""). md5( $eventName.$EventListenerInfoVO->mode.$EventListenerInfoVO->className.$EventListenerInfoVO->methodName.$EventListenerInfoVO->moduleAlias.$EventListenerInfoVO->listenerData ) ;
		//para evitar problemas, remove sempre antes de adicionar
		$this->removeEventListener( $eventName, $EventListenerInfoVO->token ) ;
		//agora adiciona no dictionary
		$this->getListeners()->add( $eventName, $EventListenerInfoVO );
		return $EventListenerInfoVO->token ;
	}

	/**
	 * @param $eventName
	 * @param $token
	 */
	public function removeEventListener( $eventName, $token ){
		$events = $this->getListenersOfEvent( $eventName ) ;
		if(!$events){
			return ;
		}
		if( ! is_array( $events ) ){
			return ;
		}
		for( $i = 0 ; $i < count( $events ) ; $i ++ ){
			/* @var ARMEventListenerInfoVO $listenerInfo */
			$listenerInfo = $events[$i];
			if($listenerInfo->token == $token){
				array_splice($events, $i, 1);
				$listenerInfo->active = FALSE ;
				break;
			}
		}
		$this->listeners->set( $eventName ,$events );
	}
	/**
	 * @return ARMDictionary
	 */
	public function getListeners(){
		if(!$this->listeners){
			$this->listeners = new ARMDictionary();
		}
		return $this->listeners ;
	}

	/**
	 * Just for autocomplet
	 * @param null $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMEventsModule
	 */
	public static function getInstance ( $alias = NULL , $useDefaultIfNotFound = FALSE  ){
		return parent::getInstance( $alias, $useDefaultIfNotFound ) ;
	}
}