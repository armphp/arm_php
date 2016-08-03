<?php

interface ARMSingletonInterface {
	/**
	 * para pegar uma instancia do modulo baseado no alias de instalação
	 * @param string $alias
	 */
	static function getInstance( $alias = "" ) ;
	
}