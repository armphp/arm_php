<?php

/** 
 * @author alanlucian
 * RootController must implement this interface, this function is called From ARMHttpRequestController->callInit();
 * @see ARMHttpRequestController 
 *
 */
interface ARMApplicationInitInterface {
	
	/**
	 * Application start Settings
	 */
	static function applicationInit();
	
}
