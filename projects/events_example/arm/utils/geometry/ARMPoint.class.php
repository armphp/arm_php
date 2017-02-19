<?php
/**
 * Classe que simboliza um ponto 2D
 * 
 * @author renatomiawaki
 *
 */
class ARMPoint{
	/**
	 * 
	 * @var number
	 */
	public $x ;
	/**
	 *
	 * @var number
	 */
	public $y ;
	
	public function __construct( $x, $y ){
		$this->x = $x * 1 ;
		$this->y = $y * 1 ;
	}
}