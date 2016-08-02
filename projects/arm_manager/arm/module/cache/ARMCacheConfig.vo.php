<?php
/**
 * User: alanlucian
 * Date: 2/5/14
 * Time: 2:37 PM
 */

class ARMCacheConfigVO {

	/**
	 * @var string
	 */
	public $cache_folder = "cache" ;


	/**
	 * if the expiration rule is valid for all caches
	 * @var bool
	 */
	public $general_expiration_rule = TRUE ;


	/**
		y	Number of years.
		m	Number of months.
		d	Number of days.
		h	Number of hours.
		i	Number of minutes.
		s	Number of seconds.

	 * IF NULL  the cache will never expirate
	 *
	 * @see DateInterval >> http://www.php.net/manual/en/class.dateinterval.php
	 * @var string||NULL
	 *
	 */
	public $cache_duration = NULL ;

}