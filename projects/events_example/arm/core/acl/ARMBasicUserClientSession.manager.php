<?php

/**
 * Classe statica básica para tratar usuarios logados
 * @author renatomiawaki
 *
 */
class ARMBasicUserClientSession implements ARMBasicUserInfoInterface {
	const SESSION_VAR_ID 				= "SESSION_VAR_CLIENT_ID" ;
	const SESSION_VAR_TYPE_ID 			= "SESSION_VAR_CLIENT_TYPE_ID" ;
	const SESSION_VAR_TYPE_ALIAS		= "SESSION_VAR_CLIENT_TYPE_ALIAS" ;
	const SESSION_VAR_TYPE_INTERFACE	= "SESSION_VAR_TYPE_INTERFACE" ;
	const SESSION_VAR_NAME 				= "SESSION_VAR_CLIENT_NAME" ;
	const SESSION_VAR_ACTIVE_TIME 		= "SESSION_VAR_CLIENT_ACTIVE_TIME" ;
	const SESSION_VAR_ACTIVE 			= "SESSION_VAR_CLIENT_ACTIVE" ;
	const SESSION_VAR_TOKEN 			= "SESSION_VAR_CLIENT_TOKEN" ;
	
	public static $LOGIN_TIME_LIMIT		= 10000 ;
	protected static $userInfo;
	public static function toString(){
		$br = "
";
		$temp = get_called_class().$br;
		$temp .= "getActive: ".self::getActive().$br;
		$temp .= "getActiveTime: ".self::getActiveTime().$br;
		$temp .= "getId: ".self::getId().$br;
		$temp .= "getName: ".self::getName().$br;
		$temp .= "getToken: ".self::getToken().$br;
		$temp .= "getTypeId: ".self::getTypeId().$br;
		$temp .= "isDeveloper: ".self::isDeveloper().$br;
		return $temp;
	}
	
	//SETS
	public static function setTypeId($value){
		ARMSession::setVar( self::SESSION_VAR_TYPE_ID , $value );
	}
	public static function setTypeAlias($value){
		ARMSession::setVar( self::SESSION_VAR_TYPE_ALIAS , $value );
	}
	public static function setTypeInterface($value){
		ARMSession::setVar( self::SESSION_VAR_TYPE_INTERFACE , $value );
	}
	
	public static function setId($value){
		ARMSession::setVar( self::SESSION_VAR_ID , $value );
	}
	public static function setName($value){
		ARMSession::setVar( self::SESSION_VAR_NAME , $value );
	}
	public static function setToken($value){
		ARMSession::setVar( self::SESSION_VAR_TOKEN , $value );
	}
	public static function setActiveTime($value){
		ARMSession::setVar( self::SESSION_VAR_ACTIVE_TIME , $value );
	}
	public static function setActive($value){
		ARMSession::setVar( self::SESSION_VAR_ACTIVE , $value );
	}
	//GET
	public static function getTypeId(){
		return ARMSession::getVar( self::SESSION_VAR_TYPE_ID ) ;
	}
	public static function getTypeAlias(){
		return ARMSession::getVar( self::SESSION_VAR_TYPE_ALIAS ) ;
	}
	public static function getTypeInterface(){
		return ARMSession::getVar( self::SESSION_VAR_TYPE_INTERFACE ) ;
	}
	public static function getId(){
		return ARMSession::getVar( self::SESSION_VAR_ID ) ;
	}
	public static function getName(){
		return ARMSession::getVar( self::SESSION_VAR_NAME ) ;
	}
	public static function getToken(){
		return ARMSession::getVar( self::SESSION_VAR_TOKEN ) ;
	}
	public static function getActiveTime(){
		return ARMSession::getVar( self::SESSION_VAR_ACTIVE_TIME ) ;
	}
	public static function getActive(){
		return ARMSession::getVar( self::SESSION_VAR_ACTIVE ) ;
	}
	/**
	 * limpa o usuario da session
	 */
	public static function kill(){

		self::setActive(NULL);
		self::setActiveTime(NULL);
		self::setId(NULL);
		self::setName(NULL);
		self::setToken(NULL);
		self::setTypeId(NULL);
		self::setIsAdmin(NULL);
		self::setIsDeveloper(NULL);
		self::setActiveTime(NULL);

		unset( $_SESSION[self::SESSION_VAR_ACTIVE_TIME] );
	}
	/**
	 * renova a sessão
	 * @return void
	 */
	public static function keepAlive(){
		self::setActiveTime(time());
	}
	/**
	 * se estiver logado retorna true e atualiza o active time
	 * @return bool
	 */
	public static function isAlive(){
		$activeTime = self::getActiveTime() ;
		if( $activeTime && $activeTime + self::$LOGIN_TIME_LIMIT > time()){
			self::keepAlive();
			return TRUE ;
		}
		
		return FALSE ;
	}
}