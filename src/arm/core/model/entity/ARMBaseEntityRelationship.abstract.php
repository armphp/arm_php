<?php

/**
 * 
 * @author renatomiawaki
 *
 */
abstract class ARMBaseARMEntityRelationship implements ARMEntityRelationshipInterface{
	/**
	 * fielname do pai na tabela da relação
	 * @var string 
	 */
	protected $fk_parent_name;
	/**
	 * aqui é o valor do "id" do pai
	 * @var unknown
	 */
	protected $fk_parent_value;
	/**
	 * 
	 * @var ARMBaseDataModuleAbstract
	 */
	protected $target_module;
	/**
	 * 
	 * @var ARMBaseEntityAbstract or array of ARMBaseEntityAbstract
	 */
	protected $data;
	
	const ALIAS_GLUE = "_";
	protected function setTargetModule(ARMBaseDataModuleAbstract $module){
		$this->target_module = $module;
	}
	/**
	 * 
	 * @return ARMBaseDataModuleAbstract
	 */
	protected function getTargetModule(){
		if(!$this->target_module){
			var_dump( $this );
			throw new ErrorException("Módulo não existente");
		}
		return $this->target_module;
	}
	protected function setParentFkName($fkName){
		$this->fk_parent_name = $fkName;
	}
	/**
	 * Caso precise que seja de outro modo, sobreescreva o metodo
	 * Nesse caso ele verifica se é array e faz o fetch contando que cada item do array é um objeto
	 * @see ARMEntityRelationshipInterface::fetchObject()
	 */
	public function fetchObject( $object_or_array_of_object ){
		if(is_array( $object_or_array_of_object )){
			$this->doFetchArrayOfObject( $object_or_array_of_object );
			return;
		}
		$this->data = $this->getVOByObject( $object_or_array_of_object );
	}
	protected function doFetchArrayOfObject( $array ){
		$this->data = array();
		foreach( $array as $item ){
			$this->data[]  = $this->getVOByObject($item);
		}
// 		$this->data = $data;
	}
	/**
	 * 
	 * @param object $object
	 * @return object VO object of this started module 
	 */
	protected function getVOByObject(  $object ){
		$VO = $this->getTargetModule()->getVO();
		foreach( $VO as $key => $value ){
			$VO->$key = ARMDataHandler::getValueByStdObjectIndex($object, $key);
		}
		return $VO;
	}
	
	
	protected function filterArrayForFetch( $array_data , $alias = "" ){
		
// 		ARMDebug::li( get_called_class() . __METHOD__ . " PARAMS  alias: {$alias}" );
// 		ARMDebug::print_r( $array_data);
		
		$VO_properties = get_object_vars( $this->getTargetModule()->getVO() ) ;
		
		$alias = $alias . ARMBaseARMEntityRelationship::ALIAS_GLUE ;
		
		$return = array();
		
		$VO = $this->getTargetModule()->getVO();
		foreach( $VO_properties as $key=>$value ){
			
			$vo_key_name = $alias . $key ;
			
// 			ARMDebug::li( get_called_class() . __METHOD__ . "  BUSCANDO NO ARRAY: $vo_key_name" );
// 			ARMDebug::print_r( $array_data );
// 			ARMDebug::print_r( $VO_properties );
			
			if( array_key_exists( $vo_key_name , $array_data ) ) {
				$return[ $key] = $array_data[ $vo_key_name ] ;  
			}
		}
// 		ARMDebug::li( get_called_class() . __METHOD__ . " <hr><hr><hr>" );
		return $return ; 
	}
	
	/**
	 * Popupla a relacao com os dados enviados no array podendo ser bidimensional quando 1N ou NN
	 *
	 * @param array $array_data
	 * @param string $alias  //alias da relação na Entity onde ela se encontra.
	*/
	public function fetchArray( $array_data, $alias = "" ){
// 		if(!$this->data){
// 			//se tiver valor para fk setado, faz o load primeiro antes do fetch
// 			if( $this->fk_parent_value ){
// 				$this->load( $this->fk_parent_value );
// 			}
// 		}
		$my_data = $this->filterArrayForFetch( $array_data , $alias ) ;
		
// 		ARMDebug::li( get_called_class() .  __METHOD__ ." -> fetchArray MyData for {$alias} "  );
// 		ARMDebug::print_r( $my_data );
		
		// se nao tem nada da relação pra dar fetch para com isso aqui p. evitar erros
		if( sizeof( $my_data) == 0 ){
			return NULL;
		}
		
		if($this->isArrayDataArray($my_data)){
// 			ARMDebug::li( get_called_class() . " -> fetchArray -> isArrayDataArray "  );
			return $this->fetchArrayOfArray($my_data, $alias);
		}
		
		
		return $this->fetchArraySingle($my_data, $alias);
		
	}
	protected function fetchArraySingle($array_data, $alias = ""){
		$this->data = $this->getPopulatedEntity($array_data)->getVO();
	}
	/**
	 * 
	 * @param array $array_to_fetch
	 * @return ARMBaseEntityAbstract
	 */
	protected function getPopulatedEntity($array_to_fetch, $alias = "" ){
// 		ARMDebug::li( get_called_class() . " -> " .__METHOD__  . " ( alias = " . $alias . " ) "  );
// 		ARMDebug::print_r( $array_to_fetch );
			
		
		$entity = $this->getTargetModule()->getEntity();
		
		
		$entity->fetchArray($array_to_fetch, $alias);
		
// 		ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " fez o fetch a o erro está por aqui?");
// 		ARMDebug::print_r( $entity );
		
		return $entity;
	}
	/**
	 * 
	 * @param array $array_data multidimensinal
	 */
	protected function fetchArrayOfArray($array_data, $alias = ""){
		
		//o fetch array reescreve o valor de data local";
// 		ARMDebug::li( get_called_class() . "-> fetchArrayOfArray ( alias = " . $alias . " ) "  );
// 		ARMDebug::print_r( $array_data  );
		
		$array_simple_vo = array();
		
		$number_of_sent_data = count( current($array_data) );
		
		foreach( $array_data as $key =>$value )
		for( $i = 0 ; $i< $number_of_sent_data ; $i++  ){
			
			if( !isset( $array_simple_vo[$i] ) ){
				$array_simple_vo[$i] = array();
			}
			$array_simple_vo[$i][$key] = $value[$i] ;
			
		}
		
// 		ARMDebug::li( get_called_class() . "-> fetchArrayOfArray ( verificando se o problema ta antes daqui parece que até aqui ta tudo certo) "  );
// 		ARMDebug::print_r( $array_simple_vo   );
		
		foreach( $array_simple_vo as $item ){
			$entity = $this->getPopulatedEntity( $item );
			
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " fez o fetch e a entity é ?" );
// 			ARMDebug::print_r( $entity );
// 			O problema tá aqui ! ! ! !
			
			$VO 	= $entity->getVO();
			
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " o VO que a entity me dá é ?" );
// 			ARMDebug::print_r( $VO );
				
			
			$this->pushDataItem($VO);
		}
		
// 		ARMDebug::li( get_called_class() . "-> fetchArrayOfArray ( terminou ) "  );
// 		ARMDebug::print_r( $this->data  );
		
		
	}
	/**
	 * encapsulei o push para caso alguma classe precise filtrar o que é inserido
	 * @param object $data_item
	 */
	protected function pushDataItem( $data_item ){
		
		$this->filterNullFieldsOfItem( $data_item );
		
		$this->data[] = $data_item;
		
	}
	private function filterNullFieldsOfItem(&$data_item){
		foreach($data_item as $key=>$value ){
			if($data_item->$key === NULL){
				unset($data_item->$key);
			}
		}
	}
	/**
	 * 
	 * @param unknown $array_data
	 * @param string $alias
	 * @return boolean
	 */
	protected function isArrayDataArray($array_data){
		foreach($array_data as $key=>$value){
			if(is_array($value)){
				return true;
			}
		}
		return false;
	}
	/**
	 *
	 * @return Ambigous <multitype:object, multitype:array >
	*/
	public function getData(){
		
		return $this->data;
		$entity = $this->target_module->getEntity();
		if(is_array($this->data)){
			$array = array();
			foreach($this->data as $data){
				$entity->fetchObject( $this->data );
				$array[] = $entity->getVO();
			}
			return $array;
		}
		
		$entity->fetchObject( $this->data );
		
		return $entity->getVO();
	}
	
	protected function getMyFields(  $has_many = FALSE  , $alias = "" ){
		
		$return  = array() ;
		
		$relation_value = $this->getData();
		
		$entity = $this->getTargetModule()->getEntity();
		$entityVO = $entity->getVO();
		
		foreach ( $this->getTargetModule()->getVO() as $key => $value ) {
			$getMethod = ARMDataHandler::urlFolderNameToMethodName("get_".$key);
			//se tem o metodo get na entiry, vai usar ele pra jogar no form.
			$useGet  =  method_exists( $entity, $getMethod ) ;
			
			$relation_key =  trim(ARMDataHandler::forceString( $alias . ARMBaseARMEntityRelationship::ALIAS_GLUE . $key ));
		
			if( is_null( $relation_value ) ){
				$return[ $relation_key ] = new FormFieldInfoVO(  $relation_key , ""  , $has_many)  ;
				continue;
			}
		
			if( is_array( $relation_value ) ) {
				$relation_value_array = array();
				for( $i = 0 ;  $i<count($relation_value ) ; $i++){
					if( isset( $relation_value[$i]->$key ) ){
						$relation_value_array[$i] = $relation_value[$i]->$key;
						if( $useGet ){
							$entityVO->$key =  $relation_value_array[$i]  ;
							$relation_value_array[$i]  = $entity->$getMethod( );
						}
					}
				}
				$return[ $relation_key ] = new FormFieldInfoVO(  $relation_key , $relation_value_array , $has_many) ;
				continue ;
			}
// 			ARMDebug::li( "{$relation_key} has : " . $relation_value->$key );
			if( $useGet ){
				$entityVO->$key =  $relation_value->$key  ;
				$relation_value->$key  = $entity->$getMethod();
			}
			
			$return[ $relation_key ] =  new FormFieldInfoVO(  $relation_key , $relation_value->$key , $has_many );
		}
			
		return $return ;
		
	}
	
}