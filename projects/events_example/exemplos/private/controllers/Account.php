<?php
/**
 * Renato Miawaki
 * User: renato
 * Date: 03/08/16
 * Time: 18:22
 */

class Account {
	public function __construct(){

		############ ADICIONANDO-SE COMO LISTENERS DE EVENTOS ##########
		################################################[EXEMPLO 1]########################################################
		//Listener de evento para quando acontece um registro com sucesso
		$eventListenerInfo = new ARMListenerCallableInfoVO() ;
		$eventListenerInfo->callebleMethod = function($ob){
			//echo
			li( "chamou a função anonima!".__CLASS__." > ".__METHOD__." ˜ ".__LINE__ ) ;
			//dump
			dd($ob) ;

		} ;
		$eventListenerInfo->listenerData = 1234;
		$eventListenerInfo->eventName = "Account.register.success" ;
		ARMEventsModule::getInstance()->addEventListener( $eventListenerInfo ) ;

		################################################[EXEMPLO 2]########################################################
		//Listener de evento para quando acontece um erro de registro
		$eventListenerInfo2 = new ARMListenerClassToInstanceInfoVO() ;
		$eventListenerInfo2->className = "Account" ;
		$eventListenerInfo2->methodName = "meuMetodo" ;
		$eventListenerInfo2->listenerData = 4444 ;
		$eventListenerInfo2->eventName = "Account.register.fail" ;
		ARMEventsModule::getInstance()->addEventListener( $eventListenerInfo2 ) ;

	}
	public function register(){

		########## DISPARANDO EVENTOS #############
		d("register .. ") ;
		ARMEventsModule::getInstance()->dispatchEvent("testando.debug", ["debugando uma array nada a ver"] );
		//aqui eu tentei registrar o usuario, e o resultado foi:
		$sucess = false ;
		$userInfo = ["name"=>"Renato Miawaki"];
		if($sucess){
			ARMEventsModule::getInstance()->dispatchEvent("Account.register.success", $userInfo );
			return $sucess ;
		}
		ARMEventsModule::getInstance()->dispatchEvent("Account.register.fail", "Email repetido." );
		return $sucess ;
	}
	public function meuMetodo( $ob ){
		//echo
		li( "chamou meuMetodo!".__CLASS__." > ".__METHOD__." ˜ ".__LINE__ ) ;
		//dump
		dd($ob) ;
	}
}