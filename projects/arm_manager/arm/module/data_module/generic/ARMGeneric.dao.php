<?php
/**
 *
 * Essa DAO generica utiliza o alias como nome da tabela por default
 *
 * Pode mudar depois
 *
 * E não pega o config com o nome do alias enviado, então ele vai tentar pegar o default alias sempre
 * Caso queira definir um, basta utilizar o metodo setDefaultAlias
 *
 * User: renatomiawaki
 * Date: 12/11/13
 * 
 */

class ARMGenericDAO extends ARMBaseDAOAbstract{
	public function setTable( $table ){
		$this->TABLE_NAME = $table ;
	}

	/**
	 * @return ARMGenericDAO
	 */
	public static function getDefaultInstance(){
		return parent::getDefaultInstance();
	}

	/**
	 * @param string $alias como nome da tabela nesse caso
	 * Para setar o config, utilize o metodo setDefaultAlias
	 * @return ARMGenericDAO
	 */
	public static function getInstance( $alias = NULL ){
		$instance = parent::getInstance();
		$instance->setTable( $alias ) ;
		return $instance ;
	}

}