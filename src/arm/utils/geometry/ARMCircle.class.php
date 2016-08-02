<?php

/**
 * Classe simbolizando um circulo
 * @author renatomiawaki
 *
 */
class ARMCircle{
	/**
	 *
	 * @var ARMPoint
	 */
	public $point ;
	
	public $ray ;
	
	public function __construct( ARMPoint $point , $ray ){
		$this->ray 		= $ray ;
		$this->point 	= $point;
	}
	
}