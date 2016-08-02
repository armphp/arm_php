<?php

/**
 * (pt_br) Retangulo de uma área no mapa do mundo
 * 
 * 
 * @author renatomiawaki
 *
 */
class ARMLocationRectangleArea{
	/**
	 * (pt_br)
	 * Ponto esquerdo inferior de um retangulo
	 * Sudoeste
	 * (en) South West
	 * @var ARMLocation
	 */
	public $minLocation;
	/**
	 * (pt_br)
	 * Ponto direito superior
	 * Noroeste 
	 * (en) North East
	 * @var ARMLocation
	 */
	public $maxLocation;
	
	public function __construct( ARMLocation $min = NULL , ARMLocation $max = NULL ){
		$this->maxLocation = $max ;
		$this->minLocation = $min ;
	}
	
	/**
	 * 
	 * @param ARMLocation $location
	 * @return boolean
	 */
	public function checkPointInArea( ARMLocation $location ){
		return ARMLocation::checkLocationInArea( $location, $this ) ;
	}
}