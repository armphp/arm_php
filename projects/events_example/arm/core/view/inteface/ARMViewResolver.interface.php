<?php

interface ARMViewResolverInterface extends ARMModuleInterface{
	/**
	 * 
	 * @param $result
	 * @param array $arrayPathFolder
	 * 
	 */
	function show( $result, $arrayPathFolder ) ;
}