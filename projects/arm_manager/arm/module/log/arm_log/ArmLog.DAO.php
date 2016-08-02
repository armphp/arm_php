<?php
	/**
	* created by ARMDaoMaker ( automated system )
	* Please, change this file
	* don't change ARMBaseArmLogDAO class
	*
	* ArmLogDAO
	* @date 11/12/2013 07:12:18
	*/
	
class ArmLogDAO extends ARMBaseArmLogDAOAbstract {

	public function createTableLog(){
		$query = $this->getCreateTableSQL( $this->TABLE_NAME ) ;
		$result = $this->query( $query ) ;
		return $result ;
	}
	protected function getCreateTableSQL( $alias ){
		return "
			CREATE TABLE IF NOT EXISTS `{$alias}` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `date_in` datetime NOT NULL,
			  `user_id` int(10) unsigned DEFAULT NULL,
			  `ref_id` int(11) DEFAULT NULL,
			  `ref_alias` varchar(255) DEFAULT NULL,
			  `action` varchar(255) NOT NULL,
			  `action_label` varchar(255) DEFAULT NULL,
			  `data_resolver_class` varchar(255) NULL,
			  `data` text,
			  PRIMARY KEY (`id`),
			  KEY `ref_alias` (`ref_alias`,`action`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	}
}