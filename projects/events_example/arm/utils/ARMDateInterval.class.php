<?php
/**
 * User: alanlucian
 * Date: 2/5/14
 * Time: 7:41 PM
 *
 *
 *
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

class ARMDateInterval {

	public static function getFromDays( $day ) {
		return sprintf("P%dD",$day) ;
	}

	public static function getFromHours( $hour ) {
		return sprintf("PT%dH",$hour) ;
	}


	public static function getFromMinutes( $minutes ) {
		return sprintf("PT%dI",$minutes) ;
	}

	public static function getFromSeconds( $seconds ) {
		return sprintf("PT%dS",$seconds) ;
	}
}