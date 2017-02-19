<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change ArmLogEntity  class
*
* ARMBaseArmLogEntity 
* @date 11/12/2013 07:12:18
*/

abstract class ARMBaseArmLogEntityAbstract extends ARMBaseEntityAbstract{
	
	
	/**
	* Converte automático para o formato YYYY/MM/DD
	* @param string $date
	*/
	public function setDateIn($date){
		$this->getVO();
		$this->VO->date_in = ARMDataHandler::convertDateToDB($date);
	}
	/**
	* Converte automático para o formato definido no locale do config do projeto
	* @return string 
	*/
	public function getDateIn(){
		if(!$this->VO){
			return NULL ;
		}
		return ARMDataHandler::convertDbDateToLocale( ARMTranslator::getCurrentLocale(), $this->VO->date_in ) ;
	}
	protected function startVO(){
		if(!$this->VO){
			$this->VO = new ArmLogVO();
		}
	}
	/**
	 * 
	 * @param string $alias
	 * @return ArmLogDAO
	 */
	protected function getDAO( $alias = "" ){
		return ArmLogModelGateway::getInstance()->getDAO( $alias ) ;
	}
	/**
	 * @return ArmLogVO
	 */
	public function getVO(){
		return parent::getVO();
	}
}