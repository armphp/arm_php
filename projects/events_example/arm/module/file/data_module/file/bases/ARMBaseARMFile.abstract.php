<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change ARMFileEntity  class
*
* ARMBaseARMFileEntity 
* @date 08/01/2014 03:01:43
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