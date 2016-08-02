<?php
 /*
 * @autor		: Renato Miawaki - reytuty@gmail.com
 * @data		: 15/12/2009
 * @versao		: 1.0
 * @comentario	: listaDiretorio($urlDoDiretorio) - metodo adicional para listar arquivos de um diretï¿½rio
 */
 /*
 * @autor		: Renato Miawaki | Mauricio Amorim | Alan Lucian
 * @data		: 07/07/2010
 * @versao		: 1.1 (inglï¿½s)
 * @comentario	: listDirectory($urlOfDirectory) - metodo adicional para listar arquivos de um diretï¿½rio
 * @description	: 	Classe para auxilio na manipulaï¿½ï¿½o de dados.
 */
 /*
 * @autor		: Leandro Leal
 * @data		: 22/04/2014
 * @versao		: 1.2
 * @comentario	: Nova função  clearFolder para limpar os arquivos temporários da pasta temp e afins.
 *
 *
 */
/*
 * @autor		: Renato Miawaki
 * @data		: 01/12/2015
 * @versao		: 1.3
 * @comentario	: novos metodos de tratamento de pastas
 *
 *
 */
class ARMDataHandler extends ARMDataStringHandler {

	
	/**
	 * Pega uma array de objetos instanciados e o nome de uma de suas propriedades e converte numa array só com os valores desse campo
	 * @param array $array_of_objects
	 * @param string $key_with_ids
	 * @return multitype:<number>
	 */
	public static function arrayObjectToArrayIds($array_of_objects, $key_with_ids = "id"){
		$array_return = array();
		if(! is_array( $array_of_objects )){
			return $array_return ;
		}
		foreach( $array_of_objects as $item ){
			if( property_exists( $item, $key_with_ids ) ){
				$array_return[] = $item->$key_with_ids ;
			}
		}
		return $array_return ;
	}

	/**
	 * @param $name
	 * @param string $defaultName if no rest good strings
	 * @return mixed|string
	 */
	public static function returnValidDbTableName( $name , $defaultName = "table" ){
		$name = str_replace( array("__", " ", "-"), "_", $name ) ;
		$name = preg_replace( "/([^0-9a-zA-Z_])/", "", $name ) ;
		if( ! strlen( $name ) > 0 ){
			$name = self::returnValidDbTableName( $defaultName )  ;
		}
		return $name ;
	}
	static $array_relacional_utf8_iso;
	/**
	 * @param 	string $date
	 * @return 	string
	 * @coment 	Envie o data em qualquer formato e esse metodo deve deixar no formatod o banco
	 * 			atualmente aceita dd-mm-aaaa ou aaaa-dd-mm
	 */
	static function convertDateToDB($date){
		if(preg_match_all("/([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9][0-9]{2}) ([0-2]?[0-9]):([0-6]?[0-9]):([0-6]?[0-9])/", $date, $arrayDate)){
			$date = $arrayDate[3][0]."-".$arrayDate[2][0]."-".$arrayDate[1][0]." ".$arrayDate[4][0].":".$arrayDate[5][0].":".$arrayDate[6][0];
		} else if(preg_match_all("/([0-9]?[0-9][0-9]{2})[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9]) ([0-2]?[0-9]):([0-6]?[0-9]):([0-6]?[0-9])/", $date, $arrayDate)){
			$date = $arrayDate[1][0]."-".$arrayDate[2][0]."-".$arrayDate[3][0]." ".$arrayDate[4][0].":".$arrayDate[5][0].":".$arrayDate[6][0];
		} else if(preg_match_all("/([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9][0-9]{2})/", $date, $arrayDate)){
			$date = $arrayDate[3][0]."-".$arrayDate[2][0]."-".$arrayDate[1][0];
		} else if(preg_match_all("/([0-9]?[0-9][0-9]{2})[\/\.\\\-]([0-9]?[0-9])[\/\.\\\-]([0-9]?[0-9])/", $date, $arrayDate)){
			$date = $arrayDate[1][0]."-".$arrayDate[2][0]."-".$arrayDate[3][0];
		}
		return $date;
	}
	
	
	public static function DOMNodeListToArray( DOMNodeList $DOMNodeList ){
		$list = array();
		for( $i = 0 ; $i < $DOMNodeList->length ; $i++ ){
			$list[] =  self::DOMElementToObject( $DOMNodeList->item( $i ) ) ;
		}
		return $list ;
	}

	/**
	 *
	 * @param $DOMElement
	 * @return stdClass
	 */
	public static function DOMElementToObject(  $DOMElement ){
		
// 		var_dump( $DOMElement );
		
// 		if(   )
	 	
		$element = new stdClass();
		
		if( $DOMElement->hasAttributes() ){
			$attr = new stdClass();
			for ($i = 0; $i < $DOMElement->attributes->length; $i++) {
// 				echo "@". $DOMElement->attributes->item($i)->name . " -> " . $DOMElement->attributes->item($i)->value ;
				$attr_name = $DOMElement->attributes->item($i)->name;
				$attr_value = $DOMElement->attributes->item($i)->value;
				$attr->$attr_name =  $attr_value ;
				
			}
			$element->attributes = $attr ;
		}
		if( $DOMElement->hasChildNodes() ){
			$childs = self::DOMNodeListToArray( $DOMElement->childNodes );
			$element->childs		= $childs ;
		}
		
		$element->name 			= $DOMElement->nodeName ;
		$element->value			= $DOMElement->nodeValue ;
		
		return $element ;
		
	}


	/**
	 *
	 * Fill the first object with the second item just a short-cut and a sematic way to use objectMerge
	 * @param $main_object
	 * @param $object_to_add
	 *
	 * @return object
	 */
	public static function fillObject( $main_object, $object_to_add ){
		return self::objectMerge( $main_object, $object_to_add, TRUE, TRUE );
	}


	/**
	 * 
	 * Adiciona as propriedades do segundo objeto no primeiro, mantendo as do primeiro caso haja duplicidade
	 * 
	 * @param object $main_object
	 * @param object $object_to_add
	 * @param object $override_null
	 * @param object $overrideAll  // sobrescreve tudo que tem no main com o que veio da outra
	 */
	public static function objectMerge( $main_object, $object_to_add , $override_null = FALSE , $override_all = FALSE){
		if( !$object_to_add ){
			return $main_object ;
		}
		foreach( $object_to_add as $key => $value){
			$add = FALSE ;
			
			if( !property_exists( $main_object, $key ) )
				$add = TRUE ;

//            ARMDebug::dump( $key."-",$override_null, ARMClassHandler::hasPublicAttrribute($main_object,$key) , is_null( $main_object->$key ));
			if( $override_null && ARMClassHandler::hasPublicAttrribute($main_object,$key) && is_null( $main_object->$key ) ){

				$add = TRUE ;
            }
			
			if( $override_all )
				$add = TRUE ;
			
			if( $add )
				$main_object->$key = $value ;
				
		}
		
		return $main_object ;
	}
	
	
	
	
	static function convertDbDateToLocale($locale = "pt-br", $date_time, $noTime = FALSE){
		switch($locale){
			case "en":
				return ARMDataHandler::convertDateToEua($date_time, $noTime);
				break;
			case "pt-br":
			default:
				return ARMDataHandler::convertDateToBrazil($date_time, $noTime);
				break;
		}
	}
	
	
	/** 
	 * atenÃ§Ã£o sÃ³ converte data vindo com formato do banco
	 * @param $date_time
	 * @param $noTime
	 * @return date
	 */
	static function convertDateToEua($date_time, $noTime = FALSE){
		//@TODO: Upgrade p/ usar o DataMask  ( esse metodo vai sumir)
		# ($date_time, $output_string, $utilizar_funcao_date = false) {
		// Verifica se a string estÃ¡ num formato vÃ¡lido de data ("aaaa-mm-dd" ou "aaaa-mm-dd hh:mm:ss")
		if (preg_match("/^(\d{4}(-\d{2}){2})( \d{2}(:\d{2}){2})?$/", $date_time)) {
			$valor['d'] = substr($date_time, 8, 2);
			$valor['m'] = substr($date_time, 5, 2);
			$valor['Y'] = substr($date_time, 0, 4);
			$valor['y'] = substr($date_time, 2, 2);
			$valor['H'] = substr($date_time, 11, 2);
			$valor['i'] = substr($date_time, 14, 2);
			$valor['s'] = substr($date_time, 17, 2);
			// Verifica se a string estï¿½ num formato vÃ¡lido de horï¿½rio ("hh:mm:ss")
		} else if (preg_match("/^(\d{2}(:\d{2}){2})?$/", $date_time)) {
			//se nÃ£o tinha hora na data enviada, vai sem data
			$noTime = TRUE;
			$valor['d'] = NULL;
			$valor['m'] = NULL;
			$valor['Y'] = NULL;
			$valor['y'] = NULL;
			$valor['H'] = substr($date_time, 0, 2);
			$valor['i'] = substr($date_time, 3, 2);
			$valor['s'] = substr($date_time, 6, 2);
		} else {
			return NULL;
		}
		if($noTime){
			$return = $valor['m']."-".$valor['d']."-".$valor['Y'];
		}else{
			$return =  $valor['m']."-".$valor['d']."-".$valor['Y']." ".$valor['H'].":".$valor['i'].":".$valor['s'];
		}
		if($return != "--"){
			return $return;
		}
		return NULL;
	}
	
	/**
	 * @param $date_time
	 * @param $noTime
	 * @return string data
	 */
	static function convertDateToBrazil($date_time, $noTime = FALSE){
		//@TODO: Upgrade p/ usar o DataMask  ( esse metodo vai sumir)
		# ($date_time, $output_string, $utilizar_funcao_date = false) {
		// Verifica se a string estï¿½ num formato vï¿½lido de data ("aaaa-mm-dd" ou "aaaa-mm-dd hh:mm:ss")
		if (preg_match("/^(\d{4}(-\d{2})-\d{2})$/", $date_time)) {
			$valor['d'] = substr($date_time, 8, 2);
			$valor['m'] = substr($date_time, 5, 2);
			$valor['Y'] = substr($date_time, 0, 4);
			// Verifica se a string estï¿½ num formato vï¿½lido de horï¿½rio ("hh:mm:ss")
		} else if (preg_match("/^(\d{4}(-\d{2})-\d{2}) (\d{2}(:\d{2}):\d{2})?$/", $date_time)) {
			$valor['d'] = substr($date_time, 8, 2);
			$valor['m'] = substr($date_time, 5, 2);
			$valor['Y'] = substr($date_time, 0, 4);
			$valor['y'] = substr($date_time, 2, 2);
			$valor['H'] = substr($date_time, 11, 2);
			$valor['i'] = substr($date_time, 14, 2);
			$valor['s'] = substr($date_time, 17, 2);
			// Verifica se a string estï¿½ num formato vï¿½lido de horï¿½rio ("hh:mm:ss")
		} else if (preg_match("/^(\d{2}(:\d{2}){2})?$/", $date_time)) {
			//se nÃ£o tinha hora na data enviada, vai sem data
			$noTime = TRUE;
			$valor['d'] = NULL;
			$valor['m'] = NULL;
			$valor['Y'] = NULL;
			$valor['y'] = NULL;
			$valor['H'] = substr($date_time, 0, 2);
			$valor['i'] = substr($date_time, 3, 2);
			$valor['s'] = substr($date_time, 6, 2);
		} else {
			return $date_time;
		}
		if($valor['d'] == ""){
			return "";
		}
		if($noTime || !isset($valor['H'])){
			return $valor['d']."/".$valor['m']."/".$valor['Y'];
		}else{
			return  $valor['d']."/".$valor['m']."/".$valor['Y']." ".$valor['H'].":".$valor['i'].":".$valor['s'];
		}
		
	}

	/**
	 * Se enviar /pasta/subpasta/file.jpg retorna file.jpg
	 * @param $filePath
	 * @return string
	 */
	public static function returnFilenameOfFolderPath( $filePath ){
		if(!$filePath){
			return $filePath ;
		}
		$name_array = explode("/", $filePath);
		return $name_array[ count($name_array)-1 ] ;
	}
	static function returnFilenameWithoutExtension($name){

		$name_array = explode(".", $name);
		$name = "";
		for($i = 0; $i < count($name_array)-1; $i++){
			$name .= $name_array[$i];
		}
		return $name;
	}

	static function returnFoldernameOfFilepath($name){
		$name_array = explode("/", $name);
		$name_array[count($name_array)-1] = "" ;
		return implode( "/", $name_array );
	}
	static  function returnExtensionOfFile($name){
		$name_array = explode(".", $name);
		return $name_array[count($name_array)-1];
	}
	static function createRecursiveFoldersIfNotExists($url){
		$array_folders = explode("/", $url);
		if(is_array($array_folders) && count($array_folders) > 0){
			$totalFolder = "";
			foreach($array_folders as $folder){
				$totalFolder .= $folder."/";
				self::createFolderIfNotExist($totalFolder);
			}
		} else {
			self::createFolderIfNotExist($url);
		}
	}
	static function createFolderIfNotExist($url , $mode = 0775){
		//fazer o upgrade para ser recursivo
		if(!file_exists($url)){
			$mkdir_success = @mkdir($url , $mode );
			if( !$mkdir_success){
				throw new ErrorException( "ARMDataHandler::createFolderIfNotExist - mkdir - permission denied- ". getcwd() . DIRECTORY_SEPARATOR . $url ) ;
			}
		}
		if( !is_writable( $url ) ){
			$chmod_success = @chmod($url, $mode);
			if( !$chmod_success){
				throw new ErrorException( "ARMDataHandler::createFolderIfNotExist - chmod - permission denied- ". getcwd() . DIRECTORY_SEPARATOR . $url ) ;
			}
		}

	}
	
	
	//@TODO: sistema de mascara @
	static function convertMoneyToDB( $valueString ){
		//@TODO: Upgrade p/ usar o DataMask  ( esse metodo vai sumir)
		$valueString = preg_replace( "/^([^0-9._]*)/", "" , $valueString ) ;
		
		if(strpos($valueString, ',') === FALSE)
            return (float)$valueString;    
		$valueString = str_replace(".", "", $valueString);
		$valueString = str_replace(",", ".", $valueString);
		return (float)$valueString;
	}
	static function convertMoneyToBrazil($valueString, $simbol= TRUE){
		//@TODO: Upgrade p/ usar o DataMask  ( esse metodo vai sumir)
		$changeNumberF = TRUE;
	    if(strpos($valueString, ',') !== FALSE)
            $changeNumberF = FALSE;
		
		if($changeNumberF){
			$valueString = number_format((float) $valueString, 2 , ',', '.');
			
		}    
		return  ($simbol && strpos($valueString, 'R$') === FALSE ? 'R$ ' : '') . $valueString ;
		
		$valueString = str_replace(".", ",", $valueString);
		if(!preg_match_all("/.*,(.*)?/", $valueString, $arrayValue)){
			$valueString .= ",00";
		}
		//echo $arrayValor[1]." : ".($arrayValor[1])."<br>";
		if($arrayValue[1] < 10 && $arrayValue[1] > 0){
			$valueString .= "0";
		}
		//print_r($arrayValor[1]);
		return $valueString;
	}
	
    
    /**
     * Remove caracteres especiais ( feito para gerar strings limpas para campos de busca por exemplo )
     * Tenta manter o máximo que puder de conteúdo, só que sem caracteres especiais e sem acentos latinos 
     * @param String $string
     */
	static function removeSpecialCharacters($string, $keepWhiteSpace = FALSE){
		$string = str_replace("/", "", $string);
		$string = str_replace(".", "", $string);
		$string = self::removeAccent($string, TRUE);
		if($keepWhiteSpace){
			return @preg_replace("/([^a-zA-Z0-9_ -])/", "", $string);
		}
		return @preg_replace("/([^a-zA-Z0-9_-])/", "", $string);
	}
	static function strToURL($string){
		
//		echo $string;
		$string = trim($string);
		$string = str_replace("  ", " ", $string);
		$string = str_replace(" ", "-", $string);
		$string = str_replace("'", "", $string);
		$string = str_replace("\"", "", $string);
//		ARMDataHandler::
		$string = self::removeAccent($string, TRUE);
//		echo $string;
		$string = mb_strtolower($string, mb_detect_encoding($string));
//		echo $string;
//		echo "</br>";
		return $string;
	}
	
	
	
	
	
	
	static function addQuotes($string){
		$string = str_replace("'", "\'", $string);
		$string = str_replace("\"", "\\\"", $string);
		return $string;
	}
	 
	/**
	 * retorna string sem tags, html entitys e sem acento
	 * @param $text (string)
	 * @return string
	 * 
	 */
	
	static function writeFile($place, $name, $content, $fopenParam = "a+"){
		/*
		'r'  	 Abre somente para leitura; coloca o ponteiro do arquivo no comeï¿½o do arquivo.
		'r+' 	Abre para leitura e escrita; coloca o ponteiro do arquivo no comeï¿½o do arquivo.
		'w' 	Abre somente para escrita; coloca o ponteiro do arquivo no comeï¿½o do arquivo e reduz o comprimento do arquivo para zero. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'w+' 	Abre para leitura e escrita; coloca o ponteiro do arquivo no comeï¿½o do arquivo e reduz o comprimento do arquivo para zero. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'a' 	Abre somente para escrita; coloca o ponteiro do arquivo no final do arquivo. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'a+' 	Abre para leitura e escrita; coloca o ponteiro do arquivo no final do arquivo. Se o arquivo nï¿½o existir, tenta criï¿½-lo.
		'x' 	Cria e abre o arquivo somente para escrita; coloca o ponteiro no comeï¿½o do arquivo. Se o arquivo jï¿½ existir, a chamada a fopen() falharï¿½, retornando FALSE e gerando um erro de nï¿½vel E_WARNING. Se o arquivo nï¿½o existir, tenta criï¿½-lo. Isto ï¿½ equivalente a especificar as flags O_EXCL|O_CREAT para a chamada de sistema open(2).
		'x+' 	Cria e abre o arquivo para leitura e escrita; coloca o ponteiro no comeï¿½o do arquivo. Se o arquivo jï¿½ existir, a chamada a fopen() falharï¿½, retornando FALSE e gerando um erro de nï¿½vel E_WARNING. Se o arquivo nï¿½o existir, tenta criï¿½-lo. Isto ï¿½ equivalente a especificar as flags O_EXCL|O_CREAT para a chamada de sistema open(2). 
		*/
		if(!file_exists($place)){
			//ARMDebug::li("pasta nao existe $place ", false, true);
		}
		if (!$handle = fopen($place.$name, $fopenParam)) {
			return FALSE;
		}
	   if (!fwrite($handle, $content)) {
			return FALSE;
	   }
	   fclose($handle);
	   return TRUE;
	}
	public static function deleteDirectory($urlOfDirectory){
		$arrayExeptionFiles = array(".", "..");
		try{
			if(file_exists($urlOfDirectory)){
				$result = ARMDataHandler::listDirectory($urlOfDirectory);
				foreach($result as $item){
					$extencion = "";
					if(preg_match("/\./", $item)){
						//tem um ponto de extenção
						$extencion = strtolower(ARMDataHandler::returnExtensionOfFile($item));
					}
					$fileUrl = ARMDataHandler::removeDoubleBars($urlOfDirectory."/".$item);
					if($extencion == ""){
							if(in_array($item, $arrayExeptionFiles)){
								//não fazer nada com esse
							} else {
								$newfolder = ARMDataHandler::removeDoubleBars($urlOfDirectory."/".$item);
								if(file_exists($newfolder)){
									//é um folder, vai varrer dentro do folder tb - recursivo
									if(is_dir($newfolder)){
										//echo ARMDebug::li("a pasta será varrida:".$newfolder);
										self::deleteDirectory($newfolder);
									} else {
										unlink($newfolder);
									}
								}
							}
					} else {
						//ve se é um arquivo a se deletar
						unlink($fileUrl);
					}
				}
				//deleta o próprio diretório enviado
				rmdir($urlOfDirectory);
			}
		} catch(Exception $e){
			$returnResultVO = new ARMReturnResultVO(); 
			//mudinho
			$returnResultVO->success = FALSE;
			$returnResultVO->addMessage( $e );
			return $returnResultVO;
		}
		
	}
	static function listDirectory($urlOfDirectory, $extention = "*"){
		//$extencao : envie "jpg" caso queira sï¿½ os arquivos .jpg
		$arrayFiles = array();
		$extention = strtolower(str_replace(".", "", $extention));
		if (is_dir($urlOfDirectory)) {
			if ($dh = opendir($urlOfDirectory)) {
				while (($file = readdir($dh)) !== FALSE) {
					if($extention == "*"){
						array_push($arrayFiles, $file);
					} else {
						//filtrar pela extenï¿½ï¿½o
						if(strtolower( self::returnExtensionOfFile($file) ) == $extention){
							//ï¿½ do mesmo tipo procurado
							array_push($arrayFiles, $file);
						}
					}
				}
				closedir($dh);
			}
		} else {
			throw new Exception( "erro ao listar $urlOfDirectory " ) ;
		}
		return $arrayFiles;
	}
	static function forceType($valor, $type = "string"){
		switch($type){
			case "string":
			case "date":
				return ARMDataHandler::forceString($valor);
				break;
			case "number":
				return ARMDataHandler::forceNumber($valor);
				break;
			case "int":
				return ARMDataHandler::forceInt($valor);
				break;
			default:
				return ARMDataHandler::forceString($valor);
				break;
		}
	}
	
	
	/**
	 * 
	 * @param unknown $value
	 * @return boolean
	 */
	static function isNotNull($value){
		return !is_null($value);
	}
	static function arrayToXML($array){
		if(!is_array($array)){
			return "";
		}
		
	}
	/**
	 * @param object $obj
	 */
	static function objToXML($obj){
		$str_xml = "";
		foreach($obj as $key=>$value){
			//ARMDebug::li("varrendo key [$key] ");
			if(is_numeric($key)){
				$key = "item";
			}
			$str_xml .= "<$key>";
			if(is_array($value)){
				$str_xml .= ARMDataHandler::objToXML($value);
			} else if(is_object($value)){
				$str_xml .= ARMDataHandler::objToXML($value);
			} else {
				if(is_bool($value)){
					$value = ($value == TRUE)?"1":"0";
				}
				$str_xml .= "$value";
			}
			$str_xml .= "</$key>";
		}
		return $str_xml;
	}
	
	
	static function addSimpleFlash($largura, $altura, $nomeSwf, $arrayParametros=array(), $transparent=FALSE, $cor="#000000", $idFlash = NULL){

		//$urlTemplate = "classes/utils/templates/flash/object.html";
		
		if($idFlash == NULL || $idFlash == ""){
			$rand1 = rand(1, 3255);
			$rand2 = rand(1, 3255);
			$rand3 = rand(1, 3255);
			$idFlash = "flash_".$rand1."_".$rand2."_".$rand3;
		}
		###id_div_flash###
		
		global $URL;
		
		$flashConteudoSujo = "<div id=\"$idFlash\"></div><script>
var so = new SWFObject('".$URL.$nomeSwf."', '".$nomeSwf."', '".$largura."', '".$altura."', '9', '".$cor."');
    ###config###
    so.write('".$idFlash."');
	</script>";//file_get_contents($urlTemplate);
		$config = "";
		//criando a array de parametros
		if(count($arrayParametros)>0){
			$parametros = "?";
			for($i = 0; $i < count($arrayParametros); $i++){
				$config .= "
	so.addVariable(\"".$arrayParametros[$i][0]."\", \"".$arrayParametros[$i][1]."\");";
			}
		}
		if($transparent){
			$config .= "
	so.addParam('wmode', 'transparent')";
		}
		$flashConteudoSujo = str_replace("###config###", $config, $flashConteudoSujo);
		//echo "<br> ARMDebug mais que do mal:((((((".$flashConteudoSujo.")))))))))))";
		return $flashConteudoSujo;

	}
	/**
	 * transforma em data formato banco ou NOW() caso venha vazio, se enviar NOW() mantem NOW
	 * @param string $date
	 * @return string
	 */
	public static function dateHandlerNowOrDate($date = NULL){
		return (strtoupper($date) == "NOW()"||strtoupper($date) == "NOW")?"NOW()":self::convertDateToDB($date);
	}
	/**
	 * @param unknown_type $urlToSend
	 * @param unknown_type $titulo
	 * @param unknown_type $rotulo
	 * @param unknown_type $descriptionFile
	 * @param unknown_type $extencoesValidas
	 * @param unknown_type $urlToGet
	 * @param unknown_type $urlToDelete
	 * @param unknown_type $urlToCapa
	 * @param unknown_type $urlToGetThumb
	 * @param unknown_type $funcaoNoTermino
	 * @param unknown_type $jsID
	 * @param unknown_type $limiteDeFotos
	 * @return mixed
	 */
	static function addFlashSendImage(
			$urlToSend, 
			$titulo = "fotos",
			$rotulo = "escolher arquivo", 
			$descriptionFile  = "Todos os Arquivos", 
			$extencoesValidas = "*.jpg; *.jpeg", 
			$urlToGet = NULL,
			$urlToDelete = NULL,
			$urlToCapa = NULL,
			$urlToGetThumb = "", 
			$funcaoNoTermino = "alert", $jsID = "envioFoto", $limiteDeFotos = NULL){
		/*
		rotulo
		urlToSend
			urlToCapa
			urlProjeto
		descriptionFile
		extencoesValidas
		*/
		//echo "<li>urlToSend $urlToSend</li>";
		$arrayParametros = array();
		$arrayParametros[] = array("rotulo", 			($rotulo));
		//rotuloEnviar
		$arrayParametros[] = array("urlToSend", 		str_replace(array("&", "="), array("[@]", "[.]"), $urlToSend));
		$arrayParametros[] = array("urlToGetThumb", 	str_replace(array("&", "="), array("[@]", "[.]"), $urlToGetThumb));
		$arrayParametros[] = array("descriptionFile", 	($descriptionFile));
		$arrayParametros[] = array("extencoesValidas", ($extencoesValidas));
		$arrayParametros[] = array("titulo", 			($titulo));
		global $URL;
		$arrayParametros[] = array("urlProjeto", 		$URL);
		$arrayParametros[] = array("temCapa", 			"1");
		
		if($urlToCapa !== NULL){
			$arrayParametros[] = array("urlToCapa", str_replace(array("&", "="), array("[@]", "[.]"), ($urlToCapa)));
		}
		if($urlToGet !== NULL){
			$arrayParametros[] = array("urlToGetLista", str_replace(array("&", "="), array("[@]", "[.]"), ($urlToGet)));
		}
		if($urlToDelete !== NULL){
			$arrayParametros[] = array("urlToDelete", 	str_replace(array("&", "="), array("[@]", "[.]"), ($urlToDelete)));
		}
		if($funcaoNoTermino !== NULL){
			$arrayParametros[] = array("funcaoNoTermino", 	$funcaoNoTermino);
		}
		
		if($limiteDeFotos!== NULL){
			$arrayParametros[] = array("limiteDeFotos", 	$limiteDeFotos);
		}
		return ARMDataHandler::addSimpleFlash(400, 330, "template/_swf/envio_foto3.swf", $arrayParametros, FALSE, "#FFFFFF");
	}
	/**
	 * envie a array e o nome do nÃ³ da array que vc precisa e ele retorna o valor ou NULL se esse nÃ³ nÃ£o existir
	 * evitando o erro de campos que nÃ£o foram enviados por POST por exemplo, ou que nÃ£o existem numa array
	 * @param array $p_array
	 * @param string $node_name
	 * @return value or NULL
	 */
	public static function getValueByArrayIndex($p_array, $node_name, $default_value = NULL){
		return (isset($p_array[$node_name]))?$p_array[$node_name]:$default_value;
	}
	public static function getValueByStdObjectIndex($p_obj, $node_name, $default_value = NULL){
		if( ! $p_obj || ! is_object( $p_obj ) ){
			return $default_value ;
		}
		return (isset($p_obj->$node_name))?$p_obj->$node_name:$default_value;
	}
	public static function cleanArrayEmpyIndex($array){
        $rt = array();
        for ($i = 0; $i<sizeof($array) ; $i++ )
            if(!empty($array[$i]))
                $rt[] = $array[$i];
                
        return $rt;
    }
	
	
	
	/**
	 * @param $array array original
	 * @param $item_or_array que deve ser adicionado ao fim
	 * @return array jÃ¡ com os itens inclusos no final
	 */
	public static function appendArray($array, $item_or_array){
		if(is_array($array)){
			if(is_array($item_or_array)){
				foreach($item_or_array as $item){
					$array[] = $item;
				}
			} else {
				$array[] = $item_or_array;
			}
		}
		return $array;
	}
	
	//@TODO: 5 application stuff remove from HERE
	public static function stateToUF( $state ) {
		if( strlen( $state) == 2){
			return $state;
		}
		$state = ARMDataHandler::strToURL($state);
		$arrData = array(
				'AM' => 'Amazonas',
				'AC' => 'Acre',
				'AL' => 'Alagoas',
				'AP' => 'Amapá',
				'CE' => 'Ceará',
				'DF' => 'Distrito federal',
				'ES' => 'Espirito santo',
				'MA' => 'Maranhão',
				'PR' => 'Paraná',
				'PE' => 'Pernambuco',
				'PI' => 'Piauí',
				'RN' => 'Rio grande do norte',
				'RS' => 'Rio grande do sul',
				'RO' => 'Rondônia',
				'RR' => 'Roraima',
				'SC' => 'Santa catarina',
				'SE' => 'Sergipe',
				'TO' => 'Tocantins',
				'PA' => 'Pará',
				'BH' => 'Bahia',
				'GO' => 'Goiás',
				'MT' => 'Mato grosso',
				'MS' => 'Mato grosso do sul',
				'RJ' => 'Rio de janeiro',
				'SP' => 'São paulo',
				'RS' => 'Rio grande do sul',
				'MG' => 'Minas gerais',
				'PB' => 'Paraiba',
		);
	
		foreach(  $arrData as $uf=>$name ){
			$name = ARMDataHandler::strToURL( $name );
			if( $name == $state )
				return $uf;
				
		}
	
		return $uf;
	}
	
	
	
	/**
	 * @see ARMDataHandler::cleanBrazilianWordsToSearch
	 * 
	 */
	public static function getBrazilianArticles(){
		//@TODO: 5 application stuff remove from HERE
		
		return $articles  = array('e',
				'os','um',
				'uns','a',
				'as','uma',
				'umas','o',
				'a','os',
				'as','um',
				'uma','uns',
				'umas','a',
				'ao','à',
				'aos','às',
				'em','num',
				'numa','nuns',
				'numas','de',
				'do','da',
				'dos','das',
				'em','no',
				'na','nos',
				'nas','de',
				'dum','duma',
				'duns','dumas',
				'por','per',
				'pelo','pela',
				'pelos','pelas',
		);
	}
	
	/**
	 * @see ARMDataHandler::cleanBrazilianWordsToSearch
	 *
	 */
	public static function getOtherBrazilianWordsToRemove(){
		//@TODO: 5 application stuff remove from HERE
		
		return  $words  = array('a',
					'ante','após',
					'até','com',
					'de','desde',
					'em','entre',
					'para','por',
					'sem','sob',
					'sobre','trás',
					'como','que',
					'ou' );
	}
	
	/**
	 * Remove all irelevants words to do a text search on PT-BR language
	 * @param multitype:array |string $words
	 * @param string $separator
	 * @return multitype:array |string
	 */
	public static function cleanBrazilianWordsToSearch( $words , $separator = " "){
		//@TODO: 5 application stuff remove from HERE
		
		$is_array = is_array( $words ) ;
		
		$articles  = array_merge(self::getBrazilianArticles()  ,  self::getOtherBrazilianWordsToRemove() ) ;
		
		if( ! $is_array )
			$words = explode( $separator, $words );
		
		$final_words = array();
		foreach( $words as $word ){
			if( !in_array( $word, $articles) && strlen( $word) > 0 ){
				$final_words[] = $word ;
			}
		}
		
		if( $is_array )
			return $final_words ;
			
		return implode( $separator , $final_words );
	}
	
	
	public static function toCurrency( ARMDataMaskInterface $DataMask , $value , $useSymbol = FALSE ){
		
		$numberFormat = $DataMask->getCurrencyNumberFormat(); 
	
		$value = number_format( $value , $numberFormat->decimals, $numberFormat->dec_point , $numberFormat->thousands_sep) ;
		
		if( $useSymbol ) 
			return sprintf(  $DataMask->getCurrencySymbolTemplate() , $value );
		
		return $value;
	}


    /**
     * Método utilizado para remover todos os arquivos de um determinado diretório. Não remove sub-folder.
     * @param $folder
     * @param string $extesion
     */

    public static function clearFolder( $folder, $extesion = "*" ){

        $folder .= "/";
        $folder = self::removeDoubleBars( $folder );
        array_map('unlink', glob( $folder.'*.'.$extesion ));

    }
	
}
