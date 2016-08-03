<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change ARMCronEventEntity  class
*
* ARMBaseARMCronEventEntity 
* @date 26/02/2016 04:02:34
*/

abstract class ARMBaseARMCronEventEntityAbstract extends ARMBaseEntityAbstract{
	
	
	/**
	* Converte automático para o formato YYYY/MM/DD
	* @param string $date
	*/
	public function setDateIn($date){
		$this->getVO();
		$this->VO->date_in = ARMDataHandler::convertDateToDB($date);
	}
	/**
	* Converte automático para o formato YYYY/MM/DD
	* @param string $date
	*/
	public function setDateToShow($date){
		$this->getVO();
		$this->VO->date_to_show = ARMDataHandler::convertDateToDB($date);
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
	/**
	* Converte automático para o formato definido no locale do config do projeto
	* @return string 
	*/
	public function getDateToShow(){
		if(!$this->VO){
			return NULL ;
		}
		return ARMDataHandler::convertDbDateToLocale( ARMTranslator::getCurrentLocale(), $this->VO->date_to_show ) ;
	}
	protected function startVO(){
		if(!$this->VO){
			$this->VO = new ARMCronEventVO();
		}
	}
	/**
	 * 
	 * @param string $alias
	 * @return ARMCronEventDAO
	 */
	protected function getDAO( $alias = "" ){
		return ARMCronEventModelGateway::getInstance()->getDAO( $alias ) ;
	}
	/**
	 * @return ARMCronEventVO
	 */
	public function getVO(){
		return parent::getVO();
	}
}