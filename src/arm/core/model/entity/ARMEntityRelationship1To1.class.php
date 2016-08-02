<?php
/**
 * Relação de 1 pra 1 entre entidades
 * @author Alan Lucian
 *
 */
class ARMEntityRelationship1To1 extends ARMBaseARMEntityRelationship{
	
	/**
	 * ex: se o id do user na tabela de relação se chama user_id, então envie "user_id" no parametro $fk_parent_name
	 * @param string $fk_parent_name nome do atributo da chave estrangeira na tabela de relação
	 * @param ARMBaseDataModuleAbstract $targetModule
	 */
	public function __construct($fk_parent_name, ARMBaseDataModuleAbstract $targetModule){
		$this->setParentFkName($fk_parent_name);
		$this->setTargetModule($targetModule);
	}
	/**
	 * deve carregar os dados da relação baseado no filtro
	 *
	 * @param unknow $fk_parent_value é o valor do id no parent
	 * @return ARMReturnResultVO
	 */
	public function load( $fk_parent_value ){
		
// 		ARMDebug::li( get_called_class() . __METHOD__ . " fk_parent_value = $fk_parent_value");
		
		$ReturnResultVO = new ARMReturnResultVO();
		
		$this->setParentValue($fk_parent_value);
		
		//seleciona baseado no filtro pela chave estrangeira e valor da mesma
		$TargetDao 	= $this->getTargetModule()->getDAO();
		$FilterVO 	= $this->getFilterVO();
		
// 		ARMDebug::li( get_called_class() . __METHOD__ . " FilterVO:");
// 		ARMDebug::print_r( $FilterVO );
		
		$ReturnDataVO = $TargetDao->selectByVO($FilterVO);
		$ReturnResultVO->success = $ReturnDataVO->success;
		
// 		ARMDebug::li( get_called_class() . __METHOD__ . " ARMReturnDataVO:");
// 		ARMDebug::print_r( $ReturnDataVO );
		
		$this->fetchLoadedResult( $ReturnDataVO );
		
		return $ReturnResultVO;
	}
	/**
	 * 
	 * @param ARMReturnDataVO $RetunrDataVO
	 */
	protected function fetchLoadedResult( ARMReturnDataVO $RetunrDataVO ){
		if($RetunrDataVO->success){
			if($RetunrDataVO->result && count($RetunrDataVO->result) > 0){
				$this->fetchObject($RetunrDataVO->result[0]);
			}
		} else {
			//tratando o resultado de Data
			$ReturnResultVO = $this->getTargetModule()->getEntity()->resultHandler($RetunrDataVO);
		}
	}
	/**
	 * Retorna um stdClass com o filtro para fazer a busca no selectByVO
	 * @return stdClass
	 */
	protected function getFilterVO(){
		$FilterVO 	= new stdClass();
		$fk_key 	= $this->fk_parent_name;
		$FilterVO->$fk_key = $this->fk_parent_value;
		return $FilterVO;
	}
	protected function setParentValue($value){
		$this->fk_parent_value = $value;
		if( $this->data ){
			$fk_key 				= $this->fk_parent_name;
			$this->data->$fk_key 	= $this->fk_parent_value;
		}
	}
	/**
	 * 
	 * @param  unknow 	$fk_parent_value é o valor do id no parent
	 * @param  boolean 	$validate
	 * @return ARMReturnResultVO
	*/
	public function commit( $fk_parent_value, $validate = FALSE ){
		
// 		ARMDebug::li( get_called_class() . " -> " .__METHOD__   . "  fk_parent_value = {$fk_parent_value} " );
		$ReturnResultVO = new ARMReturnResultVO();
		$ReturnResultVO->success = TRUE ;
		
		if( $this->data ){
			//seta o id do mesmo
			$this->setParentValue($fk_parent_value);
			
			$fk_parent_name = $this->fk_parent_name ;
			
			$this->data->$fk_parent_name = $this->fk_parent_value ;
			$target_dao = $this->getTargetModule()->getDAO();
			$verificationVO = new stdClass();
			$verificationVO->$fk_parent_name = $this->fk_parent_value ;
			//verifica se o ID enviado já existe p/ dar update ou insert
			$ReturnDataVO = $target_dao->selectByVO( $verificationVO );
			
			$entity = $this->getTargetModule()->getEntity();
			
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . "  verifica_se_ja_tem = {$fk_parent_value} " );
// 			ARMDebug::print_r($ReturnDataVO);
			
			
			if( $ReturnDataVO->success == TRUE && sizeof( $ReturnDataVO->result ) > 0){ 
// 				ARMDebug::li( get_called_class() . " -> " .__METHOD__   . "  Já tem COMMITA! = {$fk_parent_value} " );
				//Commita, é sinônimo que o ID enviado já tem no banco

				// 				ARMDebug::li( get_called_class() . " -> " .__METHOD__   . "  entity:  " );
// 				ARMDebug::print_r( $entity );
// 				ARMDebug::print_r( $this->data );
				
				$entity->fetchObject( $this->data );
				$RelReturnResultVO = $entity->commit( $validate );
				
				if( $RelReturnResultVO->success == FALSE ){
					$ReturnResultVO->success = FALSE ;
					$ReturnResultVO->appendMessage( $RelReturnResultVO->array_messages );
				}
				
				return $ReturnResultVO ;
			}
			
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . "  VAI ARMNSERRIRInterface ! = {$fk_parent_value} " );
			$ReturnDataVO = $target_dao->insertVO( $this->data );
			
			$newReturnResultVO = $entity->resultHandler($ReturnDataVO) ;
// 			ARMDebug::li( get_called_class() . " -> " .__METHOD__   . " resultHandler dos infernos " );
// 			ARMDebug::print_r( $newReturnResultVO);
			
			if( $newReturnResultVO->success == FALSE ){
				$ReturnResultVO->success = FALSE ;
				$ReturnResultVO->appendMessage( $newReturnResultVO->array_messages ) ;
			}
			
		}
		
		
		
		return $ReturnResultVO;
	}
	
	public function getFields( $alias = "" ){
		return $this->getMyFields( FALSE , $alias );
	}
}