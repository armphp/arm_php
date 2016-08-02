<?php
/*
 * @author		: Mauricio Amorim
 * @data		: 14/11/2010
 * @version		: 0.1
 * @description	: 	Classe para conexões com o banco de dados mysql
 Essa classe precisa que tenha um link de conexão com o banco aberta
 para evitar abrir conexões constantemente
 */

//@TODO: Precisa implementar uma interface que tenha os metodos que ele já tem
class ARMMysqliModule extends ARMBaseModuleAbstract {
	//confirmado
	const RETURN_STD_OBJECT							= "STR_OBJECT";
	const RETURN_VO									= "VO";

	const ORDER_ASC									= "ASC";
	const ORDER_DESC								= "DESC";
		
	
	const ERROR_UPDATE_DONOT_HAVE_ID					= -1;//não foi enviado o id para atualização
	const ERROR_UPDATE_DONOT_HAVE_UPDATES				= -2;//não foi enviado os dados para atualização
	const ERROR_UPDATE_COMMIT 							= -3;
	const ERROR_INSERT_COMMIT 							= -4;
	const ERROR_PARAMETER_DONOT_HAVE_TABLE_OR_TABLE_ID	= -5;//erro gerado quando no envidado o nome e o id referencia para ThermDAO ou ParameterDAO
	const ERROR_DUPLICATE_ENTRY		 					= 1062;//dois cadastros com unique key
	const ERROR_SQL_SINTAX			 					= 1064;//erro de sintax de SQL
	const ERROR_INSERT_WITHOUT_FOREIGN_KEY				= 1452;//chave primaria não existe para inserção
	const ERROR_INSERT_WITHOUT_OBRIGATORY_CAMP			= 1048;//algum campo obrigatorio recebeu vazio
	const ERROR_DELETE_OR_UPDATE_WITHOUT_FOREIGN_KEY	= 1451;//chave primaria não existe para exclusão
	
	//Cannot add or update a child row
	const ERROR_DONT_HAVE_LAST_INSERT_ID				= -6;//
	//erros para ThermDAO
	const ERROR_TERM_UPDATE_DONOT_HAVE_ID				= -11;//não foi enviado o id para atualização
	const ERROR_TERM_UPDATE_DONOT_HAVE_UPDATES			= -12;//não foi enviado os dados para atualização
	const ERROR_TERM_UPDATE_COMMIT 						= -13;
	const ERROR_TERM_INSERT_COMMIT 						= -14;
	const ERROR_TERM_DONOT_HAVE_TABLE_OR_TABLE_ID		= -15;//erro gerado quando no envidado o nome e o id referencia para ThermDAO ou ParameterDAO
	
	//erros para LinkDAO
	const ERROR_LINK_UPDATE_DONOT_HAVE_ID				= -21;//no foi enviado o id para atualização
	const ERROR_LINK_UPDATE_DONOT_HAVE_UPDATES			= -22;//no foi enviado os dados para atualização
	const ERROR_LINK_UPDATE_COMMIT 						= -23;
	const ERROR_LINK_INSERT_COMMIT 						= -24;
	const ERROR_LINK_DONOT_HAVE_TABLE_OR_TABLE_ID		= -25;//erro gerado quando no envidado o nome e o id referencia para ThermDAO ou ParameterDAO
	
	const ERROR_DELETE_RESTRICT							= -26;
	
	
	//confirmado
	const SUCCESS										= 1;
	

	const DATA_NOW 		= "!.#D_O_NW#.";
	const DATA_NULL 	= ".#D_O_NL#.";
	
	protected $DB_SLUG;
	
	public $saveResult = FALSE;

	/**
	 * @param string $alias
	 * @return ARMMysqliModule
	 */
	public static function getInstance( $alias = NULL ){
		if( ! $alias ){
			$alias = self::getDefaultAlias() ;
		}
		$result = parent::getInstance( $alias );

		return $result ;
	}
	/**
	 * @return ARMMysqliModule 
	 */
	public static function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_GLOBAL_ALIAS ){
		return parent::getInstaceByConfigVO( $configVO , $alias ) ;
	}
	public function setConfig( $ob ){
		
		$ob->alias = $this->__alias ;
		//ARMDebug::print_r( $ob ) ; 
		parent::setConfig( $ob ) ;
		
		//se ainda não tiver um config salvo com esse alias no pull, coloca
		if( ! ARMDBManager::getByAlias( $this->__alias ) ){
			ARMDBManager::add( $ob , $this->__alias );
		}
	}
	/**
	 * override
	 * @param object $configResult
	 * @return ARMDbConfigVO
	 */
	public function getParsedConfigData( $configResult ){
		$dbConfig = new ARMDbConfigVO();
		$configResult->alias = $this->__alias ;
		$dbConfig = ARMDataHandler::objectMerge( $dbConfig , $configResult , TRUE , TRUE ) ;

		return $dbConfig ;
	}
	public function setDbSlug( $value ){
		ARMValidation::isString( $value , TRUE );
		$this->DB_SLUG = $value ;
		
	}
	/**
	 * Força o nome da classe para o pegar sempre o mesmo config para classes que extendam a essa classe
	 * @override
	 */
	protected static function getConfigFolderName(){
		return "ARMMysqliModule" ;
	}
	
	private static $count_querys = 0;
	
	
	/**
	 *
	 * @param  (int)	$quant_limite = NULL
	 * @param  (int)	$quant_inicial = NULL
	 * @return string
	 */

	public function limit($quant_limit = NULL, $quant_start = NULL){
		//$quant_start
		$quant_limit 	= ARMDataHandler::forceInt($quant_limit);
		$quant_limit 	= ($quant_limit < 0)?0:$quant_limit;
		$quant_start 	= ARMDataHandler::forceInt($quant_start);
		$quant_start = ($quant_start < 0)?0:$quant_start;
		if($quant_limit > 0){
			return " LIMIT $quant_start, $quant_limit ";
		}
		return "";
	}
	/**
	 * testa a conexao
	 * @return boolean
	 */
	public function testConnection(){
		if ( ARMDBManager::getConn( $this->__alias ) ){
			return TRUE ;
		}
		
		return FALSE ;
	}
	/**
	 * @param string $simble
	 * @return string
	 */
	public function compareSimble($simble = "="){
		switch($simble){
			case ">":
				$simble = ">";
				break;
			case "<":
				$simble = "<";
				break;
			case "<=":
				$simble = "<=";
				break;
			case ">=":
				$simble = ">=";
				break;
			case "<>":
			case "!=":
				$simble = "<>";
				break;
			default:
				$simble = "=";
				break;
		}
		return $simble;
	}
	public function verifyOrderType($order){
		switch($order){
			case ARMMysqliModule::ORDER_DESC:
				$order = ARMMysqliModule::ORDER_DESC;
				break;
			case ARMMysqliModule::ORDER_ASC:
			default:
				$order = ARMMysqliModule::ORDER_ASC;
				break;
		}
		return $order;
	}
	
	/**
	 * @param string $date
	 * @return string
	 */
	public function dateHandlerScape($date = "NOW()"){
		return (strtoupper($date) == "NOW()" || strtoupper($date) == "NOW")?"NOW()":"'".$date."'";
	}
	public function lastInsertId(){
		
		$id = ARMDBManager::getConn( $this->__alias )->insert_id;//mysql_insert_id();
		
		//$mysqli->insert_id;
		
		if($id){
			$ReturnDataVO = new ARMReturnDataVO(TRUE, $id, ARMMysqliModule::SUCCESS);
			$ReturnDataVO->setReturnId($id);
			return $ReturnDataVO;
		}else{
			return new ARMReturnDataVO(FALSE, mysql_error(), ARMMysqliModule::ERROR_DONT_HAVE_LAST_INSERT_ID);
		}
	}
	public function query( $query ){
		if( $this->saveResult ){
			//@TODO: verificar se compensa usar o cache direto na query
			//aqui retornaria já os dados em cache
		}
		self::$count_querys++;
		$link = ARMDBManager::getConn( $this->__alias ) ;
		
		$returnResult = new ARMReturnDataVO() ;
		if( ! $link ){
			// 2002 CONNECTION_ERROR
			$returnResult->code_return = 2002 ;
			
			return $returnResult ;
		}
		try{
			
			$result = mysqli_query( $link, $query );
			
			ARMDebug::ifLi($query , "sql");
			
			$erro_number = mysqli_errno($link);
			
			if($erro_number){
				$returnResult = new ARMReturnDataVO(FALSE, mysql_error(), $erro_number, $query );
				ARMDebug::ifPrint( $returnResult , "sql_error") ;
			}else{
				$returnResult = new ARMReturnDataVO(TRUE, $result, NULL, $query );
				$returnResult->setReturnId( $link->insert_id ) ;
			}
			ARMDebug::ifPrint( $returnResult , "sql") ;
			if( $this->saveResult ){
				//@TODO: verificar se compensa usar o cache direto na query
				//;cache não implementado
				//grava o resultado caso ele já não exista
				//$content = serialize($returnResult);
				//ARMDataHandler::writeFile($folder, $file_cache_name, $content);
			}
			return $returnResult;
		} catch (Exception $e){
			$ReturnDataVO->success  = FALSE;
			$ReturnDataVO->setResult( $e ) ;
			$ReturnDataVO->code_return = mysql_errno();
			return $ReturnDataVO;
		}
	}//end function query
	public static function fetchObject( $result ){
		//throw new ErrorException($result);
		return mysqli_fetch_object($result);
	}
}//end class