<?php

/**
 * 
 * @author alanlucian
 *
 */
class ARMMysqliConfigVO extends ARMAutoParseAbstract implements ARMDbConfigInterface {
	
	public  $host ;
	public  $link ;
	public  $server ;
	public  $user ;
	public  $password ;
	public  $database ;
	
	public  $name ;
	public  $alias ;
	public  $driver = ARMDBManager::DRIVER_MYSQLI ;
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getAlias()
	 */
	function getAlias(){
		return $this->alias;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getDriver()
	 */
	function getDriver(){
		return $this->driver;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getLink()
	 */
	function getLink(){
		return $this->link;
	}
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setLink()
	 */
	function setLink( $link ){
		$this->link = $link ;
	}
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getHost()
	 */
	function getHost(){
		return $this->host;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getUser()
	 */
	function getUser(){
		return $this->user;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getPassword()
	 */
	function getPassword(){
		return $this->password;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::getDBName()
	 */
	function getDBName(){
		return $this->database;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setAlias()
	 */
	function setAlias( $alias ){
		$this->alias = $alias ;
	}
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setDriver()
	 */
	function setDriver( $driver ){
		$this->driver = $driver;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setHost()
	 */
	function setHost( $host ){
		$this->host = $host;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setUser()
	 */
	function setUser( $user ){
		$this->user = $user ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setPassword()
	 */
	function setPassword( $pass ){
		$this->password = $pass ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDbConfigInterface::setDBName()
	 */
	function setDBName( $dbName ){
		$this->database = $dbName ;
	}
}