<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change ARMGenericParameterEntity  class
*
* ARMBaseARMGenericParameterEntity 
* @date 18/03/2014 01:03:21
*/

abstract class ARMBaseARMGenericParameterEntityAbstract extends ARMBaseEntityAbstract{
	
	
	protected function startVO(){
		if(!$this->VO){
			$this->VO = new ARMGenericParameterVO();
		}
	}
	/**
	 * 
	 * @param string $alias
	 * @return ARMGenericParameterDAO
	 */
	protected function getDAO( $alias = "" ){
		return ARMGenericParameterModelGateway::getInstance()->getDAO( $alias ) ;
	}
	/**
	 * @return ARMGenericParameterVO
	 */
	public function getVO(){
		return parent::getVO();
	}
}