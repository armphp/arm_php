<?php
class ARMValidation{
	/**
	 * 
	 * @param string $getItem
	 * @return boolean
	 */
	public static function validateGetParameterItem( $getItem ){
		$deny_strings = array( "&" , "#", "" );
		if( strpos( "&" , $getItem ) >= 0 ){
			return FALSE ;
		}
		return TRUE ;
	}
	/**
	 * @param $valor string or number
	 * @return boolean
	 */
	static function isNumber( $value , $throwErrorException = FALSE ){
		$isValid = is_numeric( $value ) ;
		if( $throwErrorException && !$isValid )
			throw new ErrorException("Invalid number:" . $value ) ;
		
		return $isValid ;
	}

	/**
	 * @param $valor string to be validated
	 * @return boolean
	 */
	static function isString( $value  , $throwErrorException = FALSE ){
		$isValid = is_string( $value ) ;
		if( $throwErrorException && !$isValid )
			throw new ErrorException("Invalid string:" . $value ) ;
		
		return $isValid ;
	}
	
	
	/**
	 * @param $valor array
	 * @return boolean
	 */
	static function isArray( $value , $throwErrorException = FALSE ){
		$isValid = is_array( $value ) ;
		if( $throwErrorException && !$isValid )
			throw new ErrorException("Invalid array:" . $value ) ;
	
		return $isValid ;
	}
	
	/**
	 * @param $email
	 * @return boolean
	 */
	static function validateEmail( $email ){
		return ( filter_var( $email , FILTER_VALIDATE_EMAIL ) !== FALSE ) ;
	}


	/**
	 * Verifies if is an valid date using strtotime
	 * @see http://php.net/manual/pt_BR/function.strtotime.php
	 * @param $data
	 * @return bool
	 */
	static function isDate($data){
		//ARMDebug::dump( $data , strtotime( ARMDataHandler::convertDateToDB( $data ) ) , strtotime($data) ) ;
		return ( strtotime( ARMDataHandler::convertDateToDB( $data ) ) );
	}

	/**
	 * @param $data
	 * @return array ou false
	 */
	static function validateDate($data, ARMDataMaskInterface $mask = NULL ){
		$arrayData = array();

		if( $mask ){

			$date= DateTime::createFromFormat(  $mask->getDate(), $data);

			if( !$date )
				return FALSE ;

			// When DateTime::createFromFormat create an date it changes days and month to fit a valid but not the same date
			// we must verify if the date is the same
			if(  $data != $date->format($mask->getDate()) )
				return FALSE ;

			// just a recheck
			return  checkdate( $date->format("m") ,$date->format("d"),$date->format("Y") ) ;
		}

		if(preg_match("|([0-3]?[0-9])[/\\-]([0-1]?[0-9])[/\\-]([0-9]?[0-9]?[0-9][0-9])|", $data, $arrayData)){
			return $arrayData;
		}
		return false;
	}
	
	/**
	 * 
	 * @param string $url
	 * @param boolean $throwErrorException
	 * @throws ErrorException
	 * @return boolean
	 */
	static function validateUrl( $value, $throwErrorException = FALSE ){
		$isValid = ( filter_var( $value , FILTER_VALIDATE_URL ) !== FALSE );
		if( $throwErrorException && !$isValid )
			throw new ErrorException("Invalid URL:" . $value ) ;
		
		return $isValid ;
	}
	/**
	 * @param $texto
	 * @return boolean
	 */
	static function blank($text, $limit = 1){
		return (strlen( trim( $text ) ) >= $limit);// se tiver mais de 1 caractere ta ok
	}
}