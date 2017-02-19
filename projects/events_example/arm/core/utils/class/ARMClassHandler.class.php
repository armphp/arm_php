<?php
/**
 * @author 	Renato Miawaki
 * @desc 	Classe para verificação de classes ou objetos
 */
class ARMClassHandler {
	/**
	 * @param $object object | string
	 * @param $method_name string
	 * @return bool
	 */
	public static function isMethodPrivate( $class , $method_name){
		return self::isMethod( ReflectionMethod::IS_PRIVATE , $class, $method_name );
	}

	/**
	 *
	 * ReflectionMethod::IS_PUBLIC,
	 * ReflectionMethod::IS_PROTECTED,
	 * ReflectionMethod::IS_PRIVATE,
	 * ReflectionMethod::IS_ABSTRACT,
	 * ReflectionMethod::IS_FINAL.
	 *
	 *
	 * @param $class
	 * @param null $filter
	 * @return ReflectionMethod[]
	 */
	public static function getMethods( $class, $filter = NULL ){
		$reflect = new ReflectionClass( $class );
		$result = $reflect->getMethods( $filter );
		$arrayReturn = self::filterMethodResult( $result ) ;
		return $arrayReturn;
	}
	public static function filterMethodResult( $result ){
		$return = array() ;
		if(!$result){
			return $return ;
		}
		foreach( $result as $item ){
			$return[] = $item->name ;
		}
		return $return ;
	}
	/**
	 *
	 * @param unknown $class
	 * @param string $method_name
	 * @return boolean
	 */
	public static function isMethodStatic( $class , $method_name){
		return self::isMethod( ReflectionMethod::IS_STATIC , $class, $method_name );
	}

	/**
	 * @param $class object
	 * @param $method_name string
	 * @return bool
	 */
	public static function isMethodPublic( $class, $method_name){
		return self::isMethod( ReflectionMethod::IS_PUBLIC , $class, $method_name );
	}

	/**
	 * Verify if method exists using a ReflectionMethod filter
	 * @param number $filter
	 * @param object | string $class
	 * @param string $method_name
	 * @return boolean
	 */
	private static function isMethod( $filter , $class , $method_name ) {
		$reflect = new ReflectionClass( $class );

		$methods = $reflect->getMethods( $filter );

		foreach( $methods as $mehodReflect )
			if( $mehodReflect->name === $method_name  )
				return TRUE;

		return FALSE ;
	}


	/**
	 * Pega o nome da classe
	 * @param object $class
	 * @return string
	 */
	public static function getClassName( $class ){
		if( ARMValidation::isString($class) )
			return $class;

		$ref = new ReflectionClass($class);
		return $ref->getName() ;
	}

	/**
	 * retorna todos os attributos de um objeto
	 * @param object $class
	 * @return array
	 */
	public static function getAttributes( $class ){
		if( is_object( $class ) ){
			return array_keys( (array) $class ) ;
		}
		return self::getProperties( $class ) ;
	}
	/**
	 * Retorna todas as propriedades da classe
	 * @param className or instance $class
	 * @return multitype:
	 */
	public static function getProperties( $class ){
		if( is_object( $class ) ){
			return self::getAttributes( $class ) ;
		}
		$reflect = new ReflectionClass( $class );
		return $reflect->getProperties() ;
	}
	/**
	 *
	 * @param instance or class name $class
	 * @return array
	 */
	public static function getConstants( $class ){
		$reflect = new ReflectionClass( $class );
		return $reflect->getConstants() ;
	}
	/**
	 * Verifica se uma classe ou instancia implementa a interface enviada
	 * @param mixed object|string $class
	 * @param string $interface
	 * @return boolean
	 */
	public static function classImplements( $class , $interface ){
		$reflect = new ReflectionClass( $class );
		return $reflect->implementsInterface( $interface ) ;
	}
	/**
	 * Retorna o nome das classes implementadas na classe enviada
	 * @param unknown $class
	 * @return multitype:
	 */
	public static function getClassImplements( $class ){
		$reflect = new ReflectionClass( $class );
		return $reflect->getInterfaceNames() ;
	}
	/**
	 * Verifica se existe uma constante na classe enviada
	 * @param string $class_name
	 * @param string $constant_name
	 * @return boolean
	 */
	public static function hasConstant( $class_name , $constant_name ){
		$reflection 	= new ReflectionClass( $class_name ) ;
		$consts 		= $reflection->getConstants() ;
		return in_array( $constant_name , $consts ) ;

	}
	public static function hasMethod( $class, $method ){
		$reflection 	= new ReflectionClass( $class ) ;
		return $reflection->hasMethod( $method ) ;
	}
	public static function attributesToArrayUsingGet( $class ){

		$reflect = new ReflectionClass( $class );

		$methods = $reflect->getMethods( ReflectionMethod::IS_PUBLIC );

		$return = array();

		for( $i = 0 ; $i < count( $methods ) ; $i++ ) {

			$method = $methods[$i]->name ;

			if( !preg_match( "/^get(.*)/", $method , $matches) )
				continue;

			$attr = ARMDataHandler::strToURL( $matches[1] ) ;

// 			ARMDebug::print_r( $class );

			if( !self::hasAttribute( $class , $attr ) )
				continue;

			$return[ $attr ] =  call_user_func( array( $class, $method ) ) ;

		}
		return $return ;
	}

	public static function hasAttribute( $class , $attributeName ){
		$reflect = new ReflectionClass( $class );

		$attributes  = $reflect->getProperties() ;

		foreach( $attributes as $attributeReflect )
			if( $attributeReflect->name === $attributeName  )
			return TRUE ;

		return FALSE ;
	}

	public static function hasPublicAttrribute( $class , $attributeName ){
		$reflect = new ReflectionClass( $class );

		$attributes  = $reflect->getProperties() ;

		foreach( $attributes as $attributeReflect ){

			if( $attributeReflect->isPublic() && $attributeReflect->name === $attributeName  ){
				return TRUE ;
			}
		}

		return FALSE ;
	}

	/**
	 * Verifies if $class extends the other class
	 * @param object | string $class
	 * @param object | string  $class_to_test
	 * @return boolean
	 */
	public static function 	classExtends( $class, $class_to_test ){
		$reflect = new ReflectionClass( $class );
		return $reflect->isSubclassOf( $class_to_test ) ;

	}

	/**
	 * generates an instance for a given clas file path
	 * if an $interface is set checks if that class implements the interface
	 * @param string $file_name
	 * @param string $instance
	 * @return object
	 */
	public static function instanceByFile( $file_name , $interface = NULL ) {
			//@TODO:  verificar se realmente é necessário isso... pq parsear um arquivo p/ achar a classe e instanciar parece meio pesado  de mais
			return NULL ;
		if( file_exists( $file_name ) ){
		}
	}


    /**
     * @param $classCallerVO ARMClassProxyVO
     * @return mixed
     */
    public static function call( $classCallerVO ){

        // verifica se classe é string,
        // se for string é uma tentativa de acesso a um método estático
        if( ARMValidation::isString( $classCallerVO->class ) && !self::isMethodStatic( $classCallerVO->class,  $classCallerVO->method ) ) {
            throw new ErrorException("Tryng to access {$classCallerVO->method } a non static method on {$classCallerVO->class}");
        }

        return call_user_func_array( array( $classCallerVO->class, $classCallerVO->method ), $classCallerVO->params ) ;

    }
}