<?php
/**
 * Listener que instancia um módulo do ARM, singleton, podendo passar um alias para pegar o config correspondente
 * @author: Renato Miawaki
 * Date: 26/02/16
 */

class ARMListenerArmModuleInfoVO extends ARMEventListenerInfoVO{
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
	 * for modes:
	 * MODE_ARMMODULE | MODE_INSTANCE | MODE_STRING_NAME | MODE_CLASS_TO_INSTANCE
	 * if is a static use StaticName::methodName
	 * if a method of instance use string name
	 * @var string
	 */
	public $methodName ;

	/**
	 * @param $eventInfo ARMEventReciveInfoVO
	 */
	public function doCall( ARMEventReciveInfoVO $eventInfo ){
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
		$log = new ARMLogInfoVO() ;
		$log->action = "ARMListenerArmModuleInfoVO.doCall" ;
		$log->action_label = "do call:" ;
		$log->date_in = MDemoProductDAO::DATA_NOW ;
		$log->ref_alias = " " ;
		$log->data = $eventInfo ;
		ARMLogModule::getInstance("events")->addLog( $log ) ;
		$ModuleInstance->{$methodName}( $eventInfo ) ;
	}

	/**
	 * Override method
	 * Simbolia esse evento para fácil localização
	 * @return string
	 */
	public function getToken(){
		return md5( $this->eventName.$this->className.$this->listenerData ) ;
	}
}