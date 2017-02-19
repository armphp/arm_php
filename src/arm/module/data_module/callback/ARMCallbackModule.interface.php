<?php
/**
 * Toda classe que pretende ser colocada como callback de um evento deve implementar essa interface
 * interface ARMCallbackModuleInterface
 */
interface ARMCallbackModuleInterface {
	
	/**
	 * metodo para uso do callback de uma trigger
	 * @param unknown $data
	 */
	function callback( $data );
}