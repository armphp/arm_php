<?php
/**
 * 
 * Classe encapsulada para uso de session
 * @author Alan Lucian , Renato Miawaki
 *
 */
class ARMSession{
	/**
	 * Inicia a session se já não estiver iniciada
	 */
    public static function start(){
    	
        if(strlen(session_id()) == 0 ){
        	//@FIX: ARRUMAR ISSO PRO NOVO ESKEMA 
            //Config::includeSessionClass();  
            
            session_start();
        }
    }
    /**
     * encapsulando para facilitaro uso
     * @param string $varName
     * @return unknown|NULL
     */
    public static function getVar( $varName ){
    	self::start() ;
    	if( isset( $_SESSION[ $varName ] ) ){
    		return $_SESSION[ $varName ] ;
    	}
    	return NULL ; 
    }
    /**
     * Salva na session
     * @param string $varName
     * @param unknown $value
     */
    public static function setVar( $varName , $value ){
    	self::start() ;
    	$_SESSION[ $varName ] = $value ;
    }
}