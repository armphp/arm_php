<?php

/**
 *
 * Classe que representa uma entidade no banco de dados
 * Class ARMBaseEntityAbstract
 */
abstract class ARMBaseEntityAbstract implements ARMEntityInterface{
	
	protected $array_parameter  	= array();
	protected $__denyMethods        = array();

	/**
	 * array of string
	 * method names to ignore in toStdClass() method
	 * @param $_denyMethods
	 */
	public function __addDenyMethods($_denyMethods){
		$this->__denyMethods[] = $_denyMethods;
	}
	protected $VO ;

	function __construct(){
		$this->startVO();
		$this->__addDenyMethods("getFieldData") ;
		$this->__addDenyMethods("getVO") ;
	}

	protected function startVO(){
		throw new ErrorException("Implemente startVO na ".get_called_class());
	}
	
	public function commit( $validate = FALSE ) {
		$ReturnResultVO = new ARMReturnResultVO();
		$ReturnResultVO->success = true ;
		
		
		if( $validate )
			$ReturnResultVO = $this->validate();
		
		if( $ReturnResultVO->success == FALSE )
			return $ReturnResultVO;
		
		$update = FALSE ;
		if( isset($this->VO->id) &&  $this->VO->id > 0 ) {
			$update = TRUE ;
		}
		if( $this->hasData() ){
			$this->getVO();
			$this->commitVO( $this->VO, $this->getDAO() , $ReturnResultVO );
		}
		$ReturnResultVO->result = $this->VO;
		return $ReturnResultVO;
	}
	/**
	 * 
	 * @param string $alias
	 * @return  ARMBaseDAO
	 */
	protected function getDAO( $alias = "" ){
		throw new ErrorException( "Implemet 'protected function getDAO' on " . get_called_class()  );
	}
	
	/**
	 * verifica se tem data
	 * @return boolean
	 */
	protected function hasData(){
		//é false até que uma propriedade me diga o contrário
		$return = FALSE;
		foreach ( $this->VO as $key=>$value){
			if( !is_null( $value ) )
				return TRUE;
		}
		return $return;
	}

	function setId(  $id , $autoSearch = TRUE ) {
		
		$var = ARMDataHandler::forceInt( $id );
		
		if( ! $var > 0 )
			$var = NULL;
		$this->startVO();
		$this->VO->id = $var;

		if( $autoSearch ){
			$return = $this->autoSearch();
			return $return ;
		}
		return NULL;
	}

	public function fetchArray($array, $prefix = ""){
		if(!$this->VO){
			$this->startVO();
		}
		foreach($this->VO as $key=>$value){
			$method = ARMDataHandler::urlFolderNameToMethodName("set_".$key);
			$newValue = ARMDataHandler::getValueByArrayIndex($array, $prefix.$key);

			//se está dando um fetch mas o objeto já tem dados não sobreescreve com NULL o que já tem
			if( is_null( $newValue ) && !is_null($value) )
				continue;

			//se tem um set ele chama o metodo da entity
			if( method_exists( $this, $method ) ){
				if( $method == "setId" ){
					$this->$method( $newValue , FALSE );
				}else{
					$this->$method( $newValue );
				}
				continue;
			}
			//se não tem set, seta direto na VO
			$this->VO->$key = $newValue;
		}
	}
	public function fetchObject($obj){
		$this->getVO();
		if(!$this->VO){
			return;
		}
		$std_VO = $this->VO ;
		if(!$this->hasData()){
			//se não tem nada como data, pega só os dados enviados para uso como update e commit
			$std_VO = new stdClass();
		}
		foreach($this->VO as $key=>$value){
			$obj_value = ARMDataHandler::getValueByStdObjectIndex( $obj , $key );
			//if( $obj_value  )
				$std_VO->$key = $obj_value ;
		}
		$this->VO = $std_VO;
	}

	protected function autoSearch(){
		$retorno = $this->setVoById( $this->VO->id , $this->VO, $this->getDAO() ) ;
		$this->fetchObject( $this->VO );
		return $retorno;
	}
	function validate() {
		$resut = new ARMReturnResultVO();
		$resut->success = true ;
		return $resut ;
	}

	/**
	 * 
	 * @param mixed $VO // pode ser um array de VO
	 * @param object DAOinstance $DAO
	 * @param ARMReturnResultVO $ReturnResultVO // referencia usada pelo retorno da entity
	 * @param string $DAOMethodName // nome do método usado pela DAO
	 * @return boolean
	 */
	protected function commitVO( $VO , $DAO ,  ARMReturnResultVO &$ReturnResultVO , $DAOMethodName = "commitVO" ){
// 		ARMDebug::print_r($ReturnResultVO );
		if( is_array( $VO ) ){
			foreach( $VO as $singleVO)
				$this->commitVO( $singleVO , $DAO , $ReturnResultVO , $DAOMethodName ) ;
			
		} else {
			$ReturnDataVO = $DAO->$DAOMethodName( $VO ) ;
		}
		
		$ReturnResultVO->appendMessage( $this->resultHandler( $ReturnDataVO )->array_messages );
		
		if( $ReturnDataVO->success == FALSE ) {
			$ReturnResultVO->success = FALSE ;
			return FALSE ;
		}
		return TRUE ;
	}

	/**
	 * 
	 * @param ARMReturnDataVO $ReturnDataVO
	 * @return ARMReturnResultVO
	 */
	public function resultHandler( ARMReturnDataVO $ReturnDataVO ){
		 	$return = new ARMReturnResultVO();
		 	$return->success = $ReturnDataVO->success ;
		 	$return->result = $ReturnDataVO->result ;
			
			if( $return->success == FALSE )	{
				$return->addMessage( $ReturnDataVO->error_message );
			}
				 	
		 	return $return;
	}
	
	/**
	 * seta direto na referencia do $VO enviado os dados obtidos através da $DAO 
	 * @param mixed $search_value //valor a ser buscado, normalmente é o ID para o selectById
	 * @param object|array $VO
	 * @param object DaoInstance $DAO
	 * @param boolean $is_array // envie true para 
	 * @param string $DAOMethodName // nome do método usado pela DAO, normalmente isso é diferente quando is_array é true
	 * @return boolean
	 */
	protected function setVoById( $search_value , &$VO , $DAO , $is_array = FALSE , $DAOMethodName = "selectById" ) {
		
		if( ( is_numeric( $search_value ) && !$search_value> 0 ) || ( is_string( $search_value ) && !strlen( $search_value ) > 0) )
			return FALSE ;

		$ReturnDataVO = $DAO->$DAOMethodName( $search_value ) ;
			
		if( $ReturnDataVO->success === TRUE && sizeof( $ReturnDataVO->result ) > 0 ){
			if( !$is_array ) {
				$VO = $ReturnDataVO->result[0] ;
			} else{
				$VO = $ReturnDataVO->result ;
			}
			return TRUE ;
		}
		return FALSE ;
	}
	
	public function getVO(){
		if( !$this->VO)
			$this->startVO();
		return $this->VO;
	}

	/**
	 * @param bool $raw
	 * @return stdClass
	 */
	public function toStdClass( $raw = FALSE ){
		$obj = new stdClass();
		if(!$this->VO){
			return $obj;
		}
		//pega tudo que tem na VO setada
		foreach($this->VO as $key=>$value){
			$method = ARMDataHandler::urlFolderNameToMethodName("get_".$key);
			//se tem o metodo get, executa o get e pega
			if(method_exists( $this , $method ) && $raw !== TRUE ){
				$obj->$key = $this->$method();
				continue;
			}
			//se não existe o metodo, pega da vo existente
			$obj->$key = $value;
		}
		//agora vai somar o que tem de metodo get
		$methods = ARMClassHandler::getMethods( $this , ReflectionMethod::IS_PUBLIC ) ;
		$methods = $this->__filterMethods( $methods ) ;
		foreach( $methods as $method ){
			//metodo pra propriedade
			$prop = ARMDataHandler::classNameToUrlFolderName( preg_replace("/^get/", "", $method ) ) ;
			//verifica sejá nao foi feito pela propriedade
			if( ! isset( $obj->$prop ) ){
				//ainda nao foi
				$obj->$prop = $this->$method() ;
			}
		}
		return $obj;
	}

	/**
	 * a idéia é retornar só os metodos gets permitidos
	 * @param $arrayMethods array
	 * @return array
	 */
	private function __filterMethods( $array ){
		$arrayMethods = array() ;
		if( ! $array ){
			return array() ;
		}
		for( $i = 0; $i < count( $array ); $i++ ){
			$method = trim( $array[ $i ] ) ;
			$get = strpos( $method , "get" ) ;
			if( $get === FALSE || $get !== 0 ){
				//não começa com get, tira
				continue ;
			}
			if( in_array( $method, $this->__denyMethods ) ){
				//negado, tira
				continue ;
			}
			$arrayMethods[] = $method ;
		}
		return $arrayMethods ;
	}
	/**
	 *
	 * método que retorna 
	 * @return array
	 */
	public function getFieldData( $alias = "" ){
		$return = array();
		$VO = $this->toStdClass();
		foreach( $VO as $key => $value ){
			$return[$alias . $key ] =  new FormFieldInfoVO( $alias . $key , $value ); 
		}
		return (object) $return ;
	}
	
}