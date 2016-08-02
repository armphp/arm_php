<?php


/**
 * 
 * Ponto em geolocalização
 * 
 * @author renatomiawaki
 * 
 * ex:
 * 
 * $ARMArea = new ARMLocationRectangleArea();
			
			$ARMArea->minLocation = new ARMLocation(0, 0);
			$ARMArea->maxLocation = new ARMLocation(10, 10);
			
			$ArmLocation = new ARMLocation(5, 5);
			
			$ArmLocation->checkInArea( $ARMArea ) ;
			
			$ARMArea->checkPointInArea( $ArmLocation ) ;
 *
 */
class ARMLocation {
	
	/**
	 *
	 * @var number
	 */
	public $lat;
	/**
	 * 
	 * @var number
	 */
	public $lng;
	
	public function __construct( $lat , $lng ){
		$this->setLat( $lat );
		$this->setLng( $lng );
	}
	public function setLat( $value ){
		$this->lat = ARMDataNumberHandler::forceNumber( $value ) ;
	}
	public function setLng( $value ){
		$this->lng = ARMDataNumberHandler::forceNumber( $value ) ;
	}
	public function checkInArea( ARMLocationRectangleArea $area ){
		return self::checkLocationInArea( $this , $area ) ;
	}
	/**
	 * 
	 * @param ARMLocation $location
	 * @param ARMLocationRectangleArea $area
	 * @return boolean
	 */
	public static function checkLocationInArea( ARMLocation $location, ARMLocationRectangleArea $area ){
		if( 	! is_numeric( $location->lat ) ||  
				! is_numeric( $location->lng ) ||  
				is_null( $area->maxLocation ) ||  
				is_null( $area->minLocation ) ||
				! is_numeric( $area->maxLocation->lat ) ||  
				! is_numeric( $area->maxLocation->lng ) ||
				! is_numeric( $area->minLocation->lat ) ||  
				! is_numeric( $area->minLocation->lng ) 
		){
			throw new ErrorException("Esperando dados númericos não obtidos") ;
		}
		//conferindo área dentro da latitude (linha do equador)
		if( ! self::checkLinearArea( $area, $location, "lat" ) ){
			return FALSE ;
		}
		//conferindo área dentro da longitude (linha de greenwith)
		if( ! self::checkLinearArea( $area, $location, "lng" ) ){
			return FALSE ;
		}
		return TRUE ;
	} 
	private static function checkLinearArea( ARMLocationRectangleArea $area, ARMLocation $location, $property ){
// 		ARMDebug::li("checkLinearArea verificando se o ponto { $property: {$location->$property} }  ");
		$min = $area->minLocation->$property;
		$max = $area->maxLocation->$property;
		$dif 	= ( $area->maxLocation->$property - $area->minLocation->$property );
		$compare =  ( $area->maxLocation->$property > $area->minLocation->$property ) ;
		
		$minInRange 	= ARMMath::isHigher( $location->$property , $min ) ;
		$maxInRange 	= ARMMath::isLower(  $location->$property , $max ) ;
		
		$isIn 			= ( $compare ) ? ( $minInRange &&  $maxInRange) : ( $minInRange ||  $maxInRange) ;
		//se for maior que limitToChange a diferença, ele deu a volta ao mundo pelo circulo polar
		if( $isIn ) {
			return TRUE ;
		}
		return FALSE ;
	}
}