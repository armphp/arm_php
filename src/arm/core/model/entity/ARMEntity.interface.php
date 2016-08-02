<?php

Interface ARMEntityInterface {
	
	/**
	 * 
	 * @param boolean $validate
	 * @return ARMReturnResultVO
	 */
	function commit( $validate = FALSE ) ;
	
	function setId(  $id , $autoSearch = FALSE ) ;
	
	function validate() ;
	
	function fetchArray($array, $prefix = "");
	
	function fetchObject($obj);
	
	function resultHandler( ARMReturnDataVO $ReturnDataVO );
	
	/**
	 * @return VOobject
	 */
	function getVO() ;
	
}