<?php
set_time_limit(60);


class ARMClassHunter {

	CONST DEBUG_VALUE_ALL = "hunter";
	//@TODO: ARM  8 isso do Zend deve vir da config usando o AddPathToIgnore
	private static $ignore_list = array ();


	//@TODO: ARM  8 isso tem que vir do config
	private static $base_dir_list = NULL ;


	private static $class_list ;


	private static $used_classes ;

	private static $include_list ;
	private static $errors;


	public static function getBaseDirList(){
		if( !self::$base_dir_list )
			self::$base_dir_list = ARMConfig::getDefaultInstance()->getAllClassPath();

		return self::$base_dir_list ;
	}

	public static function classExists($class_name){
		if( !is_array( self::$class_list ) ) {
// 			die;
			self::parseFolderList( self::getBaseDirList() ) ;
		}

		return isset( self::$class_list[$class_name] ) ;

	}


	public static function getClassFilePath($class_name){
		if( !self::classExists($class_name) )
			return NULL;

		return self::$class_list[$class_name] ;

	}

	public static function addPathToIgnoreList( $path ){
		if( !in_array( $path,  self::$ignore_list) )
			self::$ignore_list[] =  $path ;

	}

	private static function parseFolderList( array $folderList ){

		foreach( $folderList as $folderPath ){
			self::parseFolder( $folderPath );
		}

	}

	public static function parseFolder( $dir ){

// 		self::addPathToIgnoreList( Config::FOLDER_REQUEST_CONTROLER );
		if( !is_dir($dir) )
			return FALSE ;
		$dir_contents  = scandir( $dir );

// 		ARMDebug::li( " Parseando Diretório:" . $dir) ;
		foreach( $dir_contents as $dir_content ){
			$dir_path = $dir. "/" . $dir_content;
// 				ARMDebug::print_r( $dir_content  );
				if( preg_match( "/^([A-Za-z0-9_.]*)\.([a-zA-Z_0-9_]\.)?php/", $dir_content , $out) ){

					$file_content = file_get_contents( $dir_path );
					if( preg_match ("/[^A-Za-z](class|interface|Interface)\s([A-Z][A-Z-a-z0-9_]*)/" , $file_content, $out ) ){
 						$className = $out[2];

 						$ignore_list = self::$ignore_list  ;
//  						array_walk( $ignore_list  , "ARMDataHandler::escapeToEreg" , "/" );//

 						if( count( $ignore_list ) > 0 ){
	 						foreach( $ignore_list as &$item )
	 							$item = ARMDataHandler::escapeToEreg($item);

	 						if( preg_match( "/(" . implode( "|" , $ignore_list  )  . ")/", $dir_path ) )
	 							return false ;
						}
						self::addToClassList( $className  , $dir_path );
					}
					//verificar se é uma classe antes de por na lista !
				}
			if( $dir_content[0]!== "." && $dir_content != "." && $dir_content != ".."  && is_dir( $dir_path ) ){
				self::parseFolder( $dir_path ) ;
			}
		}
	}

	private static function addToClassList( $class_name , $path){
		if(!is_array( self::$class_list ) ){
			self::$class_list = array();
		}
		$path = ARMDataHandler::removeDoubleBars($path);
		if( !isset( self::$class_list[$class_name] ) ){
			self::$class_list[$class_name] = $path ;
// 			ARMDebug::ifLi($class_name . " add_ " . $path ) ;
		}
	}


	public static function findClassByFile( $fileName ) {
		if( !is_array( self::$class_list ) )
			self::parseFolderList( self::getBaseDirList() );

		$class_name = array_search(  $fileName ,  self::$class_list  ) ;

		return $class_name ;
	}

	public static function includeClass( $class_name ){
		if( !is_array( self::$class_list ) )
			self::parseFolderList( self::getBaseDirList() );


// 		ARMDebug::print_r(self::$class_list);
// 		var_dump("INCLUINDO." .  $class_name  ) ;



		if( isset( self::$class_list[ $class_name ] ) )
			include_once( self::$class_list[ $class_name ] ) ;

		return class_exists( $class_name );
	}




	public static function findClassesToInclude(  $class_name  ){

		ARMDebug::li(" Hunter em {$class_name}", ARMClassHunter::DEBUG_VALUE_ALL );

		if( !is_array( self::$class_list ) )
			self::parseFolderList( self::getBaseDirList() );

		ARMDebug::ifPrint( self::$class_list , ARMClassHunter::DEBUG_VALUE_ALL  );

		self::$errors = array();

		self::$used_classes  = array();

		self::$include_list = array();

		self::parseClass( new UsedClass( $class_name , 0 ) );

// 		krsort(self::$include_list);

		$used_classes = array_values (  self::$used_classes  ) ;

		usort( $used_classes  , "self::otherUserdClass") ;

		$include = array();
		$i = 0;
		foreach( ( $used_classes) as $used_class  ){

			if(FALSE){
				$used_class = new UsedClass();
			}

			if( isset( $include[$i-1] ) && $include[$i-1] == self::$class_list[ $used_class->name ] ){
				//skip
				continue ;
			} else {
				$include[] = self::$class_list[ $used_class->name ] ;
			}
			$i++;
		}

		ARMDebug::ifPrint($used_classes , ARMClassHunter::DEBUG_VALUE_ALL ) ;


		ARMDebug::ifli( "INCLUDE:" ,  ARMClassHunter::DEBUG_VALUE_ALL );
		ARMDebug::ifPrint( $include , ARMClassHunter::DEBUG_VALUE_ALL );

		return $include;

	}


	private static function otherUserdClass( UsedClass $classA , UsedClass $classB ) {

		if( $classA->getPriority() == $classB->getPriority() ) {
			return 0 ;
		}

		return ( ( $classA->getPriority() > $classB->getPriority()  ) ? -1 : 1 ) ;

	}

	public static function getErrors(){
		return self::$errors;
	}

	public static function findClassesToIncludeByFile( $fileName ){

		if( !is_array( self::$class_list ) ) {
			// 			die;
			self::parseFolderList( self::getBaseDirList() ) ;
		}
		$class_name  = md5( $fileName ) ;
		self::$class_list[ $class_name ] = $fileName ;

		return self::findClassesToInclude( $class_name ) ;
 	}

	private static function parseClass( UsedClass $class ) {


		if( !isset( self::$class_list[ $class->name ] ) ){
			ARMDebug::ifli(" ARQUIVO não encontrado para :" . $class->name  , ARMClassHunter::DEBUG_VALUE_ALL );
			//
			return FALSE ;
		}

		ARMDebug::ifli(" Parseando :" . self::$class_list[ $class->name ] , ARMClassHunter::DEBUG_VALUE_ALL );
		if(!self::$class_list[ $class->name ]){
			li("Class Hundler error", TRUE);
		}
		$file_content  = file_get_contents(  self::$class_list[ $class->name ] );

		//var_dump( "Find class On" . $file_path . $file_content );
// 		$kindOfInclude = array(
// 				"/new\s+([A-Z][A-Za-z_0-9]+)\s*\(/m" => FALSE, // new classes
// 				"/implements\s+([A-Z][A-Za-z_0-9]+)/m" => TRUE, // implements
// 				"/implements\s+[A-Z][A-Za-z_0-9]+\s*,\s*([A-Z][A-Za-z_0-9]+[,\s]*)*/m" => TRUE, // implements
// 				"/extends\s+([A-Z][A-Za-z_0-9]+)/m" => TRUE , //extends
// 				"/([A-Z][A-Za-z_0-9]+)::/m" => FALSE, // static
// 		);


		$kindOfInclude = array(
				"getNewInstancesOnFile",
				"getInterface" ,
				"getSuperClass",
				"getStaticInstancesOnFile",
		);



		$current_file_used_classes = array() ;

		foreach ( $kindOfInclude as $method ){
			//preg_match_all( $pattern , $file_content , $results );

			$classList =  call_user_func( "self::$method" , $file_content );
			/*@var $classList ARMHunterClassDependenceVO */


			ARMDebug::ifli( "Class {$class->name} Executou:" . $method , ARMClassHunter::DEBUG_VALUE_ALL );
			ARMDebug::ifPrint( $classList , ARMClassHunter::DEBUG_VALUE_ALL );


			if( sizeof( $classList->class_list ) > 0 ){
				// se uma classe é usada mais de uma vez em um arquivo ela vai passar mais de uma vez aqui, e gera um problema de prioridade

				foreach ( $classList->class_list as $className ){

					$req = ( $classList->required ? "REQUIRES" : "USE");
					ARMDebug::ifli( "Class {$class->name} {$req} {$className}"  , ARMClassHunter::DEBUG_VALUE_ALL );



					if( class_exists( $className ) || in_array( $className , $current_file_used_classes )  ){
						ARMDebug::ifli( "Class {$class->name} {$req} {$className} e esta já existe"  , ARMClassHunter::DEBUG_VALUE_ALL );
						continue ;
					}


					if( !isset( self::$class_list[$className] ) ){
						self::$errors[] = "Class \"{$className}\" used in \"" . self::$class_list[$class->name] . "\" NOT FOUND ";
						continue ;
					}


					if(   !isset(  self::$used_classes[ $className ] ) ){
						self::$used_classes[$className] = new UsedClass( $className , ( $class->getPriority() + 1 ) );

						$current_file_used_classes[] = $className ;


						self::parseClass( self::$used_classes[$className]  ) ;

						ARMDebug::ifli( "Parse " . $className , ARMClassHunter::DEBUG_VALUE_ALL );

					}
					if( $classList->required ) {

						ARMDebug::ifli( $class->name . " REQUIRED ADD " . $className . " ??? " . isset( self::$used_classes[$className] ) , ARMClassHunter::DEBUG_VALUE_ALL );

						$class->addRequired(  $className , self::$used_classes[$className] ) ;
					}

// 						self::$include_list[] = self::$class_list[$className];
				}
			}
		}//foerach pattern
	}





	/**
	 *
	 * @param string $file_content
	 * @return ARMHunterClassDependenceVO
	 */
	protected static function getInterface( $file_content){
		$classes = new ARMHunterClassDependenceVO();
		$classes->required = TRUE ;
		$classes->class_list =array();
		$ereg  = "/implements\s+(.*){/m" ;
		preg_match_all( $ereg , $file_content , $results ) ;
// ARMDebug::print_r($results);

		if(!is_array( $results[1]) || count( $results[1]) == 0)
			return $classes;

		$class_list = explode(",", $results[1][0] );
		for( $i=0; $i<count($class_list); $i++  )
			$class_list[$i] = trim($class_list[$i]);

// 		ARMDebug::ifLi("INterfaces:");
// 		ARMDebug::ifPrint( $class_list , ARMClassHunter::DEBUG_VALUE_ALL ) ;

		$classes->class_list = $class_list;
		return $classes ;
	}



	/**
	 *
	 * @param string $file_content
	 * @return ARMHunterClassDependenceVO
	 */
	protected static function getSuperClass( $file_content ){

		$ereg  = "/extends\s+([A-Z][A-Za-z_0-9]+)/m" ;
		preg_match_all( $ereg , $file_content , $results ) ;

		$classes = new ARMHunterClassDependenceVO();
		$classes->required = TRUE ;
		$classes->class_list = $results[1] ;

		return $classes ;
	}

	/**
	 *
	 * @param string $file_content
	 * @return ARMHunterClassDependenceVO
	 */
	protected static function getNewInstancesOnFile( $file_content ){

		$ereg  = "/new\s+([A-Z][A-Za-z_0-9]+)\s*\(/m" ;
		preg_match_all( $ereg , $file_content , $results ) ;


		$classes = new ARMHunterClassDependenceVO();
		$classes->required = FALSE ;
		$classes->class_list = $results[1] ;

		return $classes ;
	}

	/**
	 *
	 * @param string $file_content
	 * @return ARMHunterClassDependenceVO
	 */
	protected static function getStaticInstancesOnFile( $file_content ){

		$ereg  = "/([A-Z][A-Za-z_0-9]+)::/m" ;
		preg_match_all( $ereg , $file_content , $results ) ;

		$classes = new ARMHunterClassDependenceVO();
		$classes->required = FALSE ;
		$classes->class_list = $results[1] ;

		return $classes ;
	}




}


class ARMHunterClassDependenceVO {

	/**
	 *
	 * @var Boolean
	 */
	public $required ;

	/**
	 *
	 * @var array
	 */
	public $class_list ;
}

class UsedClass{


	public $name ;

	private $priority = 0 ;

	public $requires = array() ;

	public function __construct( $class_name , $priority = 0 ){
		$this->name = $class_name ;
		$this->priority = $priority ;
// 		ARMDebug::ifli( "new UsedClass:  " . $class_name . " :: " , $priority );
	}

	public function getPriority(){
		return $this->priority;
	}

	public function addRequired( $className , UsedClass &$class){
// 		ARMDebug::ifli( $this->name . "++" . $className   . " ??? $this->priority   :  $class->priority  ");
		$this->requires[ $className ] = $class ;
		if( $class->priority <= $this->priority ){
			$pri = $this->priority+1;


			$class->setPriority( $pri );
		}
	}

	public function setPriority( $var ){
		if( $var <= $this->priority )
			return;

		$this->priority = $var ;
		foreach( $this->requires as $usedClass ){
			$usedClass->setPriority( $var + 1 );
		}
	}

}