<?php


/**
 * An simple Dictionary system for data persistence
 * @author alanlucian
 *
 */
class ARMDictionary {
	
	/**
	 * Array with data colection
	 * @var array
	 */

	protected  $data = array();
	
	/**
	 * Accept any valid array Key as $key parameter
	 * @see http://www.php.net/manual/en/language.types.array.php
	 * @param mixed $key   index to store the $value
	 * @param mixed $value
	 */
	public function add( $key , $value ) {
		//@TODO: add deveria ver que já existe e transformar em array???
		$this->data[ $key ] = $value ;
	}

	/**
	 * Troca o valor
	 * @param $key
	 * @param $value
	 */
	public function set( $key , $value ) {
		$this->data[ $key ] = $value ;
	}
	/**
	 * Get saved data with key index or NULL
	 * @param mixed $key
	 * @return multitype:mixed|NULL
	 */
	public function get( $key ) {
		if(isset( $this->data[ $key ] ) )
			return $this->data[ $key ] ;
		
		return NULL ;
	}

	/**
	 * @return array
	 */
	public function getData(){
		return $this->data ;
	}

	/**
	 * @param $data object
	 */
	public function mergeData( $data , $defaultIsCurrent = TRUE ){
		if( $data && is_array( $data ) ){
			foreach( $data as $key => $value ){
				if( $defaultIsCurrent ){
					$localData = $this->get( $key ) ;
					if( $localData !== NULL ){
						//se o current é o default e exite um valor pra ele, entao next
						continue ;
					}
				}
				$this->set( $key , $value ) ;
			}
		}
	}
}