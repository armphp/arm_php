<?php
/**
 * O getInstance desse módulo tem relação direta com o nome da tabela que o log terá
 * Faz-se isso para evitar tabelas de log muito sobrecarregada para salvar coisas diferentes
 * Então:
 * a tabela é relativa aou prefixo no config + instancia do módulo
 * Entenda a instancia como o tipo de log que quer salvar, ex:
 * ARMLogModule::getInstance("acl")->addLog( ... ) ;//logs relativo a tentativa de login, logins, etc
 * ARMLogModule::getInstance("messages")->addLog( ... ) ;//logs relativos a mensagens
 * ARMLogModule::getInstance("debug")->addLog( ... ) ;//logs para debugs e testes
 *
 * User: renatomiawaki
 * Date: 12/9/13
 * 
 */

class ARMLogModule extends ARMBaseDataModuleAbstract{
	/**
	 * @var ARMLogDAO
	 */
	protected $_daoInstance ;
	/**
	 * @var string
	 */
	protected $_table ;
	/**
	 * prefixo para tabelas de log
	 * @var string
	 */
	protected $_prefix ;
	/**
	 * @var string
	 */
	protected $_alias ;

	/**
	 * @return ArmLogModelGateway
	 */
	function getModelGateway(){
		//$table = $this->getTableName() ;
		//ArmLogDAO::setDefaultAlias( $table ) ;
		return ArmLogModelGateway::getInstance() ;
	}


	/**
	 * @param null $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMLogModule
	 */
	public static function getInstance( $alias = NULL, $useDefaultIfNotFound = TRUE ){
		/** @var $instance ARMLogModule */
		$instance = parent::getInstance( $alias, $useDefaultIfNotFound ) ;
		$instance->setAlias( $alias ) ;
		$table = $instance->getTableName() ;
		ArmLogModelGateway::$current_table = $table ;
		$instance->_daoInstance = ArmLogDAO::getInstance( $table ) ;
		return $instance ;
	}

	/**
	 * @param object $ob
	 */
	public function setConfig( $ob ){
		/* @var $ob ARMLogConfigVO */
		$this->_prefix  = ARMDataHandler::getValueByStdObjectIndex( $ob, "prefix_table" , "armlog" ) ;
		parent::setConfig($ob);
	}
	protected function setAlias( $alias ){
		$this->_alias   = $alias ;
	}
	protected function getTableName(){
		return ARMDataHandler::returnValidDbTableName( $this->_prefix."_".$this->_alias ) ;
	}

	/**
	 * @param ARMLogInfoVO $ARMLogInfoVO
	 * @param bool $autoCreateTable
	 * @return ARMReturnDataVO|null
	 * @throws ErrorException|Exception
	 */
	public function addLog( ARMLogInfoVO $ARMLogInfoVO , $autoCreateTable = TRUE ){
		$VO = ArmLogModelGateway::getInstance()->getVO() ;

		$VO->parseObject( $ARMLogInfoVO ) ;
		$VO->date_in        = ARMMysqliModule::DATA_NOW ;
		$VO->data           = ( !is_string( $ARMLogInfoVO->data ) ) ? json_encode( $ARMLogInfoVO->data ) : $ARMLogInfoVO->data ;

		try{
			return $this->_daoInstance->insertVO( $VO ) ;
		} catch( ErrorException $e ){
			if( $autoCreateTable ){
				return $this->createTable( $ARMLogInfoVO ) ;
			}
			throw $e ;
		} catch( Exception $e ){
			if( $autoCreateTable ){
				return $this->createTable( $ARMLogInfoVO ) ;
			}
			throw $e ;
		}
		return NULL ;
	}

	/**
	 * Pega os logs conforme o filtro na tabela configurada para essa instancia
	 * a tabela é relativa aou prefixo no config + instancia do módulo
	 * @param ARMLogFilterVO $Filter
	 * @param number $limit
	 * @param number $offset
	 * @return ARMReturnDataVO|null
	 */
	public function getLog( ARMLogFilterVO $Filter , $limit = NULL , $offset = NULL ){
		$VO = ArmLogModelGateway::getInstance()->getVO() ;
		$VO->parseObject( $Filter ) ;
		// $Filter->date_min
		try{
			return $this->_daoInstance->selectByVO( $VO , $limit, $offset ) ;
		} catch( ErrorException $e ){
			return NULL ;
		}
		return NULL ;
	}
	protected function createTable( $ARMLogInfoVO = NULL ){
		$resultCreate = $this->_daoInstance->createTableLog();
		if( $resultCreate->success ) {
			if( $ARMLogInfoVO ) {
				return $this->addLog( $ARMLogInfoVO , FALSE) ;
			}
			return $resultCreate ;
		}
		throw new ErrorException( $resultCreate->error_message , $resultCreate->code_return ) ;
	}
}