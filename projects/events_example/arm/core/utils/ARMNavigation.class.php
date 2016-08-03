<?php
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @description	: 	Agora a classe � est�tica
	 * 					Modo de uso:
	 					//de qualquer lugar do c�digo em que a classe j� tenha sido importada:
	 					$variavel = ARMNavigation::get("teste");
	 */
	/**
	 * @author		: Renato Miawaki
	 * @data		: 05/11/2013
	 * @version		: 1.3
	 * @description	: 	Agora a arrayVariable fica guardado na ARMNavigation de forma estática
	 * 					e a ARMNavigation::getVar($variavel); retorna também o que esta no arrayVariable \
	 * 					que é resultado do arrayRestFolder, que agora também faz parte do ARMNavigation
	 */
class ARMNavigation{
	const REDIRECT_VAR	= "SESSION_REDIRECT_VAR";
	const URI_RETURN_TYPE_STRING	= "URI_RETURN_TYPE_STRING";
	const URI_RETURN_TYPE_ARRAY		= "URI_RETURN_TYPE_ARRAY";

	/**
	 * para guardar informações sobre a regra de parseamento entre nome de url e nomenclatura de Classe
	 * utilize getArrayRenameRules()
	 * @var array
	 */
	private static $arrayRenameRules;
	private static $flipedArrayRenameRules;

	static function get($variable){
		//se tiver vazio ou nao estivar setado, retorna null
		return (isset($_GET[$variable]) && $_GET[$variable] != "")?$_GET[$variable]:NULL;
	}
	static function post($variable){
		return (isset($_POST[$variable]) && $_POST[$variable] != "")?$_POST[$variable]:NULL;
	}
	public static $arrayRequest;

	public static $arrayRestFolder;

	public static $arrayVariable;

	/**
	 * @var ARMReturnSearchClassVO
	 */
	public static $controllerInfo;

	/**
	 * para gerar uma url de acesso a uma controller com ou sem método
	 * podendo adicionar parâmetros
	 * ex: uso  action de formulario
	 * @param strin|object $controller
	 * @param string $method
	 * @return ARMControllerLinkMaker
	 */
	public static function linkToController( $controller, $method = "" ){
		return new ARMControllerLinkMaker( $controller, $method  ) ;
	}

	/**
	 * Retorna a url atual
	 * @param string $urlToAppend
	 * @return string
	 */
	public static function getCurrentURL( $urlToAppend = "" ){
		$link = "http://".$_SERVER[ "HTTP_HOST" ]  ;
		$request = $_SERVER[ "REQUEST_URI" ] ;
		if( $urlToAppend ){
			$request = ARMDataHandler::removeDoubleBars( $request ."/".$urlToAppend) ;
		}
		return $link . $request;
	}
	/**
	 * para gerar uma url de acesso a uma controller com ou sem método
	 * ex: uso  action de formulario
	 * @param strin|object $controller
	 * @param string $method
	 * @return string
	 */
	public static function getLinkToController( $controller, $method = ""  ,$complete = TRUE ){

		$controller_access_url = new ARMControllerLinkMaker( $controller, $method  ) ;

		if( ! $complete ){

			$controller_access_url->simpleLink() ;
		}
		return   $controller_access_url ;
	}

	/**
	 * Retorna a array de todas as pastas da url chamada, desconsiderado a controller
	 * @return array
	 */
	public static function getCompleteArrayFolder( $removeAppUrl = TRUE ){

		return self::$arrayRequest;

		//DEPRECATED

		$returnArray = array() ;
		//pega a pasta que resolveu a requisição
		$foldersToController = self::$urlResolvedController ;

		if( $removeAppUrl ){
			$foldersToController = str_replace( ARMConfig::getDefaultInstance()->getAppUrl(), "", self::$urlResolvedController ) ;
		}
		if( $foldersToController ){
			$returnArray 	= explode("/" , $foldersToController ) ;
		}
		return ARMDataHandler::appendArray( $returnArray , self::$arrayRestFolder ) ;
	}
	/**
	 * Url até onde foi utilizado para resolver a controller (sem o metodo)
	 * @var string
	 */
	public static $urlResolvedController ;
	public static function getCurrentControllerURL( $relative = "" ){
		return ARMDataHandler::removeLastBar( self::$urlResolvedController ) . ARMDataHandler::removeDoubleBars( "/".$relative ) ;
	}
	/**
	 * atalho para getAppUrl do config
	 *
	 * @param string $relative_url
	 * @param string $raw (retorna a url pura configurada lá no config )
	 * @return string
	 */
	public static function getAppUrl($relative_url = '', $raw = FALSE ){
		return ARMConfig::getDefaultInstance()->getAppUrl( $relative_url, $raw ) ;
	}

	/**
	 * @param $variable
	 * @param $defaultValue (valor que ele retorna caso não encontre a variavel setada)
	 * @return mixed
	 */
	static function getVar( $variable , $defaultValue = NULL){
		//primeiro verifica na variable da arrayVariable local
		if(isset(self::$arrayVariable[$variable])){
			return self::$arrayVariable[$variable];
		}
		if(ARMNavigation::post($variable) != NULL){
			return ARMNavigation::post($variable);
		} else if(ARMNavigation::get($variable) != NULL){
			return ARMNavigation::get($variable);
		} else {
			return $defaultValue ;
		}
	}
	/**
	 * retorna uma array relacional para troca de caracteres e formação de nome entre url e Classes
	 * @return array
	 */
	static function getArrayRenameRules(){
		// @UPGRADE! - verifiar se é mais rápido usar isso dessa maneira ou numa array em alguma classe já escrita
		if(!self::$arrayRenameRules){
			$arrayChanges = array();
			$arrayChanges["_a"] = "A";
			$arrayChanges["_b"] = "B";
			$arrayChanges["_c"] = "C";
			$arrayChanges["_d"] = "D";
			$arrayChanges["_e"] = "E";
			$arrayChanges["_f"] = "F";
			$arrayChanges["_g"] = "G";
			$arrayChanges["_h"] = "H";
			$arrayChanges["_i"] = "I";
			$arrayChanges["_j"] = "J";
			$arrayChanges["_k"] = "K";
			$arrayChanges["_l"] = "L";
			$arrayChanges["_m"] = "M";
			$arrayChanges["_n"] = "N";
			$arrayChanges["_o"] = "O";
			$arrayChanges["_p"] = "P";
			$arrayChanges["_q"] = "Q";
			$arrayChanges["_r"] = "R";
			$arrayChanges["_s"] = "S";
			$arrayChanges["_t"] = "T";
			$arrayChanges["_u"] = "U";
			$arrayChanges["_v"] = "V";
			$arrayChanges["_x"] = "X";
			$arrayChanges["_y"] = "Y";
			$arrayChanges["_w"] = "W";
			$arrayChanges["_z"] = "Z";
			$arrayChanges["_1"] = "1";
			$arrayChanges["_2"] = "2";
			$arrayChanges["_3"] = "3";
			$arrayChanges["_4"] = "4";
			$arrayChanges["_5"] = "5";
			$arrayChanges["_6"] = "6";
			$arrayChanges["_7"] = "7";
			$arrayChanges["_8"] = "8";
			$arrayChanges["_9"] = "9";
			$arrayChanges["_0"] = "0";
			self::$arrayRenameRules = $arrayChanges;
		}
		return self::$arrayRenameRules;
	}

	static function getFlipedArrayRenameRules() {
		if(!self::$flipedArrayRenameRules){
			self::$flipedArrayRenameRules = array_flip( ARMNavigation::getArrayRenameRules() );
		}
		return self::$flipedArrayRenameRules;
	}


	/*
	 * método p/ facilitar o desenvolvimento de controllers
	 */
	static function getURL( $destination = "" ){
		//
		$site_url = ARMConfig::getDefaultInstance()->getAppUrl( $destination ) ;
		return  $site_url ;
	}
	/**
	 *
	 * @var RewriteRuleInterface
	 */
	protected static $rewrite ;
	public static function setRewriteHandler( ARMRewriteRuleInterface $rewrite ){
		self::$rewrite = $rewrite ;
	}
	/**
	 * Pega a url e caso exista regra de rewrite iniciada, aplica a regra
	 * @param string $url
	 * @return string
	 */
	protected static function rewrite( $url ){
		if( self::$rewrite  ){
			return self::$rewrite->rewrite( $url ) ;
		}
		return $url;
	}
	/**
	 * Retorna a string do nome do dominio
	 * @return string
	 */
	static function getURIDomain(){
		return $_SERVER["HTTP_HOST"];
	}
	/**
	 * @param string 	$siteName
	 * @param string 	$ReturnType
	 * @param string 	$maxRange
	 * @param int 		$initRange
	 * @param bool 		$byVariable // envie o nome da variavel quando for para pegar valor de variavel de navegação
	 * @return array or string
	 */
	static function getURI($siteName = "", $ReturnType = ARMNavigation::URI_RETURN_TYPE_ARRAY, $maxRange = NULL, $initRange = 0, $byVariable = NULL){
		ARMDebug::ifPrint($siteName, "debug_navigation") ;
		// @UPGRADE!
		$siteName = str_replace(array("http://www", "https://www", "http://", "https://", "//"), "", $siteName);
		ARMDebug::ifPrint($siteName, "debug_navigation") ;
		$siteName = str_replace( $_SERVER["SERVER_NAME"] , "", $siteName);
		ARMDebug::ifPrint($siteName, "debug_navigation") ;
		//sa o ultimo caracter for /, tira
		$siteName = ARMDataHandler::removeLastBar($siteName);
		$siteName = ARMDataHandler::removeFirstChar( $siteName , "/") ;
		ARMDebug::ifPrint($siteName, "debug_navigation") ;
		if($byVariable){
			$url = explode("/", self::rewrite( ARMNavigation::get($byVariable) ));
		} else {
			$request_uri = $_SERVER["REQUEST_URI"] ;
			if( $ReturnType == ARMNavigation::URI_RETURN_TYPE_ARRAY ) {
				$request_uri = explode("?", $request_uri) ;
				$request_uri = $request_uri[0];
			}


			$url = str_ireplace($siteName, "", $request_uri );
			//$url = explode("/", $url);

		}
		//tirando o nome do site só do início
		$url = str_replace("//", "/", $url);

		//transforma a url em array
		$url  = preg_replace("/(^\/)/", '', $url);
		$tempArray = explode("/", $url);

		if($initRange > 0 || $maxRange != NULL){
			$tempTotal = 0;
			if($maxRange != NULL){
			    //echo ARMDebug::li("maxRange $maxRange : initRange $initRange");
				$tempTotal = $maxRange;
			} else {
			    $tempTotal = count($tempArray);
			}
			$tempTotal = $tempTotal + 1;
			if($tempTotal > count($tempArray)){
			    $tempTotal = count($tempArray);
			}
			$tempArray = array_slice($tempArray, $initRange , $tempTotal);
		}

		//filtra a array conforme as regras
		$tempArrayFiltrada = array();
		for($i = 0; $i < count($tempArray); $i++){
			if($tempArray[$i] != ""){
				$tempArrayFiltrada[] = $tempArray[$i];
			}
		}
		unset($tempArray);

		switch($ReturnType){
			case ARMNavigation::URI_RETURN_TYPE_STRING:
				$url = implode("/", $tempArrayFiltrada);
				return $url;
				break;
			case ARMNavigation::URI_RETURN_TYPE_ARRAY:
			default:
				return $tempArrayFiltrada;
				break;
		}
		return $url;
	}

	/**
	 * Retorna objeto com variaveis com seus respectivos valores e array de slug
	 * melhorias: se passado duas variaveis iguais de valores diferentes salvar os valores como array em uma unica variavel
	 * @return string
	 */
	static function getVariableArraySlug($tempArrayRestFolder = NULL, $typeSeparete = "."){
		$arrayVariable = array();
		if(is_array($tempArrayRestFolder) && count($tempArrayRestFolder) > 0){
			foreach($tempArrayRestFolder as $str){
				$explode = explode($typeSeparete, $str);
				//print_r($explode);
				if(count($explode)>1){
					$variable = array_shift($explode);
					$arrayVariable[$variable] = urldecode(implode($typeSeparete, $explode));
				}
				//print_r($arrayVariable);
			}
		}
		//ARMDebug::print_r($arrayVariable);exit();
		return $arrayVariable;
	}

	static function unserializeToRedirect( $redirect_to_recived ){
		// @UPGRADE! não está muito bom
		$redirect_to 	= str_replace("|", "/", $redirect_to_recived);
		$redirect_to = str_replace("[&]", "&", $redirect_to);
		$redirect_to = str_replace("[?]", "?", $redirect_to);
		return $redirect_to;
	}

	static function serializeToRedirect  ( $url ){
		// @UPGRADE!  verificar se pode transportar isso para outra classe, e também deixa-la mais abstrata e configurado
		$url = str_replace(  "/", "|", $url);
		$url = str_replace(  "?", "[?]", $url);
		$url = str_replace(  "&", "[&]", $url);
		return $url;
	}

	static function getRequestToRedirect( $addVar = TRUE ){
		// @UPGRADE!  verificar se pode transportar isso para outra classe, e também deixa-la mais abstrata e configurado
		$add = ( $addVar) ? "redirect_to." : "";
		return  $add . ARMNavigation::serializeToRedirect( self::getRequestUrl() );
	}

    static function redirect( $path = "" , $fullURL = FALSE ){
    	// @UPGRADE! filosofia sobre o uso de config dentro da navigation, e até se navigation não é um módulo, logo, precisa de um config próprio
    	if( !$fullURL ){
    		$path = ARMConfig::getDefaultInstance()->getAppUrl( $path ) ;
    	}
        header("Location:" . $path  );
        exit;
    }
}