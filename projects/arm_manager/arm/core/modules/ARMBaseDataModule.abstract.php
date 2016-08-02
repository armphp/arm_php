<?php

/**
 * Classe para ajudar a encapsular o nome da ModelGateway e nào espalhar o uso de DAO e VO por todo o projeto
 * centralizando todos os tipos de acesso em um Module e assim facilitando a centralização
 *
 * aqui só os comando básicos
 *
 * Class ARMBaseDataModuleAbstract
 */
abstract class ARMBaseDataModuleAbstract extends ARMBaseModuleAbstract {
	/**
	 * //cash de orders info
	 * @var ARMDictionary[]
	 */
	protected $__cachEntityes ;

	public function getAlias(){
		return "_" . ARMDataHandler::classNameToUrlFolderName(str_replace("DataModule", "", get_called_class()));
	}
	public function getAll( $limit = NULL , $ofset = NULL  ){

		$DAO = $this->getModelGateway()->getDAO();

		$rt = $DAO->selectAll( $limit, $ofset );

		return $rt ;

	}
	public function getByVO( $vo, $limit = NULL , $ofset = NULL   ){
		$DAO = $this->getModelGateway()->getDAO();
		$rt = $DAO->selectByVO( $vo, $limit, $ofset );
		return $rt ;
	}
	/**
	 * retorna o tostdClass que vem da entity
	 * @param $id
	 * @return stdClass
	 */
	public function getStdById( $id ){
		$entity = $this->getEntityById( $id ) ;
		return $entity->toStdClass() ;
	}
	public function getByFieldId( $field, $id ){
		$returnData = $this->getModelGateway()->getDAO()->selectByField($id, $field) ;
		if( $returnData->hasResult() ){
			return $returnData->result[ 0 ];
		}
		return NULL ;
	}

	/**
	 * @param $id
	 * @return ARMBaseEntityAbstract
	 */
	public function getEntityById( $id ){
		$entity = $this->getCashEntity()->get( $id ) ;
		if( ! $entity ){
			$entity = $this->getModelGateway()->getEntity() ;
			$entity->setId( $id ) ;
			$this->getCashEntity()->set( $id , $entity ) ;
		}
		return $entity ;
	}

	/**
	 * Para pegar o dictionary correto para essa classe
	 * @return ARMDictionary
	 */
	private function getCashEntity(){
		if( ! $this->__cachEntityes ){
			$this->__cachEntityes = array() ;
		}
		if( ! isset( $this->__cachEntityes[ get_called_class() ] ) ){
			$this->__cachEntityes[ get_called_class() ] =  new ARMDictionary() ;
		}
		return $this->__cachEntityes[ get_called_class() ] ;
	}

	/**
	 * retorna o getLinkVO que vem da entity
	 * @param $id
	 * @return stdClass
	 */
	public function getVOById( $id ){
		$entity = $this->getModelGateway()->getEntity() ;
		$entity->setId( $id ) ;
		return $entity->getVO() ;
	}

	public function activeById( $id ){
		return $this->getModelGateway()->getDAO()->active( $id ) ;
	}
	public function deactiveById( $id ){
		return $this->getModelGateway()->getDAO()->deactive( $id ) ;
	}
	public function deleteById( $id ){
		return $this->getModelGateway()->getDAO()->delete( $id ) ;
	}
	public function deleteByVO( $vo , $limit = NULL ){
		return $this->getModelGateway()->getDAO()->deleteByVO( $vo , $limit ) ;
	}

	/**
	 * @return ARMModelGatewayInterface
	 */
	abstract function getModelGateway() ;
}