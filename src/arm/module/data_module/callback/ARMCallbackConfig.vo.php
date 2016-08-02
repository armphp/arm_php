<?php
/**
 * 
 * User: renatomiawaki
 * Date: 2/14/14
 * 
 */

class ARMCallbackConfigVO extends ARMAutoParseAbstract{
	/**
	 * @var ARMCallbackVO[]
	 */
	public $fixedCallback ;

	public function parseObject($object)
	{
		if( isset( $object->fixedCallback ) && is_array( $object->fixedCallback ) ){
			$object->fixedCallback = $this->fetchCallbacks( $object->fixedCallback ) ;
		}
		return parent::parseObject( $object ) ;
	}

	/**
	 * Faz o parse dos objetos já tipando como ARMCallbackVO
	 * @param $itens
	 * @return array
	 */
	private function fetchCallbacks( $itens ){
		$return = array() ;
		foreach( $itens as $item ){
			/* @var $item ARMCallbackVO */
			$callback = new ARMCallbackVO() ;
			$callback->parseObject( $item ) ;
			$return[] = $callback ;
		}
		return $return ;
	}
}