<?php

/**
 * (pt-br) 	Classe que resolve resultados que atendem por json
 * 			Essa é a penas uma possibilidade, caso precise de um tipo específico, crie sua class e sete no config
 * @author renatomiawaki
 *
 */
class ARMXmlViewModule extends ARMBaseModuleAbstract implements ARMViewModuleInterface {
	
	/**
	 *
	 * @param HttpResult $result
	 * @param array $arrayPathFolder
	 */
	public function show( $result, $arrayPathFolder ) {
		//@TODO: fazer a implementação pra mostrar xml do objeto enviado
		echo "to-do";
		die;
	}
	
}