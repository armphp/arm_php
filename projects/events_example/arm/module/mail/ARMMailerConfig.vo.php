<?php

/**
 * 
 * @author alanlucian
 *
 */
class ARMMailerConfigVO {

	/**
	 * Sets the From email address for the message.
	 * @var string
	 */
	public $from ;
	
	/**
	 * Sets the From name of the message.
	 * @var string
	 */
	public $from_name ;
	
	
	/**
	 * Email priority (1 = High, 3 = Normal, 5 = low).
	 * @var int
	 */
	public $priority          = 3;
	
	/**
	 * Will use SMTP?
	 * @var boolean
	 */
	public $is_smtp = false;
	

	/**
   * Sets the SMTP hosts.  All hosts must be separated by a
   * semicolon.  You can also specify a different port
   * for each host by using this format: [hostname:port]
   * (e.g. "smtp1.example.com:25;smtp2.example.com").
   * Hosts will be tried in order.
   * @var string
   */
	public $host ;

	/**
	 * HOST port number
	 * @var int
	 */
	public $port = 25 ;

	/**
	 * Sets SMTP authentication. Utilizes the Username and Password variables.
	 * @var boolean
	 */
	public $smtp_auth = TRUE ;

	
	/**
	 * An SMTP connection must have a user
	 * @var unknown
	 */
	public $user_name ;
	
	/**
	 * The user needs a password right?
	 * @var string
	 */
	public $password ;
	
	
   /**
   * Sets connection prefix.
   * Options are "", "ssl" or "tls"
   * @var string
   */
	public $smtp_secure_type = "" ;
	
	
	/**
	 * Sets the SMTP server timeout in seconds.
	 * This function will not work with the win32 version.
	 * @var int
	 */
	public $smtp_timeout       = 10;
	
	
	/**
	 * @var array
	 */
	public $cc_list = array();

	/**
	 * @var array
	 */
	
	public $bcc_list = array();

	public $debug_mode = FALSE ;
}