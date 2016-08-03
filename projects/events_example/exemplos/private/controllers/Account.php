<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 03/08/16
 * Time: 18:22
 */

class Account {
	public function register(){
		//aqui eu registrei o usuario
		//fiz tudo que precisava
		$eventListenerInfo = new ARMListenerCallableInfoVO() ;
		$eventListenerInfo->callebleMethod = function($ob){ d($ob) ;dd( "eita" ) ; } ;
		$eventListenerInfo->listenerData = 1234;
		$eventListenerInfo->eventName = "lala" ;

		ARMEventsModule::getInstance()->addEventListener( $eventListenerInfo ) ;

		ARMEventsModule::getInstance()->dispatchEvent("lala");
	}
	public function meuMetodo( $ob ){
		//
		li("Account > meuMetodo ");
		dd( $ob ) ;
	}
}