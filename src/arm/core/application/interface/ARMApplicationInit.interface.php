<?php

/** 
 * @author alanlucian
 * RootController must implement this interface, this function is called From ARMHttpRequestController->callInit();
 * @see ARMHttpRequestController 
 * 
 * @TODO: FIX para o class hunter encontrar multiplos implements de uma mesma classe
 * 
 */
interface ARMApplicationInitInterface {
	
	/**
	 * Application start Settings
	 */
	static function applicationInit();
	
}
