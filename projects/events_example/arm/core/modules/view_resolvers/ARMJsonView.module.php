<?php

/**
 * (pt-br) 	Classe que resolve resultados que atendem por json
 * 			Essa é a penas uma possibilidade, caso precise de um tipo específico, crie sua class e sete no config
 * @author renatomiawaki
 *
 * @version 1.1
 * 			Passe 'simple' como parametro e não receba os dados de resultado http
 * @version 1.2
 * 			JSON_UNESCAPED_UNICODE no json encode e utf8_decode no output
 *          (Na ARMDBManager.class.php inserimos o $dbLink->set_charset($config->getEncode());)
 * @author Rainer rainereduardolopez@gmail.com
 *
 */
class ARMJsonViewModule extends ARMBaseModuleAbstract implements ARMViewResolverInterface {

	/**
	 *
	 * @param ARMHttpRequestDataVO $result
	 * @param array $arrayPathFolder
	 */
	public function show( $result, $arrayPathFolder ) {
		header('Content-Type: application/json');
		if( ARMNavigation::getVar( "simple" ) ){

			$result = $result->result ;

		}



		$json = json_encode($result, JSON_UNESCAPED_UNICODE);

        if(!json_last_error()){

	        if( mb_detect_encoding($json, 'UTF-8', true) ){
		        //não é utf8, então encoda
		        echo utf8_decode( $json ) ;
		        die;
	        }


            echo $json;
            die;
        }

        $result->code = 403;
        $result->result = json_last_error_msg();
		echo json_encode($result);
		die;
	}

}
