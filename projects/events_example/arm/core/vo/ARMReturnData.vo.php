<?php 
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @description	: Essa classe representa o padrão de retorno para as DAOs e DbIterface
	 * 
	 * @author		: Renato Miawaki
	 * @data		: 22/10/2013
	 * @version		: 2.0
	 * @description	: Essa classe representa o padrão de retorno para as DAOs e DbIterface
	 * 
	 * 
	 * 
	 */

class ARMReturnDataVO{
	public $success		   	= FALSE ;
	public $result 		   	= NULL ;
	public $code_return	   	= NULL ;
	public $error_message 	= NULL ;
	/**
	 * total de resultados sem o limit
	 * @var number
	 */
	public $count_total    	= NULL ;
	public $offset			= NULL ;
	public $limit			= NULL ;
    public $query          	= NULL ;
    
	private $_hasResult 	= NULL ;
	private $result_fetch_object ;
	private $result_fetch_vo ;
	/**
	 * só existe se o return_id for passada na query e se for possível retornar o id
	 * @var int
	 */
	private $return_id;
	public function getReturnId(){
		return $this->return_id;
	}
	public function setReturnId($id){
		if( ! $this->return_id ){
			$this->return_id = $id;
		}
	}
	
	/**
	 * Retorna booleando para dizer se esse objeto guarda dentro dele 1 ou mais resultados
	 * @return boolean
	 */
	public function hasResult(){
		if( $this->_hasResult === NULL ){
			$this->_hasResult = ($this->success && count( $this->result ) > 0);
		}
		return $this->_hasResult ;
	}
	/**
	 * @param bool $success
	 * @param return data $result
	 * @param int $code_return
     * @param string $query  
	 */
	public function __construct($success = FALSE, $result = NULL, $code_return = NULL, $query = NULL ){
		$this->success 			= $success;
		$this->result 			= $result;
		$this->code_return		= $code_return;
        $this->query        	= $query;
	}
	public function getUniqueResult(){
		
		if( $this->hasResult() ){
			return $this->result[0] ;
		}
		
		return NULL ;
	}
	/**
	 * faz o fetch do resultado em objeto
	 */
	public function fetchAll(){
		//vai retornar como objeto
		if( ! $this->result_fetch_object ){
			if( ! is_array( $this->result ) ){
				$this->fetchObject( $this->result );
			} else if( count( $this->result ) > 0 && is_object( $this->result[0] ) ) {
				$this->result_fetch_object = $this->result ;
			}
		}
		$this->result                   = $this->result_fetch_object ;
	}
	/**
	 *
	 * @param resultado do tipo mysqli $mysql_result
	 * @return Ambigous <multitype:, unknown, string, multitype:ARMAutoParseAbstract >
	 */
	private function fetchObject( $mysql_result ){
		if( $this->result_fetch_object ){
			return $this->result_fetch_object ;
		}
		$this->result_fetch_object = array();

        while( $r = ARMMysqliModule::fetchObject( $mysql_result ) ){
			$this->result_fetch_object[] = $r;
		}
		return $this->result_fetch_object;
	}
	public function fetchAllVO( ARMModelGatewayInterface $Gateway ){
		if(!$this->result_fetch_vo){
			$temp_result = array();
			$this->fetchAll();
			for($i = 0; $i < count( $this->result_fetch_object ); $i++){
				$tempVO = $Gateway->getVO();
				$tempVO->parseObject( $this->result_fetch_object[$i] );
				$temp_result[] = $tempVO;
			}
			$this->result_fetch_vo = $temp_result;
		}
		$this->result = $this->result_fetch_vo;
	}

	/**
	 * @var object[]
	 */
	public $entities_std ;
	public function fetchAllEntityToStd( ARMModelGatewayInterface $Gateway ){
		if(!$this->entities_std){
			$temp_result = array();
			$this->fetchAll();
			for($i = 0; $i < count( $this->result_fetch_object ); $i++){
				$tempVO = $Gateway->getEntity();

				$tempVO->fetchObject( $this->result_fetch_object[$i] );
				$temp_result[] = $tempVO->toStdClass();
			}
			$this->entities_std = $temp_result;
		}
		$this->result = $this->entities_std;
	}
	public function getCode(){
		return $this->code_return;
	}
}