<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change ARMFileEntity  class
*
* ARMBaseARMFileEntity 
* @date 07/01/2014 06:01:52
*/

abstract class ARMBaseARMFileEntityAbstract extends ARMBaseEntityAbstract{
	
	
	protected function startVO(){
		if(!$this->VO){
			$this->VO = new ARMFileVO();
		}
	}
	/**
	 * 
	 * @param string $alias
	 * @return ARMFileDAO
	 */
	protected function getDAO( $alias = "" ){
		return ARMFileModelGateway::getInstance()->getDAO( $alias ) ;
	}
	/**
	 * @return ARMFileVO
	 */
	public function getVO(){
		return parent::getVO();
	}
}