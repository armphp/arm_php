<?php
/**
 * ARM Framework
 * @author: Renato Seiji Miawaki
 * Date: 20/02/16
 *
 * Um event listener pode ser:
 *
 * MODE_ARMMODULE 			| um módulo do arm para fazer um getInstance
 * MODE_INSTANCE			| uma instancia de objeto com metodo
 * MODE_STRING_NAME			| um metodo passado como string do nome ou metodo statico passado como string
 * MODE_CALLEBLE			| um metodo calleble
 * MODE_CLASS_TO_INSTANCE 	| um objeto para dar new e chamar o metodo
 *
 */

class ARMEventListenerInfoVO extends ARMAutoParseAbstract{
	/**
	 * @var
	 */
	public $active = TRUE ;
	/**
	 * do not use this, its an automatic value
	 * token of method name and config of this listener
	 * @var string
	 */
	public $token ;
	/**
	 * MODE_ARMMODULE 			| um módulo do arm para fazer um getInstance
	 * MODE_INSTANCE			| uma instancia de objeto com metodo
	 * MODE_STRING_NAME			| um metodo passado como string do nome ou metodo statico passado como string
	 * MODE_CALLEBLE			| um metodo calleble
	 * MODE_CLASS_TO_INSTANCE 	| um objeto para dar new e chamar o metodo
	 */
	public $mode ;

	/**
	 * for MODE_ARMMODULE | MODE_CLASS_TO_INSTANCE
	 * @var string
	 */
	public $className ;
	/**
	 * just for MODE_ARMMODULE
	 * @var string
	 */
	public $moduleAlias = "" ;
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
	 * just for MODE_CALLEBLE
	 * @var callable
	 */
	public $callebleMethod ;
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
		$methodHandler = "callMode".$this->mode ;
		if( method_exists($this, $methodHandler ) ) {
			$eventInfo = new ARMEventInfoVO($this->count++, $data);
			$eventInfo->listenerData = $this->listenerData;
			$this->$methodHandler( $eventInfo );
			return;
		}
	}
	protected $count = 0 ;

	/**
	 * @param $eventInfo ARMEventInfoVO
	 */
	protected function callModeArmModule( ARMEventInfoVO $eventInfo ){
		//validando
		if(!$this->className){
			return;
		}
		if(!$this->methodName){
			return;
		}
		ARMClassIncludeManager::load($this->className) ;
		$ModuleInstance  = call_user_func( $this->className."::getInstance" , $this->moduleAlias ) ;
		$methodName = $this->methodName ;
		if( ! method_exists( $ModuleInstance ,  $methodName ) ){
			return ;
		}
		$ModuleInstance->{$methodName}( $eventInfo ) ;
	}

	/**
	 * para o MODO de instancias de objetos já existentes
	 * @param $eventInfo ARMEventInfoVO
	 */
	protected function callModeInstance( ARMEventInfoVO $eventInfo ){
		if(!$this->instance){
			return ;
		}
		if( ! method_exists( $this->instance , $this->methodName ) ){
			return ;
		}
		$this->instance->{$this->methodName}($eventInfo);
	}

	/**
	 * pelo nome do metodo estatico ou nao
	 * @param ARMEventInfoVO $eventInfo
	 */
	protected function callModeString( ARMEventInfoVO $eventInfo ){
		if( !$this->methodName ){
			return;
		}
		if ($this->className) ARMClassIncludeManager::load( $this->className ) ;
		call_user_func( $this->methodName , $eventInfo ) ;
	}

	/**
	 * Para metodos
	 * @param ARMEventInfoVO $eventInfo
	 */
	protected function callModeCalleble( ARMEventInfoVO $eventInfo ){
		if(!$this->callebleMethod || ! is_callable( $this->callebleMethod ) ){
			return;
		}
		$this->callebleMethod( $eventInfo ) ;
	}

	/**
	 * Para classes que precisam ser instanciadas
	 * @param ARMEventInfoVO $eventInfo
	 */
	protected function callModeToInstance( ARMEventInfoVO $eventInfo ){
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

}