<?php
	/**
	* created by ARMDaoMaker ( automated system )
	* Please, change this file
	* don't change ARMBaseARMFileDAO class
	*
	* ARMFileDAO
	* @date 07/01/2014 06:01:11
	*/
	
class ARMFileDAO extends ARMBaseARMFileDAOAbstract {
	public function setTable( $table ){
		$this->TABLE_NAME = $table ;
	}

	/**
	 * @return ARMFileDAO
	 */
	public static function getDefaultInstance(){
		return parent::getDefaultInstance();
	}

	/**
	 * @param string $alias como nome da tabela nesse caso
	 * Para setar o config, utilize o metodo setDefaultAlias
	 * @return ARMFileDAO
	 */
	public static function getInstance( $alias = NULL ){
		$instance = parent::getInstance();
		if( ! $alias ){
			// se não for enviado o nome de instancia, força que seja o valor padrão do config
			$config = new ARMSimpleFileConfigVO() ;
			$alias = $config->$alias ;
		}
		$instance->setTable( $alias ) ;
		return $instance ;
	}

	/**
	 * Cria a tabela com o nome definido no alias enviado
	 * Caso não seja enviado alias, no alias padrão do config do módulo de ARMSimpleFile
	 * @return ARMReturnDataVO
	 */
	public function createTable() {
		$query = $this->getCreateTableSQL( $this->TABLE_NAME ) ;
		$result = $this->query( $query ) ;
		return $result ;
	}
	protected function getCreateTableSQL( $alias ){
		return "
			CREATE TABLE IF NOT EXISTS `{$alias}` (
			   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `active` tinyint(4) DEFAULT NULL,
			  `order` int(11) DEFAULT NULL,
			  `type` varchar(100) NOT NULL,
			  `url` varchar(255) DEFAULT NULL,
			  `name` varchar(255) DEFAULT NULL,
			  `description` text,
			  PRIMARY KEY (`id`)
				,
			  KEY `type` (`type`,`action`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	}
}