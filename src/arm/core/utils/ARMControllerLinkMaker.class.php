<?php
/**
 * User: alanlucian
 * Date: 1/28/14
 * Time: 3:21 PM
 * Classe para gerar uma url de acesso a uma controller com ou sem método
 * podendo adicionar parâmetros
 */

class ARMControllerLinkMaker {


	protected $link ;

	protected $full_url = TRUE ;

	function __construct( $controller, $method = ""  ) {
		// pega o nome da classe, pode ser uma String ou instância
		$class_name = ARMClassHandler::getClassName($controller);

		// caminho do arquivo para gerar o link correspondente
		$class_file_path = ARMDataHandler::removeDoubleBars( dirname( ARMClassHunter::getClassFilePath( $class_name ) ) . "/" );

		$this->link = str_replace( ARMConfig::getDefaultInstance()->getFolderRequestController() , "",	 $class_file_path );

		// junta tudo
		$this->link.= "/". ARMDataHandler::classNameToUrlFolderName( $class_name );
		$this->link.= "/" .  ARMDataHandler::classNameToUrlFolderName( $method );

	}

	/**
	 * @param $name
	 * @param string $value
	 * @param string $glue
	 * @return $this
	 */
	public function addParam( $name , $value = "" , $glue = "."){
		$this->link.= "/" . $name . $glue . $value ;
		return $this ;
	}

	/**
	 * @param $array_params
	 * @param string $glue
	 * @return $this
	 */
	public function addParams( $array_params, $glue = "." ){

		ARMValidation::isArray( $array_params , TRUE );

		foreach( $array_params as $name=> $value){
			$this->addParam( $name, $value , $glue ) ;
		}
		return $this ;
	}


	/**
	 * Set the usage to only return the controller URL not the entire link
	 * @return $this
	 */
	public function simpleLink(){
		$this->full_url  = FALSE ;
		return $this ;
	}

	function __toString() {
		$this->link  = ARMDataHandler::removeDoubleBars( $this->link  );
		if( $this->full_url ){
			$this->link  = ARMConfig::getDefaultInstance()->getAppUrl(  $this->link  ) ;
		}
		return $this->link  ;
	}


}