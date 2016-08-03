<?php

/**
 * Atention! This interface is not complete !
 * - if you find out something missing, please, don't be ashamed upgrade this
 * This interface tells all switchable data between systems (eg: databases, countries and even other planets );
 * @author alanlucian
 *
 */
interface ARMDataMaskInterface {
	
	/**
	 * return a date format based on php date() function 
	 * @return string
	 */
	function getDate();
	
	/**
	 * return a time format based on php date() function
	 * @return string
	 */
	function getTime();
	
	/**
	 * return a date and time format based on php date() function
	 * @return string
	 */
	function getDateTime();
	
	/**
	 * return the currency number_format data
	 * @return ARMDataMaskNumberFormat
	 */
	function getCurrencyNumberFormat();
	
	/**
	 * return the currency validation regular expression pattern
	 * @see preg_match
	 * @return string
	 */
	function getCurrencyValidation();
	
	/**
	 * return the currency symbol used on a sprintf function
	 * @see sprintf
	 * @return string
	 */
	function getCurrencySymbolTemplate();
	
}