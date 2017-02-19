<?php

/**
 * 
 * @author alanlucian & renatomiawaky
 *
 */
class ARMEntityRelationshipNtoN extends ARMBaseARMEntityRelationship {
	
	
	private $fk_related_name; 
	
	/**
	 * 
	 * @var  ARMBaseDAO
	 */
	private $broker_dao;
	
	/**
	 * Define a relação entre 2 entidades ( tabelas do banco a grosso modo) N pra N  usando uma tabela relacional.
	 * Usando como exemplo a relação de tabelas USER  -> USER_ADDERSS <- ADDRESS
	 * @param string $fk_parent_name    	nome do campo da entidade na tabela de relacao ( Ex: user_id na USER_ADDRESS )
	 * @param ARMBaseDataModuleAbstract $targetModule  	módulo do alvo da relação ( Ex: AddressModule )
	 * @param string $fk_related_name 		nome do campo da relação na tabela relacional (  Ex: address_id na USER_ADDRESS )
	 * @param  ARMBaseDAO $broker_dao			DAO da tabela relacional ( Ex: UserAddressDAO )
	 */
	public function __construct(  $fk_parent_name  , ARMBaseDataModuleAbstract $targetModule, $fk_related_name,  ARMBaseDAO $broker_dao ) {
		$this->setParentFkName( $fk_parent_name );
		$this->setTargetModule( $targetModule );

		$this->fk_related_name = $fk_related_name;
		$this->broker_dao = $broker_dao;
	}
	
	
	public function load( $fk_parent_value ){
		$result  = new ARMReturnResultVO();
		$result->success = TRUE ; // por enquanto tá tudo certo
		
		$fk_parent_name 	= $this->fk_parent_name ;
		$fk_related_name 	= $this->fk_related_name ;
		
		$broker_filter_VO = new stdClass();
		$broker_filter_VO->$fk_parent_name = $fk_parent_value ;
		
		
		
		$brokerReturnDataVO = $this->broker_dao->selectByVO( $broker_filter_VO ) ;
		if ( $brokerReturnDataVO->success === true && sizeof( $brokerReturnDataVO->result ) > 0 ){
			$this->data = array();
			foreach( $brokerReturnDataVO->result  as $resultObject ){
				
				$ReturnDataVO = $this->getTargetModule()->getDAO()->selectById( $resultObject->$fk_related_name ) ;
				
// 				ARMDebug::ifli( get_called_class() . " " . __METHOD__ . "  " . __LINE__ );
				
// 				ARMDebug::ifPrint( $ReturnDataVO );
				
				
				if(  $ReturnDataVO->success === TRUE && sizeof( $ReturnDataVO->result ) > 0 ) {
					foreach( $ReturnDataVO->result  as $resultItem ){

// 						ARMDebug::li( get_called_class() . __METHOD__ . "  " . __LINE__ );
// 						ARMDebug::print_r( $resultItem );
						
						$entity = $this->target_module->getEntity();
						$entity->fetchObject( $resultItem ) ;
						
						
						$this->data[] = $entity->getVO();
					}
					
				} elseif( $ReturnDataVO->success  === FALSE ) {
					$result = FALSE ;
					$result->addMessage( $ReturnDataVO->getCode() ) ;
				}
			}
		}
		
// 		ARMDebug::ifli( get_called_class() . " " . __METHOD__ . "  " . __LINE__ );
		
// 		ARMDebug::ifPrint( $this->data );
		
		$result->result = $this->data ;
		
		return $result ;
	}

	
	public function commit( $fk_parent_value, $validate = FALSE ){
		$this->fk_parent_value = $fk_parent_value ;
		$result  = new ARMReturnResultVO();
		$result->success = TRUE ; // por enquanto tá tudo certo

		$fk_parent_name 	= $this->fk_parent_name ;
		$fk_related_name 	= $this->fk_related_name ;
		
		$entity = $this->target_module->getEntity();
		
// 		ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " COmitar: " );
		
// 		var_dump($this->data);
// 		die;
		
		if( is_null( $this->data) )
			return $result;
		
		foreach ( $this->data as &$targetVO ){
			
// 			ARMDebug::print_r( $targetVO );
			
			$entity->fetchObject( $targetVO );
// 			ARMDebug::print_r($entity->getLinkVO());
			$e_commit_returnResult = $entity->commit( TRUE );
			
// 			var_dump( $e_commit_returnResult );
			
// 			ARMDebug::print_r($e_commit_returnResult);
			
			$targetVO = $entity->getVO();
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " o VO que tem que ter ID pra popular o fk_related_name-> " . $fk_related_name  );
// 			ARMDebug::print_r( $targetVO );
			
// 			agora tem q ver o ID e se o brokerjá tem essa relação dexa queto
			$target_pk_name =  $this->target_module->getDAO()->getPrimaryKey();
			
			$broker_VO  =  new stdClass();
			$broker_VO->$fk_parent_name 	= $this->fk_parent_value ;
			
// 			ARMDebug::print_r( $targetVO );
			
			$broker_VO->$fk_related_name	= $targetVO->$target_pk_name ;
			
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " Broker_VO" );
// 			ARMDebug::print_r( $broker_VO );
				
			
			$brokerReturnDataVO = $this->broker_dao->selectByVO( $broker_VO );
			
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " fez o brokerReturnDataVO " );
// 			ARMDebug::print_r( sizeof( $brokerReturnDataVO->result )   );
			
			
			// se a relação já existe, deixa lá queitinha, só 
			if ( $brokerReturnDataVO->success === true && sizeof( $brokerReturnDataVO->result ) == 0 ){
				$insert_resultVO =  $this->broker_dao->insertVO( $broker_VO );
				if( $insert_resultVO == FALSE ){
					$result->success = FALSE ;
					$result->addMessage( $insert_resultVO->getCode() ) ;
				}
			}
			
		}
		
		return $result;
		
	}
	public function getFields( $alias = "" ){
		return $this->getMyFields( TRUE, $alias );
	}
}