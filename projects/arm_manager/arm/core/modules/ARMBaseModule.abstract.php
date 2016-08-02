<?php

/**
 * Base de quase todos os módulos
 * 
 * @author renatomiawaki
 *
 */
abstract class ARMBaseModuleAbstract extends ARMBaseSingletonAbstract implements ARMModuleInterface{
	
	const DEFAULT_GLOBAL_ALIAS = "default" ;
	
	protected static  $use_config = TRUE ;
	/**
	 * 
	 * @var object
	 */
	protected static $last_instance ;
	
	/**
	 *
	 * @var object
	 */
	protected static $default_instance ;
	
	/**
	 * 
	 * @var object
	 */
	protected static $__config ;
	
	
	protected $_config ;

	/**
	 * none default para o alias da classe quando nenhum alias for passado
	 * @var ARMDictionary
	 */
	protected static $_DEFAULT_ALIAS;
	public static function setDefaultAlias( $alias ){
		$class = get_called_class() ;
		$className = $class::getConfigFolderName() ;
		self::getAliasPool()->add( $className, $alias ) ;
	}

	/**
	 *
	 * @return ARMDictionary
	 */
	private static function getAliasPool(){
		if( ! self::$_DEFAULT_ALIAS ){
			self::$_DEFAULT_ALIAS = new ARMDictionary() ;
		}
		return self::$_DEFAULT_ALIAS ;
	}
	public static function getDefaultAlias(){
		if( self::getAliasPool() ){
			$class = get_called_class() ;
			if( $alias = self::getAliasPool()->get( $class::getConfigFolderName() ) ){
				//achou o default alias setado
				return $alias ;
			}
			//não achou o default setado, pega do global
			return self::DEFAULT_GLOBAL_ALIAS ;
		}
		return NULL ;
	}
	/**
	 * 
	 * @param string $alias
	 * @param unknown $data
	 * @return boolean
	 */
	public static function install( $alias = "" , $data ) {
		//@TODO: verificar o install e testar
		$realClass = get_called_class() ;
		$class_name 	= $realClass::getConfigFolderName() ;

		$alias 			= ($alias == "") ? self::getDefaultAlias() : $alias ;
		
		return ARMModuleManager::saveConfig( $class_name, $alias, $data ) ;
	}
	
	/**
	 * Override if want to change or force name
	 * (pt_br) Retorna o nome da classe para controle de config pelo ARMConfig
	 * (pt_br) Caso precise de um mesmo config para classes que extendem sua classe principal
	 * @return string
	 */
	protected static  function getConfigFolderName(){
		return get_called_class() ;
	}
	
	
	public static function getInstanceByPath( $pathToFindConfig  ) {

		$realClass = get_called_class() ;
		$class_name 	= $realClass::getConfigFolderName()  ;
		$alias 			= is_array( $pathToFindConfig ) ? implode("/", $pathToFindConfig )  : $pathToFindConfig  ;

		$instance 		= parent::getInstance( $alias ) ;
		if( !isset( self::$__config[ $class_name ]) ) {
			self::$__config[ $class_name ] = array() ;
		}
		
		if( ! isset( self::$__config[ $class_name ][ $alias ] ) ){
			
			if( !is_array( $pathToFindConfig ) ){
				$pathToFindConfig = explode("/" , $pathToFindConfig ) ;
			}
			
			$configResult = ARMModuleManager::getConfigByPath( $class_name , $pathToFindConfig ) ;
			
			self::$__config[ $class_name ][ $alias ] = TRUE;
			
			//tenta pegar o config padrão caso a classe indique qual é o parseador de config
			//passa o config parseado para o setConfig
			$instance->setConfig( $instance->getParsedConfigData( $configResult ) );

		}

		// set the last instance alias only if an instance can be provided;
		self::$last_instance[ $class_name ] = $alias ;
		
		return $instance ; 
	}
	
	/**
	 * Returns the last instance of the called ModuleClass
	 * @return object
	 */
	public static function getLastInstance() {
		$class_name 	= self::getConfigFolderName() ;
		if( ! isset( self::$last_instance[ $class_name ] ) ){
			return NULL ;
		}
		ARMDebug::ifPrint( self::$last_instance , "base_module" ) ;
		ARMDebug::ifPrint( $class_name , "base_module" ) ;
		return self::getInstance( self::$last_instance[ $class_name ]  );
	}
	
	/**
	 * Verifies if there is a last instance of the of the called ModuleClass
	 * @return boolean
	 */
	public static function hasLastInstance(){
		$class_name 	= self::getConfigFolderName()  ;
		return isset( self::$last_instance[ $class_name ] ) ;
	}
	
	/**
	 * 
	 * @param string $alias
	 * @return ARMBaseModuleAbstract
	 */
	public static function getInstance ( $alias = NULL , $useDefaultIfNotFound = FALSE  ){
		$realClass = get_called_class() ;
		$class_name 	= $realClass::getConfigFolderName()  ;
		$alias 			= ( ! $alias ) ? self::getDefaultAlias()  : $alias ;

		$instance 		= parent::getInstance( $alias ) ;
		if( !isset( self::$__config[ $class_name ]) ) {
			self::$__config[ $class_name ] = array() ;
		}
//		ARMDebug::error( get_called_class() . "[ $class_name ] alias: ( $alias ) " ) ;
//		ARMDebug::print_r( $result ) ;
		if( ! isset( self::$__config[ $class_name ][ $alias ] ) ){
			
			$configResult = ARMModuleManager::getConfig( $class_name , $alias ) ;
			//coloca na memória o config carregado para essa classe e esse alias
			self::$__config[ $class_name ][ $alias ] = $configResult ;

		}
		$configResult = self::$__config[ $class_name ][ $alias ] ;
		if( $useDefaultIfNotFound && ! $configResult ){
			//pegando a instancia padrão já que não encontrou o config para esse alias
			return self::getInstance() ;
		}
		$instance->setConfig( $instance->getParsedConfigData( $configResult ) );
		// set the last instance alias only if an instance can be provided;
		self::$last_instance[ $class_name ] = $alias ;
		
		return $instance ;
	}
	
	
	/**
	 * @return ARMBaseModuleAbstract
	*/
	public static function getInstaceByConfigVO( $configVO , $alias ) {
		$realClass = get_called_class() ;
		
		$class_name 	= $realClass::getConfigFolderName() ;
		
		$instance 		= parent::getInstance( $alias ) ;
		//set or reset config
		$instance->setConfig(  $configVO ) ;
	
		self::$last_instance[ $class_name ] = $alias ;
		
		return $instance ;
	}

	function __invoke()
	{
		// TODO: Implement __invoke() method.
	}

	public function setConfig( $ob ){
		$this->_config 		= $ob;
	}
	
	/**
	 * This method must be overriden to
	 * @param object $configResult
	 * @return object
	 */
	public function getParsedConfigData( $configResult ){
		if( ! $configResult ){
			//não encontrou config pelo json, procura a VO do módulo pra vir os valores padrões do módulo
			$configVOName = $this->getConfigClassName() ;
			ARMClassIncludeManager::load( $configVOName ) ;
			if( $configVOName ){
				return new $configVOName() ;
			}
		}
		return $configResult ;
	}
	
	private static $__configVOClass ;
	/**
	 * Aconselhado sobreescrever esse metodo para agilizar o processo
	 * 
	 * Esse metodo, procura automaticamente uma classe VO que combine com esse módulo, 
	 * buscando pelo nome da classe do módulo
	 * 
	 * @return string
	 */
	public static function getConfigClassName() {
		if( ! self::$__configVOClass ){
			self::$__configVOClass = new ARMDictionary();
		}
		$className = get_called_class() ;
		$configName = self::$__configVOClass->get( $className ) ;
		if( $configName ) {
				
			return $configName ;
		}
	
		$possibleVOName = str_replace("Module", "", $className ) ;
		$possibleVOName .= "ConfigVO";
		if( ARMClassHunter::classExists( $possibleVOName ) ){
				
			self::$__configVOClass->add( $className , $possibleVOName ) ;
			return $possibleVOName ;
				
		}
		self::$__configVOClass->add( $className , NULL ) ;
		return NULL ;
	
	}
	
	
	/**
	 * 
	 * @param ARMModuleInterface $instance
	 * @return object
	 */
	public static function setDefaultInstance( ARMModuleInterface $instance ) {
		$class_name 	= self::getConfigClassName()  ;
		if( !isset( self::$default_instance  ) )
			self::$default_instance = array();
		
		self::$default_instance[ $class_name ] = $instance ;
		
		return self::$default_instance[ $class_name ];
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMModuleInterface::getDefaultInstance()
	 */
	public static function getDefaultInstance() {
		$class_name 	= self::getConfigClassName() ;
		
		if( !isset( self::$default_instance ) ) 
			return NULL;
		
		if( is_array( self::$default_instance ) && isset(self::$default_instance[ $class_name ] ) )
			return self::$default_instance[ $class_name ] ;
		
		 return NULL;
	}
	
}