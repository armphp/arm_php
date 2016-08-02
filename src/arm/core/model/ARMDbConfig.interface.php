<?php

/**
 * Interface for an DB connection config file
 * @author alanlucian
 *
 */

interface ARMDbConfigInterface extends ARMManagerItemInterface{
	
	
	/**
	 * @return string
	 */
	function getDriver();
	
	/**
	 * @return mysqli
	 */
	function getLink();
	
	/**
	 * @return string
	 */
	function getHost();
	
	/**
	 * @return string
	 */
	function getUser();
	
	/**
	 * @return string
	 */
	function getPassword();
	
	/**
	 * @return string
	 */
	function getDBName();

	
	/**
	 * 
	 * @param mysqli $link
	 */
	function setLink( $link );
	
	/**
	 * 
	 * @param string $alias
	 */
	function setAlias( $alias );

	/**
	 * 
	 * @param string $driver
	 */
	function setDriver( $driver );
	
	/**
	 * 
	 * @param string $host
	 */
	function setHost( $host );
	
	/**
	 * 
	 * @param string $user
	 */
	function setUser( $user );
	
	/**
	 * 
	 * @param string $password
	 */
	function setPassword( $password );
	
	/**
	 * 
	 * @param string $database
	 */
	function setDBName( $database );
	
}