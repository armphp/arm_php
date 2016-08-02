<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change ArmParameterEntity  class
*
* ARMBaseArmParameterEntity 
* @date 17/01/2014 05:01:07
*/

abstract class ARMBaseArmParameterEntityAbstract extends ARMBaseEntityAbstract{
	
	
	protected function startVO(){
		if(!$this->VO){
			$this->VO = new ArmParameterVO();
		}
	}
	/**
	 * 
	 * @param string $alias
	 * @return ArmParameterDAO
	 */
	protected function getDAO( $alias = "" ){
		return ArmParameterModelGateway::getInstance()->getDAO( $alias ) ;
	}
	/**
	 * @return ArmParameterVO
	 */
	public function getVO(){
		return parent::getVO();
	}
}