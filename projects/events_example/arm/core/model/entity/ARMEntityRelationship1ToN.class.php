<?php
/**
 * Entidade de relação de 1 pra N entre entidades
 * @author Alan Lucian
 *
 */
class ARMEntityRelationship1ToN extends ARMEntityRelationship1To1{
	
	
	/**
	 *
	 * @param ARMReturnDataVO $RetunrDataVO
	 */
	protected function fetchLoadedResult( ARMReturnDataVO $RetunrDataVO ){
		if($RetunrDataVO->success && count($RetunrDataVO->result) > 0 ){
			$this->fetchObject($RetunrDataVO->result);
		} else {
			//tratando o resultado de Data
			$ReturnResultVO = $this->getTargetModule()->getEntity()->resultHandler($RetunrDataVO);
		}
	}
	protected function setParentValue($value){
		$this->fk_parent_value = $value;
		if( $this->data ){
			foreach($this->data as $data){
				$fk_key 				= $this->fk_parent_name;
				$data->$fk_key 			= $this->fk_parent_value;
			}
		}
	}
	/**
	 *
	 * @param  unknow 	$fk_parent_value é o valor do id no parent
	 * @param  boolean 	$validate
	 * @return ARMReturnResultVO
	 */
	public function commit( $fk_parent_value, $validate = FALSE ){
		
		$ReturnResultVO = new ARMReturnResultVO();
		
		if( $this->data ){
			//seta o id do mesmo para todas os itens data envolvidos
			$this->setParentValue($fk_parent_value);
			
			foreach($this->data as $data){
				$entity = $this->getTargetModule()->getEntity();
				$entity->fetchObject( $data );
					
				$ReturnResultVOitem = $entity->commit($validate);
				if( !$ReturnResultVOitem->success ){
					$ReturnResultVO->success = FALSE;
					$ReturnResultVOitem->array_messages;
					$ReturnResultVO->appendMessage($ReturnResultVOitem->array_messages);
				}
			}
		}
		
		return $ReturnResultVO;
	}
	
	public function getFields( $alias = "" ){
		return $this->getMyFields( TRUE, $alias );
	}
}