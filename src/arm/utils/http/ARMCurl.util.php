<?php

class ARMCurl {
	
	/**
	 * The cURL resource
	 * @var cURL 
	 */
	protected $cURLResource ;

	
	/**
	 * An valid URL to do the request
	 * @var string
	 */
	protected $urlRequest 		= "" ;
	
	
	/**
	 * Array of post parameters
	 * @var array
	 */
	protected $postData ;
	
	
	/**
	 * Array of get parameters 
	 * @var array
	 */
	protected $getData ;
	
	/**
	 * 
	 * @param string $url_request
	 * @param boolean $autoInit
	 */
	public function __construct(  $url_request = "" ,  $autoInit = TRUE ) {
		
		$this->setUrl( $url_request ) ;
		
		if( $autoInit )
			$this->init() ;
		
	}

	/**
	 * sets the URL request
	 * @param string $url
	 */
	public function setUrl( $url ) {
		ARMValidation::validateUrl( $url , TRUE ) ;
		$this->urlRequest = $url ;
	}  

	
	/**
	 * Executes the current cURL request
	 * @param boolean $close to close a curl session after execution
	 */
	public function exec( $close = TRUE ) {
		
		if( !isset( $this->cURLResource ) ) 
			$this->init() ;
		
		curl_setopt( $this->cURLResource , CURLOPT_RETURNTRANSFER, TRUE );
			
		//verifies post data
		if( isset( $this->postData ) ) {
			curl_setopt( $this->cURLResource , CURLOPT_POST , true ) ;
			curl_setopt( $this->cURLResource , CURLOPT_POSTFIELDS , $this->postData ) ;
		}
		
		if( isset( $this->getData )  ) {
			$this->urlRequest = ARMDataHandler::urlAddGetVars( $this->urlRequest, $this->getData ) ;
			$this->setOPT( CURLOPT_URL , $this->urlRequest ) ;
		}
		
		$curlResult = curl_exec( $this->cURLResource ) ;
		
		if( $close )
			curl_close( $this->cURLResource );

		return $curlResult ;
	}
	
	/**
	 * Add an POST var
	 * @param string $key
	 * @param string $value
	 */
	public function addPostParameter( $key , $value ) {
		if( !isset( $this->postData ) )
			$this->postData = array();
		
		$this->postData[ $key ] = $value ;
	}
	
	/**
	 * SET the POST vars
	 * @param array $postData
	 */
	public function setPostParameters(  $arrayData ){
		ARMValidation::isArray( $arrayData, TRUE ) ;
		$this->postData = $arrayData;
	}
	
	/**
	 * Add an GET var
	 * @param string $key
	 * @param string $value
	 */
	public function addGetParameter( $key , $value ) {
		if( !isset( $this->getData ) )
			$this->getData = array();
		
		$this->getData[ $key ] = $value ;
	}
	
	
	/**
	 * SET the GET vars
	 * @param array $postData
	 */
	public function setGetParameters(  $arrayData ){
		ARMValidation::isArray( $arrayData, TRUE ) ;
		$this->getData = $arrayData;
	}
	
	/**
	 * This method is an gateway to manually ( and manly ) set an cURL option.
	 * @see curl_setopt
	 * @param int $CURLOPT
	 * @param unknown $value
	 */
	public function setOPT( $CURLOPT, $value ){
		ARMValidation::isNumber( $CURLOPT , TRUE );
		curl_setopt( $this->cURLResource , $CURLOPT, $value );
	}
	
	/**
	 * forces the curl_init
	 */
	protected function init(){
		$this->cURLResource = curl_init( $this->urlRequest );
//		curl_setopt( $this->cURLResource, CURLOPT_FOLLOWLOCATION, TRUE);
		
	}
}