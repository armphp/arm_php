<?php


class Dev  {
	protected $restFolder ;
	public function __construct(){
		 $this->restFolder = ARMNavigation::getURI( ARMConfig::getDefaultInstance()->getAppUrl() ) ;
	}
	public function init() {
		//verificar em que página está
		$return = new ContentMenuLeft() ;
		$return->appURL 		= ARMNavigation::getURL() ;
		$return->selected		= ( isset( $this->restFolder[0] ) ) ? $this->restFolder[0] : "" ;
		return $return ;
	}
	public function menuLeft(){
		//escrevi aqui apenas para ilustrar o que já iria acontecer se não tivesse o metodo
		return $this->init() ;
	}
	public function topSearch(){
		//escrevi aqui apenas para ilustrar o que já iria acontecer se não tivesse o metodo
		return $this->init() ;
	}
}