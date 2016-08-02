<?php

include_once 'arm/core/utils/class/ARMClassHunter.class.php';


class ARMClassIncludeManager {

	private static $include_folder =  NULL ;
	//"tmp/class_include" ;
	//@TODO: Transformar essa classe em módulo
	private static $inc_prefix  = ".inc.php" ;

	public static function includeFor( $class_name , $force_include = TRUE ){

		self::prepareFolder();

		$include_file =  self::$include_folder . "/" . $class_name . self::$inc_prefix  ;

		return self::generateIncFile( $include_file , ARMClassHunter::findClassesToInclude( $class_name ) , $force_include, ARMClassHunter::getErrors() ) ;

	}


	public static function load(  $class_name ){
		if ( class_exists( $class_name ) )
			return true ;

		self::includeFor( $class_name );
		ARMClassHunter::includeClass( $class_name ) ;

		return class_exists( $class_name );

	}

	public static function loadByFile( $fileName , $includeFile = TRUE , $includeDependences = TRUE ) {

		self::prepareFolder();

		ARMClassHunter::findClassesToIncludeByFile( $fileName   ) ;

		$include_file_name = self::$include_folder . "/" . md5( $fileName )  . self::$inc_prefix  ;

		self::generateIncFile( $include_file_name , ARMClassHunter::findClassesToIncludeByFile( $fileName ) , $includeDependences, ARMClassHunter::getErrors() ) ;

		if( $includeFile )
			include_once $fileName ;

		return $include_file_name ;
	}


	protected static function generateIncFile( $file_name , $file_list  , $force_include  , $errors  ){
		if( !is_file( $file_name ) ||  ARMConfig::getDefaultInstance()->isDev() === TRUE ) {

			$include_data = "<?php\n";

			foreach( $file_list as $file )
				$include_data.= sprintf( "include_once '%s';\n" , $file );


			$include_data.= "/* " . implode("\n", $errors ) . "  */" ;

			$include_data.= "?>";

			$handle =  fopen( $file_name , "w");
			fwrite( $handle , $include_data ) ;
			fclose($handle);
		}

		if( $force_include )
			include_once $file_name;

		return $file_name ;
	}

	protected static function prepareFolder(){

        self::getIncludeFolder();

		ARMDataHandler::createRecursiveFoldersIfNotExists( self::$include_folder );

		if( !is_dir( self::$include_folder))
			mkdir( self::$include_folder );


	}

    public static function getIncludeFolder(){

        if( self::$include_folder  == NULL ){
            self::$include_folder = ARMConfig::getDefaultInstance()->getTempFolder( "class_include" ) ;
        }
        return self::$include_folder;
    }

    public static function clearFolder(){

        self::prepareFolder();
        ARMDataHandler::clearFolder( self::$include_folder, "php" );

    }
}