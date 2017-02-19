<?php
/**
 *
 * Resultado padrão para a HttpRequestcontroller preparar para as Resolvers
 * @author renatomiawaki
 *
 */
class ARMHttpRequestDataVO {

	public $code ;
	public $result ;

	public function __construct()
	{
		$this->code = 200;
	}
}
