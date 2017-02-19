<?php 
/**
 * @author Renato Miawaki
 * @desc classe padrão para retorno de busca de arquivo
 */
class ARMReturnSearchClassVO{
	/**
	 * @var bool
	 */
	public $success = FALSE;
	/**
	 * @var string do nome da classe encontrada (só se encontrar)
	 */
	public $className;
	/**
	 * @var string do nome do methodo encontrado
	 */
	public $methodName;
	
	/**
	 * @var array das pastas que estão após o arquivo encontrado
	 */
	public $arrayRestFolder;
	public function __construct(){
		//
	}
}