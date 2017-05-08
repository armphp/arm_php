<?php

/**
 * (pt-br) 	Classe que resolve resultados que atendem por json
 * 			Essa é a penas uma possibilidade, caso precise de um tipo específico, crie sua class e sete no config
 * @author renatomiawaki
 *
 */
class ARMDumpViewModule extends ARMBaseModuleAbstract implements ARMViewResolverInterface {
	
	/**
	 *
	 * @param HttpResult $result
	 * @param array $arrayPathFolder
	 */
	public function show( $result, $arrayPathFolder ) {
		dd( $result );
	}
	
}