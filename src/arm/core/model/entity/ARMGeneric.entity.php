<?php
/**
 * Entidade generica para não precisar criar entidades para todas as tabelas
 * @author renatomiawaki
 *
 */
class ARMGenericEntity extends ARMBaseEntityAbstract{
	private $module;

	public function __construct(ARMBaseDataModuleAbstract $module){
		$this->module 	= $module;
	}
	
	public function getDAO(){
		return $this->getModule()->getDAO();
	}
	
	public function getVO(){
		if( !$this->VO ) // || get_class( $this->VO )  == "stdClass" )
			$this->VO = $this->getModule()->getVO();
		
		return $this->VO;
	}
	
	
	protected function startVO(){
		if(!$this->VO){
			$this->VO = $this->getModule()->getVO();
		}
	}
	/**
	 * 
	 * @return ARMBaseDataModuleAbstract
	 */
	protected function getModule(){
		return $this->module;
	}
	/**
	 * Entidade generica não tem relações
	 */
	public function startRelations(){}

}