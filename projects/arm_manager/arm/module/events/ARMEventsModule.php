<?php
/**
 * @author: Renato Seiji Miawaki
 * Date: 20/02/16
 */

class ARMEventsModule extends ARMBaseModuleAbstract {

	/**
	 * Modos possíveis para um ListenerInfoVO
	 */
//	const MODE_ARMMODULE 			= "ArmModule";// um módulo do arm para fazer um getInstance
//	const MODE_INSTANCE				= "Instance";// uma instancia de objeto com metodo
//	const MODE_STRING_NAME			= "String";// um metodo passado como string do nome ou metodo statico passado como stringpublic const MODE_INSTANCE			| uma instancia de objeto com metodo
//	const MODE_CALLEBLE				= "Calleble";// um metodo calleble
//	const MODE_CLASS_TO_INSTANCE 	= "ToInstance";// um objeto para dar new e chamar o metodo

	/**
	 * @var ARMDictionary
	 */
	private $listeners ;

	public function setConfig($ob){
		parent::setConfig($ob) ;
		//verificando se tem listeners padrões para adicionar
		$listeners = ARMDataHandler::getValueByStdObjectIndex( $ob, "defaultListenersArmModule" ) ;
		if( $listeners && is_array( $listeners )){
			foreach( $listeners as $listener ){
				$listenerVO = new ARMListenerArmModuleInfoVO();
				$listenerVO->parseObject($listener) ;
				$this->addEventListener(  $listenerVO );
			}
		}
		$listeners = ARMDataHandler::getValueByStdObjectIndex( $ob, "defaultListenersStringMehodName" ) ;
		if( $listeners && is_array( $listeners )){
			foreach( $listeners as $listener ){
				$listenerVO = new ARMListenerMethodStringInfoVO();
				$listenerVO->parseObject($listener) ;
				$this->addEventListener(  $listenerVO );
			}
		}
		$listeners = ARMDataHandler::getValueByStdObjectIndex( $ob, "defaultListenersClassToInstance" ) ;
		if( $listeners && is_array( $listeners )){
			foreach( $listeners as $listener ){
				$listenerVO = new ARMListenerMethodStringInfoVO();
				$listenerVO->parseObject($listener) ;
				$this->addEventListener(  $listenerVO );
			}
		}
	}
	const ON_EVENT_scheduleCronEvent = "ARMEventModule_scheduleCronEvent" ;
	/**
	 * Adicione um evento para ser executado pelo cron
	 * @param $eventName string
	 * @param $personalUniqueToken string chave única sua para que consiga deletar o evento depois
	 * @param $when string datetime db format
	 * @param $param
	 */
	public function scheduleCronEvent( $eventName, $personalUniqueToken, $when, $param = NULL ){
		if( ! ARMDataHandler::getValueByStdObjectIndex( $this->_config , "permitScheduleCronEvent" ) ){
			return FALSE ;
		}
		$vo = ARMCronEventModelGateway::getInstance()->getVO() ;
		$vo->date_in = ARMCronEventDAO::DATA_NOW ;
		//active 1 significa que está ativo esperando para ser executado, ao mudar pra zero é porque foi executado pelo cron
		$vo->active = 1 ;
		$vo->date_to_show = $when ;
		$vo->event_info = json_encode($param) ;
		$vo->token 		= $personalUniqueToken ;
		$vo->event_name = $eventName ;
		//salvando o evento
		ARMCronEventModelGateway::getInstance()->getDAO()->commitVO($vo) ;
		//o próprio Event dispara eventos se configurado para disparar
		//
		$this->dispatchSelfEvent( self::ON_EVENT_scheduleCronEvent , array("eventName"=> $eventName, "when"=>$when, "param"=>$param, "token"=>$personalUniqueToken ) ) ;
	}

	/**
	 * Remove um evento agendado da lista, independente de disparado ou não
	 * @param $eventName
	 * @param $token chave para reconhecer de que evento se está falando
	 * @return bool
	 */
	public function removeScheduleCronEvent( $eventName, $token ){
		$vo = ARMCronEventModelGateway::getInstance()->getVO() ;
		$vo->event_name = $eventName ;
		$vo->token = $token ;
		return ARMCronEventModelGateway::getInstance()->getDAO()->deleteByVO( $vo )->success ;
	}

	/**
	 * Retorna uma lista com os eventos
	 * @return ARMReturnDataVO
	 */
	public function getScheduleCronEvents(){
		return ARMCronEventModelGateway::getInstance()->getDAO()->selectScheduleEvents() ;
	}
	/**
	 * Encapsulamento para uso interno, disparando o próprio evento se estiver configurado para isso
	 * @param $eventName
	 * @param $param
	 */
	private function dispatchSelfEvent($eventName, $param){
		if( ARMDataHandler::getValueByStdObjectIndex( $this->_config , "dispatchSelfEvents" ) ){
			//se for setado no config para ele disparar os próprios eventos, ele dispara
			$this->dispatchEvent($eventName, $param) ;
		}
	}
	/**
	 * @param $eventName
	 * @param $data
	 */
	public function dispatchEvent( $eventName, $param = NULL ){
		/* @var $listenersToEvent ARMEventListenerInfoVO */
		$listenersToEvent = $this->getListenersOfEvent( $eventName ) ;
		if(!$listenersToEvent){
			return;
		}

		$this->dispatchSelfEvent("ARMEventModule_dispatchEvent", $param ) ;

		if(!is_array($listenersToEvent)){
			$this->doDispatchToListener( $listenersToEvent, $param ) ;
			return;
		}

		foreach( $listenersToEvent as $listenerInfo /* @var ARMEventListenerInfoVO $listenerInfo */ ){
			$this->doDispatchToListener( $listenerInfo, $param ) ;
		}
	}

	/**
	 * encapsulamento para dispatchEvent utilizar em array ou item único
	 * Faz o call do evento em si passando o parametro como obj se for json
	 * @param $listenerToEvent
	 * @param $param
	 */
	protected function doDispatchToListener( $listenerToEvent, $param ){
		if(!$listenerToEvent->active){
			return;
		}
		if( is_string( $param ) ){
			//seria um json?
			$jsonObj = json_decode( $param ) ;
			if( json_last_error() == JSON_ERROR_NONE){
				//é json, passa pro parametro
				$param = $jsonObj ;
			}
		}

		$this->dispatchSelfEvent("ARMEventModule_doDispatchToListener", $listenerToEvent ) ;
		$listenerToEvent->call( $param ) ;
	}
	/**
	 * @param $eventName
	 * @return ARMEventListenerInfoVO[]
	 */
	protected function getListenersOfEvent( $eventName ){
		return $this->getListeners()->get( $eventName ) ;
	}
	const ON_EVENT_addEventListener = "ARMEventModule_addEventListener" ;

	/**
	 *
	 * @param $eventName
	 * @param ARMEventListenerInfoVO $EventListenerInfoVO
	 * @return string token id
	 */
	public function addEventListener( ARMEventListenerInfoVO $EventListenerInfoVO ){
		if(!$EventListenerInfoVO->eventName){
			$this->dispatchSelfEvent("ARMEventModule_addEventListenerError", "send the event name" ) ;
			return ;
		}
		//add token
		$EventListenerInfoVO->token = $EventListenerInfoVO->getToken() ;
		//para evitar problemas, remove sempre antes de adicionar
		$this->removeEventListener( $EventListenerInfoVO->eventName, $EventListenerInfoVO->token ) ;
		//agora adiciona no dictionary
		$this->getListeners()->add( $EventListenerInfoVO->eventName, $EventListenerInfoVO );
		$this->dispatchSelfEvent(self::ON_EVENT_addEventListener, array("eventName"=> $EventListenerInfoVO->eventName, "EventListenerInfoVO"=>$EventListenerInfoVO ) ) ;
		return $EventListenerInfoVO->token ;
	}
	const ON_EVENT_removeEventListener = "ARMEventModule_removeEventListener" ;
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
		$this->dispatchSelfEvent("ARMEventModule_removeEventListener", array("eventName"=> $eventName, "token"=>$token ) ) ;
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
	 *
	 * @param $item ARMCronEventVO
	 * @throws ErrorException
	 * @throws Exception
	 */
	public function addLog( $item ){
		$logInfo = new ARMLogInfoVO() ;
		$logInfo->action = "cron_event" ;
		$logInfo->action_label = ARMDataHandler::getValueByStdObjectIndex( $item, "date_to_show" ) ;
		$logInfo->date_in = ARMCronEventDAO::DATA_NOW ;
		$logInfo->ref_alias = ARMDataHandler::getValueByStdObjectIndex( $item, "event_name" ) ;
		$logInfo->ref_id = ARMDataHandler::getValueByStdObjectIndex( $item, "id" ) ;
		$logInfo->data = print_r( $item, true ) ;
		ARMLogModule::getInstance("cron_event")->addLog($logInfo);
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