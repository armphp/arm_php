<?php
	/**
	 * 
	 * @author renatomiawaki
	 *
	 */
	interface ARMRequestAccessControllInterface {
		/**
		 * 
		 * @param string $className
		 * @param string $methodName
		 * @param string $requestType
		 * 
		 * @return bool
		 * 
		 */
		function hasAccess( $className, $methodName, $requestType ) ;

		/**
		 * 
		 * @return ARMHttpRequestDataVO
		 */
		function requestAccessResult();
	}
