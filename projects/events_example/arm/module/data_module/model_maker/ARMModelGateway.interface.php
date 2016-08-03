<?php

interface ARMModelGatewayInterface extends ARMSingletonInterface{
	/**
	 * 
	 * @return ARMBaseEntityAbstract
	 */
	function getEntity();
	
	/**
	 * @return ARMAutoParseAbstract
	 */
	function getVO();
	/**
	 * @return  ARMBaseDAOAbstract
	 */
	function getDAO();
	
}