<?php

/**
 * (pt-br) 	Classe que resolve resultados que atendem por json
 * 			Essa é a penas uma possibilidade, caso precise de um tipo específico, crie sua class e sete no config
 * @author renatomiawaki
 *
 * @version 1.1
 * 			Passe 'simple' como parametro e não receba os dados de resultado http
 *
 */
class ARMJsonViewModule extends ARMBaseModuleAbstract implements ARMViewResolverInterface {
	
	/**
	 *
	 * @param HttpResult $result
	 * @param array $arrayPathFolder
	 */
	public function show( $result, $arrayPathFolder ) {
		header('Content-Type: application/json');
		if( ARMNavigation::getVar( "simple" ) ){
			$result = $result->result ;
		}
		echo json_encode( $result );
		die;
	}
	
}