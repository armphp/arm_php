<?php
//session_start();
// @TODO: liga e desliga de um debug true
// if( isset($_REQUEST["debug"] )   && ( $_REQUEST["debug"] == "on" ||  $_REQUEST["debug"] == "off" ) ) {
// 	$debugState = $_REQUEST["debug"]=="on" ? true : false ;
// 	$_SESSION["debug"] = $debugState;
// 	echo "DEBUG MODE " . ($debugState? "ATIVADO" : "DESATIVADO" ).  "...aguarde para ser redirecionado";
// 	? >
// <!-- 	<script type="text/javascript"> -->
// 	<!--
// 	var t=setTimeout(function(){
// 		window.location = window.location.href.replace("debug=" , "");
// 	},3000);
// 	
//	</script>
//	<?php  die();
// }
// if(isset($_SESSION["debug"])){
// 	ARMDebug::setARMDebug($_SESSION["debug"]);
// }
//ARMDebug::setARMDebug(false)

	/*
	 * @author		: Renato Seiji Miawaki
	 * @data		: 07/07/2009
	 * @version		: 1.0
	 * @description	: 	Classe para auxilio de debugs
	 					Modo de uso
							primeiro:
								de o include_once da classe
							segundo:
								//se quiser que de echo
								ARMDebug::li("testando");
								//se quiser guardar o debug em variavel
								$teste = ARMDebug::li("testando com retorno", TRUE);
	 */
function d( $obj ){
	ARMDebug::dump( $obj );
}
function dd( $obj ){
	d( $obj ); die;
}
function li( $val , $trace = FALSE ){
	ARMDebug::li( $val , false, $trace ) ;
}
class ARMDebug{
	
	
	private static $debugState = false;
	 
	
	private static $timelist = array();
	/**
	 * ahuhaHAUHAuHAHUA vc é um gênio alan! Muito bom, adorei essa funcionalidade!
	 * @param string $label
	 */
	static function timeMark( $label = "" ) {
		$time = microtime(TRUE);
		
		ARMDebug::$timelist[] = array( "label" =>  $label , "time" => $time ) ;
	}

	static function timeInfo( $reset = TRUE , $echoString = TRUE){
// 		if(! ARMDebug::canARMDebug())
// 			return false;
		
		ARMDebug::timeMark("last mark");
		$logString = "";
		$firstTime = 0;
		if(isset(ARMDebug::$timelist[0])){
			$timeInfo = ARMDebug::$timelist[0] ;
			$firstTime 		=  $timeInfo["time"] ;
		}
		$lastTime = null ;
		for ( $i = 0 ;  $i < sizeof( ARMDebug::$timelist ) ; $i++) {
			$timeInfo = ARMDebug::$timelist[$i] ;
			$timeInfo = (object) $timeInfo ;
			
			
			$time 		=  $timeInfo->time * 1000000 ;
			$time_diference = ( $lastTime ) ? $time - $lastTime : 0 ;
			$time_diference = $time_diference  ;
			
			$subtotal 	= $time-$firstTime  ;
			$logString .= ARMDebug::li($time . " -> " . $timeInfo->label."  ($time_diference) | subtotal: $subtotal");
			
			$lastTime = $time ;
		}
		
		if( $reset ) {
			ARMDebug::$timelist = array() ;
		}
		if($echoString){
			echo $logString;
		}
		return $logString;
	}
	
	public static function setARMDebug( $onOrOff = TRUE ){
		ARMDebug::$debugState = $onOrOff;
	}
	
	public static function canARMDebug(){
		return ARMDebug::$debugState;
	}
	
	static function li($text = "", $return = FALSE, $trace = FALSE, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor = "#003300"){
		
		$new_text = "<div class='debug'><hr />";
		if($trace || isset($_GET["force_trace_debug"])){
			$ex = new Exception("debug");
			$trace = str_replace("
","\n<br />", $ex->getTraceAsString());
			$new_text .= "trace: <br />".$trace."<br />";
		}
		
		$new_text .= "<div style=\"padding:2px; clear: both; min-width:500px; background-color:$bgColor;border:2px; border-color:$borderColor; color:{$fontColor} !important; font-family:Arial, Helvetica, sans-serif;\">
	$text
</div></div>";
		if($return){
			return $new_text;
		}
		echo $new_text;
	}
	
	
	public static function error( $text,  $return = FALSE, $trace = FALSE ){
		$fontColor = "#FFFFFF" ;
		$bgColor = "#CC0000";
		
		if($return){
			return ARMDebug::li($text, TRUE, $trace, $fontColor , $borderColor = "#FF0000", $bgColor);
		}
		echo ARMDebug::li($text, FALSE, $trace, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor);
	}
	
	static function print_r($obj, $return = FALSE, $trace = FALSE){
		
		$fontColor = "#FFFFFF" ;
		$text = "<pre style='font-family:verdana; white-space:pre !important; color:{$fontColor} !important;text-align: left;'>\n";
		
		ob_start();
		var_dump(  $obj );
		$text .= ob_get_contents();
		ob_end_clean();
// 		$text .= print_r($obj, TRUE);
		
		$text .= "\n</pre>\n";
		$bgColor = "#003300";
		if(isset($obj->success)){
			if($obj->success){
				$bgColor = "#000033";
			} else {
				$bgColor = "#CC0000";
			}
		}
		
		if($return){
			return ARMDebug::li($text, TRUE, $trace, $fontColor , $borderColor = "#FF0000", $bgColor);
		}
		echo ARMDebug::li($text, FALSE, $trace, $fontColor = "#FFFFFF", $borderColor = "#FF0000", $bgColor);
	}

	static function dump($a){
		$args = func_get_args();

		foreach( $args as $arg ){
			self::print_r( $arg );
		}
	}

	static function dbLog( $data ){
		ARMClassIncludeManager::load("ARMGenericDAO");
		$DAO  =  ARMGenericDAO::getInstance( "user" );
		
		$log= "";
		$log.= $_SERVER["REQUEST_URI"] . "\n";
		ob_start();
		
		$args = func_get_args();
		
		foreach( $args as $arg ){
			echo "\n =============================\n" ;
				var_dump( $arg );
		}
		
		$log.=  ob_get_contents();
		ob_end_clean();
		
		
// 		$log = addslashes( $log );  
		
		
// 		ARMDebug::print_r( $log ) ;
		try{
			$DAO->select("INSERT INTO debug_table VALUES (NULL, ? , NOW() );" , array( $log )  ) ;
		}catch (ErrorException  $e){}	
	}
	
	
	private static function debugCondition( $var_name  ){
		
		return ( isset( $_REQUEST[ $var_name ] ) || isset( $_REQUEST[ "debug_all" ] ) || isset( $_REQUEST[ "debug_" . $var_name ] ) );

	}
	
	static function ifLi( $data , $var_name = "debug", $autoBroke = FALSE){
		if( self::debugCondition( $var_name ) ){
			ARMDebug::li( $data , FALSE, $autoBroke);
		}
	}
	
	static function ifPrint( $data , $var_name = "debug" , $autoBroke = FALSE){
		if( self::debugCondition( $var_name ) ){
			ARMDebug::print_r( $data , FALSE, $autoBroke);
		}	
	}
	
}

	?>