<?php
interface ARMModuleInterface extends ARMSingletonInterface {
	
	
	
	/**
	 * 
	 * metodo que salva o config do módulo
	 * 
	 * @param string $alias
	 * @param object $data
	 */
	static function install( $alias ="" , $data ) ;
	
	/**
	 * caso não use o módulo com o config em json e queira forçar o config
	 * @param object $config
	 */
	function setConfig( $config );
	
	/**
	 * @param object $object
	 * @return object
	 */
	function getParsedConfigData( $object );
	
	/**
	 * 
	 * Retorna o nome da classe que simboliza a configuração para esse módulo
	 * 
	 * @return string or null
	 */
	static function getConfigClassName() ;
	
	/**
	 * @return ARMModuleInterface
	 */
	static function getDefaultInstance();
	
	/**
	 * 
	 * @param ARMModuleInterface $instance
	 */
	static function setDefaultInstance( ARMModuleInterface $instance ) ;
	
	/**
	 * Get a module instance using $lconfigVO parameter without looking for any config
	 * @param object $configVO
	 */
	static function getInstaceByConfigVO( $configVO , $alias ) ;
	
	
}