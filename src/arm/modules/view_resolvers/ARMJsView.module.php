<?php

/**
 * (pt-br) 	Classe que resolve resultados que atendem por js ajax cross domain
 * 			Quando necessita de uma requisição pra outro domínio, efetue a chamada desse tipo de resultado e
 * 			coloque o resultado como script da página. opcionalmente envie o metodo callback 
 * 			Caso não envie o 'method' indicando o metodo de callback, ele irá usar o ARMAjax.crossCallback
 * 
 * 			Essa é a penas uma possibilidade, caso precise de um tipo específico, crie sua class
 * @author renatomiawaki
 *
 */
class ARMJsViewModule extends ARMBaseModuleAbstract implements ARMViewResolverInterface {
	
	/**
	 *
	 * @param HttpResult $result
	 * @param array $arrayPathFolder
	 */
	public function show( $result, $arrayPathFolder ) {
		//opcionalmente envie o parametro method para definir o callback
		$callbackMethod = ARMNavigation::getVar( "method" ) ;
		if( ! $callbackMethod ){
			//Caso não envie o 'method' indicando o metodo de callback, ele irá usar o ARMAjax.crossCallback
			$callbackMethod = "ARMAjax.crossCallback" ;
		}
		header('Content-Type: text/javascript') ;
			$requestURL = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
			echo  "{$callbackMethod}(" .  json_encode(  $result ) . " , '{$requestURL}');";
			die;
		die;
	}
	
}