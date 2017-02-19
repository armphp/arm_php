<?php
echo "Deprecated class ARMEntityRelationship";
exit();
/**
 * 
 * @author alanlucian & renatomiawaky
 *
 */
class ARMEntityRelationship{
	
	const RELATIONSHIP_1TO1 = "ONE_TO_ONE" ; 
	const RELATIONSHIP_1TON = "ONE_TO_MANY" ; 
	const RELATIONSHIP_NTON = "MANY_TO_MANY" ; 
	
	const RELATIONSHIP_ALIAS_GLUE = "_" ; 
	
	protected $type;
	
	protected $FilterVO ;
	
	protected $targetModule;
	
	protected $target_fk;
	
	protected $manyToManyDAO;
	
	protected $data;
	
	public function __construct($type = ARMEntityRelationship::RELATIONSHIP_1TO1 ,  ARMBaseDataModuleAbstract $targetModule = NULL ,  ARMBaseDAO $manyToManyDAO = NULL , $target_fk = NULL ) {
		
		$this->setType( $type ) ;
		
		$this->setTargetModule( $targetModule ) ;
		
		if( $manyToManyDAO  )
			$this->setManyToManyDAO( $manyToManyDAO ) ;
		if( $target_fk )
			$this->setTargetFK( $target_fk ) ;		
	}
	
	public function load(){
		$result  = new ARMReturnResultVO();
		$result->success = TRUE ;
		
		if( is_null( $this->FilterVO ) || is_null( $this->targetModule ) ){
			$result->success = FALSE ;
			$result->addMessage( Translation::text(  "ARMEntityRelationship - Dados insuficientes para busca" ) ) ;
			return $result ;
		} 
		
		
		if( $this->type === ARMEntityRelationship::RELATIONSHIP_1TO1 || $this->type === ARMEntityRelationship::RELATIONSHIP_1TON) {
			$ReturnDataVO = $this->targetModule->getDAO()->selectByVO( $this->FilterVO ) ;
			if ( $ReturnDataVO->success === true && sizeof( $ReturnDataVO->result ) > 0 ){
					$this->data =  ( $this->type === ARMEntityRelationship::RELATIONSHIP_1TO1 )? $ReturnDataVO->result[0] : $ReturnDataVO->result ;
			} 
		} elseif ( $this->type === ARMEntityRelationship::RELATIONSHIP_NTON ) {
				
			if( is_null( $this->manyToManyDAO ) || is_null( $this->target_fk ) ){
				$result->success = FALSE ;
				$result->addMessage( Translation::text(  "ARMEntityRelationship - Dados insuficientes para busca N-N" ) ) ;
				return $result ;
			}
			
			$ManyReturnDataVO = $this->manyToManyDAO->selectByVO( $this->FilterVO ) ;
			if ( $ManyReturnDataVO->success === true && sizeof( $ManyReturnDataVO->result ) > 0 ){
				
				foreach( $ManyReturnDataVO->result  as $resultObject ){
					$fk_name = $this->target_fk ;
					$ReturnDataVO = $this->targetModule->getDAO()->selectById( $resultObject->$fk_name ) ;
					if(  $ReturnDataVO->success === TRUE && sizeof( $ReturnDataVO->result ) > 0 ) {
						foreach( $ReturnDataVO->result  as $resultItem )
							$this->addMultiData( $resultItem ) ;
						
					} elseif( $ReturnDataVO->success  === FALSE ) {
						$result = FALSE ;
						$result->addMessage( $ReturnDataVO->getCode() ) ;
					}
				}
				
			}
		}
		
		$result->result = $this->data ;
		return $result ;
	}

	/**
	 * Popupla a relacao com os dados enviados no array podendo ser bidimensional quando 1N ou NN 
	 * 
	 * @param array $array_data
	 * @param string $alias  //alias da relação na Entity onde ela se encontra.
	 */
	public function fetchArray( $array_data, $alias = "" ){
		$VO = $this->targetModule->getVO();
		
		//remove dados desnecessários p/ popular a VO com o que ela precisa
		$array_data = $this->filterVOKeys($VO, $array_data, $alias ) ;
		
		
		
		$data = array( $VO );
		
		foreach( $array_data as $key => $value ) {
			
			$key = preg_replace("/^". $alias . self::RELATIONSHIP_ALIAS_GLUE . "/", "" , $key );
			
			if( is_array( $value ) ){
				for( $i = 0 ; $i < sizeof( $value ) ; $i++ ){
					if( !isset( $data[$i] ) )
						$data[$i] = $VO ;
					
					$data[$i]->$key = $value ;
				}
			} else {
				$data[0]->$key = $value ;
			}
			
		}
		if( sizeof( $data ) == 1 ){
			
			$this->data = $data[0];
			return NULL;
		}
		
		$this->data = $data ;
	}
	
	/**
	 * Metodo que filtra array para conter apenas os dados que são relativos a VO enviada
	 * @param object $VO //objeto da VO p/ filtrar os dados do array
	 * @param array $array_data
	 * @param string $alias
	 * @return array
	 */
	private function filterVOKeys( $VO , $array_data, $alias ) {
		$array_vo_keys = array();
		foreach( $VO as $key=>$value ){
			$key_with_alias = $alias . self::RELATIONSHIP_ALIAS_GLUE . $key ;
			if( isset( $array_data[ $key_with_alias ] ) ){
				$array_vo_keys[ $key_with_alias] = $array_data[ $key_with_alias ] ;
			}
		}
		return $array_vo_keys ;
	}
	
	protected function addMultiData( $dataItem ){
		if( !is_array( $this->data ) )
			$this->data = array() ;
		
		$this->data[] = $dataItem ;
	}
	
	/**
	 * 
	 * @return Ambigous <multitype:, unknown, multitype:unknown >
	 */
	public function getData(){
		return $this->data;
	}
	
	/**
	 * Funcão para aletar um valor dentro do DATA da relationship quando ela ja foi populada ou pelo fetch ou pelo load, mas precsa ser modificada.
	 * @param string $key
	 * @param mixed $value
	 */
	public function changeDataValue(  $key , $value ){
		if( is_array($value) ){
			for( $i = 0 ; $i <= count( $value ) ; $i++ ){
				$this->data[ $i ]->$value = $value[ $i ] ;
			}
			
			return;
		}
		$this->data->$key = $value ;
	}
	
	/**
	 * 
	 * @return ARMBaseDataModuleAbstract
	 */
	public function getModule(){
		return $this->targetModule;
	}
	
	public function getType(){
		
	}
	
	public function setType( $type ) {
		switch ( $type ){
			case ARMEntityRelationship::RELATIONSHIP_1TO1 :
			case ARMEntityRelationship::RELATIONSHIP_1TON :
			case ARMEntityRelationship::RELATIONSHIP_NTON :
				$this->type = $type ;
				break;
			default:
				return FALSE;
				break;
		}
		return TRUE;
	}
	
	/**
	 * Nome do campo do targetDAO na tabela intermediária quando for RELATIONSHIP_NTON
	 * @param unknown $target_fk
	 */
	public function setTargetFK( $target_fk ){
		$this->target_fk = $target_fk ;
	}
	public function setFilter(  $filterVO ) {
		$this->FilterVO = $filterVO ;
	}
	
	public function setTargetModule( ARMBaseDataModuleAbstract $module ) {
		$this->targetModule = $module ;
	}
	
	public function setManyToManyDAO(  ARMBaseDAO $DAO ) {
		$this->manyToManyDAO = $DAO ;
	}
}