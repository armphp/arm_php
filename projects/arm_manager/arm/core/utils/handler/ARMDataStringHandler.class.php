<?php

/**
 * Utils para tratamento de datas do tipo string ou envolvendo strings
 *
 * @author renatomiawaki
 *
 */
class ARMDataStringHandler extends ARMDataCharHandler {

	public static function slugfy( $string ){
		$string = self::strtolower_utf8( $string );
		$string = self::removeAccent( $string ) ;
		$string = str_replace(" " , "_" , $string );
		$string  = preg_replace("/([^a-z0-9_])/im" , "" , $string);
		return $string ;

	}

	public static function strtolower_utf8($string){
		$convert_to = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
			"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
			"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
			"з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
			"ь", "э", "ю", "я"
		);
		$convert_from = array(
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
			"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
			"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
			"З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
			"Ь", "Э", "Ю", "Я"
		);

		return str_replace($convert_from, $convert_to, $string);
	}

	public static function escapeToEreg( $string , $pattern_delimiter = "/" ){
		$ereg_chars = array(
				".",
				"+",
				"$",
				"^",
				"*",
				"?",
				"(",
				")",
				"[",
				"]",
				"|",
				$pattern_delimiter
		);

		foreach($ereg_chars as $ereg_char  ){
			$string = str_replace( $ereg_char,  "\\" . $ereg_char , $string ) ;
		}

		return $string ;
	}

	public static function convertToEncoding($string, $new_encoding){
		$old_encoding = mb_detect_encoding($string);
		return mb_convert_encoding($string, $old_encoding, $new_encoding);
	}


	static function forceString($valor, $stripTag = FALSE, $scape_string = TRUE){
		if($stripTag){
			$valor = strip_tags($valor);
		}
		//		$valor = nl2br($valor);
		if($scape_string){
			//troca " por html entitis";
			$valor = str_replace("\"", '&#034;', $valor);
			//troca ' por html entitis;
			$valor = str_replace("'", "&#039;", $valor);
			//			$valor = mysql_escape_string($valor);
		}
		return (string) $valor;
	}


	/**
	 * @param $urlFolderName // nome da pasta bÃ¡sica que deve ser ignorada
	 * @return unknown_type
	 */
	public static function urlFolderNameToClassName($urlFolderName){
		$urlFolderName = strtolower($urlFolderName);
		$arrayChanges = ARMNavigation::getArrayRenameRules();
		foreach($arrayChanges as $key=>$value){
			$urlFolderName = str_replace($key, $value, $urlFolderName);
		}
		$urlFolderName = ucfirst($urlFolderName);
		return $urlFolderName;
	}

	/**
	 * @param $urlFolderName // nome da pasta bÃ¡sica que deve ser ignorada
	 * @return unknown_type
	 */
	public static function urlFolderNameToMethodName($urlFolderName){
		$urlFolderName = strtolower($urlFolderName);
		$arrayChanges = ARMNavigation::getArrayRenameRules();
		foreach($arrayChanges as $key=>$value){
			$urlFolderName = str_replace($key, $value, $urlFolderName);
		}
		return $urlFolderName;
	}


	public static function urlAddGetVars(  $url , array $vars ){

		$glue = ( strpos( $url , "?" ) === FALSE ? "?" : "" ) ;

		return $url . $glue . http_build_query( $vars );
	}

	static function classNameToUrlFolderName($className){
		$arrayChanges = ARMNavigation::getArrayRenameRules();
		foreach($arrayChanges as $key=>$value){
			$className = str_replace($value, $key, $className);
		}
		if(strlen($className) > 0 && strpos($className, "_") === 0){
			$className = substr($className, 1, strlen($className)-1);
		}
		return $className;
	}


	/**
	 * @param $urlFolderName // nome da pasta bÃ¡sica que deve ser ignorada
	 * @return unknown_type
	 */
	public static function returnFirstAndLastName($name){
		$name = str_replace("  ", " ", $name);
		$array_nomes = explode(" ", $name);
		if(count($array_nomes) > 1){
			$name = $array_nomes[0]." ".$array_nomes[count($array_nomes)-1];
		}
		return $name;
	}


	/**
	 * pega o ID do video do youtube de uma string
	 * @param $url
	 * @return $vid
	 */
	public static function getYoutubeVideoId($url){
		if($url === null){ return ""; }


		preg_match("/[\\?&]v=([^&#]*)/", $url, $out);

		if(!sizeof($out)>0)
			return '';


		$vid = $out[1];

		return $vid;
	}


	/**
	 * pega imagem do video do youtube
	 * @param $url
	 * @param $size
	 * @return $vid
	 */
	public static function getYoutubeThumb( $url, $size = 'small')	{

		$vid = ARMDataHandler::getYoutubeVideoId($url);

		if($size == "small"){
			$rt = "http://img.youtube.com/vi/" . $vid . "/2.jpg";
		}else {
			$rt ="http://img.youtube.com/vi/" . $vid . "/0.jpg";
		}
		return $rt;
	}


	static function removeDoubleBars($string){
		return str_replace(array("////", "///", "//"), "/", $string);
	}
	/**
	 * @param $string_folder
	 * @return string
	 */
	static function removeLastBar($string_folder){
		//echo ARMDebug::li("string_folder:$string_folder");
		if(strlen($string_folder) > 0 && $string_folder[strlen($string_folder)-1] == "/"){
			$string_folder = substr($string_folder, 0, strlen($string_folder)-1);
		}
		//echo ARMDebug::li("string_folder retornando:$string_folder");
		return $string_folder;
	}



	/**
	 * @param $string_folder
	 * @return string

	 static function removeLastBar($string_folder){
	 //echo ARMDebug::li("string_folder:$string_folder");
	 if(strlen($string_folder) > 0 && $string_folder[strlen($string_folder)-1] == "/"){
	 $string_folder = substr($string_folder, 0, strlen($string_folder)-2);
	 }
	 //echo ARMDebug::li("string_folder retornando:$string_folder");
	 return $string_folder;
	 }*/
	static function removeEntityAccent($str, $encode = 'UTF-8'){
		$acentos = array(
				'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
				'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
				'C' => '/&Ccedil;/',
				'c' => '/&ccedil;/',
				'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
				'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
				'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
				'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
				'N' => '/&Ntilde;/',
				'n' => '/&ntilde;/',
				'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
				'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
				'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
				'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
				'Y' => '/&Yacute;/',
				'y' => '/&yacute;|&yuml;/',
				'a.' => '/&ordf;/',
				'o.' => '/&ordm;/',
				' ' => '/&nbsp;|&bull;|&ldquo;/'


		);
		return preg_replace(array_values($acentos), array_keys($acentos), $str);
	}
	static function removeAccent($string, $entitys_to = FALSE){
		//@TODO: resolver isso
		// $string = self::encode( $string ) ;

		//return $string;
		$string = str_replace(explode(" ", "Ã Á À Â"), 'A', $string);
		$string = str_replace(explode(" ", "ã á à â"), 'a', $string);

		$string = str_replace(explode(" ", "É È Ê"), 'E', $string);
		$string = str_replace(explode(" ", "é è ê"), 'e', $string);

		$string = str_replace(explode(" ", "Í Ì Î "), 'I', $string);
		$string = str_replace(explode(" ", "í ì î"), 'i', $string);

		$string = str_replace(explode(" ", "Õ Ó Ò Ô"), 'O', $string);
		$string = str_replace(explode(" ", "õ ó ò ô"), 'o', $string);

		$string = str_replace(explode(" ", "Ú Ù Û"), 'U', $string);
		$string = str_replace(explode(" ", "ú ù û"), 'u', $string);

		$string = str_replace("ç", 'c', $string);
		$string = str_replace("Ç", 'C', $string);

		if($entitys_to){
			$string = self::removeEntityAccent($string);
		}
		return $string;
	}

	/**
	 * Pega uma URL qualquer e completa ela com HTTP para usar como Link
	 * @param unknown $url
	 * @param string $force_www
	 * @return string
	 */
	public static function normalizeUrlToLink( $url , $force_www = FALSE){
		$SSL = "" ;
		$WWW = "" ;

		if( strpos(  $url , "https") !== FALSE )
			$SSL = "s";

		if( strpos( $url , "www") !== FALSE  || $force_www )
			$WWW = "www.";

		$remove = array(
				"http{$SSL}://",
				"www."
						);

		$url = str_replace( $remove , "", $url );

		return "http{$SSL}://{$WWW}" . $url  ;
	}


	static function cleanStringsForSearch($text){
		$text = self::forceString($text, TRUE);
		$text = self::removeAccent($text, TRUE);
		$text = str_replace(array("    ", "   ", "  ", "
"), " ", $text);

		return trim($text);
	}


	static function ecmaToUnderline($string){
		$newString 			= "";
		for($i = 0; $i < strlen($string); $i++){
			//$string[$i]

			if(ARMDataHandler::isUpper($string[$i])){
				$newString .= "_".strtolower($string[$i]);
			} else {
				$newString .= $string[$i];
			}
		}
		return $newString;
	}




	public static function cropString( $string, $limit ,$default_continue = '...'){
		if(strlen($string)<= $limit )
			return $string;
		return substr($string, 0, $limit) . $default_continue;
	}

	/* exempro de $content_info a ser enviado
	 * $email_content_info = array(
	 		"###CLIENT_NAME###" =>  $name ,
	 		"###CLIENT_EMAIL###" => $email,
	 		"###SENT_DATE###" => date("d/m/Y") ,
	 		"###MESSAGE###" => $message ,
	 		"###SUBJECT###" => 	"Pagamento confirmado para o pedido #" . $poVO->getId()
	 );
	*/
	public static function templateReplace( $content ,  $content_info = NULL ){
		$content;
		if( $content_info != NULL && is_array($content_info)) {

			foreach( $content_info as $tplTag => $replacement ){
				$content = str_replace($tplTag, $replacement, $content);
			}

		}
		return $content;
	}

	public static function removeWordsByLimit( $string , $limit = 2 ){
		// @TODO: remover baseado na string enviada palavras com menos caracteres do que citado em limit
		$stringReturn = "";
		if( strpos( $string , " ") > 0 ){
			$stringArray = explode(" ", $string ) ;
			foreach( $stringArray as $item ){
				$item = trim( $item );
				if( strlen( $item ) <= $limit ){
					$stringReturn .= $item ." ";
				}
			}
		}
		return trim( $stringReturn ) ;
	}
}