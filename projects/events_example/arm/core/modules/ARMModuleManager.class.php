<?php

/**
 * 
 * @author renatomiawaki
 *
 */
class ARMModuleManager extends ARMBaseSingletonAbstract {
	
	public static function ___install( $alias = "" , $data ){
		
	}
	
	/**
	 *
	 * @return FetchedClassInterface
	 *
	*/
	public function __getParsedConfigData( $obj ){
		return new ARMModuleManagerConfigInfoVO( $obj );
	}
	
	protected static $_configLoaderDriver ;
	/**
	 * 
	 * @param string $className
	 * @param string $alias
	 * @throws ErrorException
	 * @return object
	 */
	public static function getConfig( $className ,  $alias = "" ){
		
		$configLoaderDriverClassName = self::getConfigLoaderDriver();
		return $configLoaderDriverClassName::getConfig( $className ,  $alias ) ;
		
	}
	
	
	public static function getConfigByPath(  $className, $arrayPathFolder ){
		$configLoaderDriverClassName = self::getConfigLoaderDriver();
		return $configLoaderDriverClassName::getConfigByPath( $className ,  $arrayPathFolder ) ;
	}

	/**
	 * Retorna o nome da classe que cuida de carregar configs em módulos
	 * pois o mesmo pode vir de arquivo ou banco de dados.
	 * @return string
	 * @throws ErrorException
	 */
	protected static function getConfigLoaderDriver(){
		
		//verificar o tipo de procura, se em file ou o que for, baseado no config desse mesmo modulo encontrado
		$configLoaderDriverClassName 	= ARMConfig::getDefaultInstance()->getDefaultConfigLoaderDriver() ;
		
		if( self::$_configLoaderDriver && $configLoaderDriverClassName == self::$_configLoaderDriver ){
			//já existe um setado, e é o mesmo do ultimo informado como padrão
			return self::$_configLoaderDriver ;
		}
		
		ARMClassIncludeManager::load( $configLoaderDriverClassName ) ;
		
		$implements 	= class_implements( $configLoaderDriverClassName ) ;
		if( !$implements || ! in_array("ARMConfigResolverInterface", $implements) ){
			throw new ErrorException("A classe $className precisa implementar ARMConfigResolverInterface ") ;
		}
		self::$_configLoaderDriver = $configLoaderDriverClassName ;
		
		return self::$_configLoaderDriver ;
	}
		
	public static function saveConfig( $className, $alias, $data ){
		if( ! $data ){
			return FALSE ;
		}
		//@TODO: Não entendi nada !!! Explicar e corrigir/testar
		$class 		= ARMConfig::getDefaultConfigLoaderDriver() ;
		ARMClassIncludeManager::load( $class ) ;
		$implements = class_implements( $class ) ;
		if( !$implements || !in_array("ARMConfigResolverInterface", $implements) ){
			throw new ErrorException("A classe $class precisa implementar ARMConfigResolverInterface ") ;
		}
		$meuConfig = $class::getConfig(get_called_class() , $className );
		if( $meuConfig ){
			$class 			= $meuConfig->className ;
			ARMClassIncludeManager::load( $class ) ;
			$implements 	= class_implements( $class ) ;
			if( !$implements || !in_array("ARMConfigResolverInterface", $implements) ){
				throw new ErrorException("A classe $class precisa implementar ARMConfigResolverInterface ") ;
			}
			
		}
		
		return $class::saveConfig( $className, $alias, $data ) ;
	}
}

