<?php

/**
 * 
 * Configuração para o ARMModelGatewayModule criar as DAO, Gateway e VOs
 * 
 * @author renatomiawaki
 *
 */
class ARMModelGatewayConfigToMakeVO extends ARMAutoParseAbstract{
	/**
	 * Alias na conexão que vai ser utilizada ARMMysqliConnectionModule
	 * @var string 
	 */
	public $mysqli_instance_alias ;
	/**
	 * 
	 * @var array do nome das tabelas que precisa criar
	 */
	public $tables ;
	
	/**
	 * ( opctional )
	 * @var string Prefixo para o nome de todas as classes criadas
	 */
	public $prefixClassName = "" ; 
	
	/**
	 * path da pasta onde serão salvos os arquivos criados
	 * @var string
	 */
	public $targetFolder ;
	/**
	 * se true, ele cria novamente mesmo que o arquivo já exista. 
	 * Se false, ele não cria se perceber que já existe
	 * @var boolean
	 */
	public $forceOverride ;
}