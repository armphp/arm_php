<?php




/**
 * This class grants access to all Db configured connections by slug or id
 * @author alanlucian
 *
 */

class ARMDBManager extends ARMBasePoolManager{

	
	const DRIVER_MYSQLI = "MYSQLI" ;
	
	
	private static $dbConfigPool = array();
	
	
	/**
	 * Add an configurantion on DB config Pool
	 * @param ARMDbConfigInterface $DBInfoConfigVO
	 */
	public static function add(  $DBInfoConfigVO , $alias = "default" ){
		
		self::$dbConfigPool[ $alias ] = $DBInfoConfigVO ;
		
		//self::$dbConfigPool[] = &self::$dbConfigPool[ $alias ] ;
	}
	
	public static function getConn( $alias = "" , $override = FALSE ) {
		$config = self::getConfig( $alias );
		if( !$config ) {
			return FALSE ;
		}
		if( !$config->getLink() || $override ){
			self::startLink( $config );
		}
		return $config->getLink() ; // $config->getLink();
		
	}
	
	public static function getByAlias( $alias ){
		return self::getConfig( $alias );
	}
	public static function getByIndex( $alias ){
		return self::getConfig( $alias );
	}
	/**
	 * 
	 * @param ambiguous $alias
	 * @return DbConfigInterface
	 */
	private static function getConfig( $alias ){
		//ARMDebug::li("pegando config pelo alias $alias ");
		
		if( !isset( self::$dbConfigPool[$alias] ) ) {
			//ARMDebug::li(" ERRO pelo alias: $alias ");
			return FALSE;
		}
		//ARMDebug::print_r( self::$dbConfigPool ) ;
		return self::$dbConfigPool[ $alias ] ;
	}
	
	
	
	
	/**
	 * 
	 * @param ARMDbConfigInterface $config
	 * @throws ErrorException
	 */
	
	private static function startLink( ARMDbConfigInterface &$config ){
		
		// Here we need do check if the DB driver is suported by the framework
		if(  $config->getDriver()  !== self::DRIVER_MYSQLI ){
			throw new ErrorException( "Driver de banco de dados não suportado" );
			die ;
		}
		
		try{
			//start an DB link and pass it to the config instance
			
			//ARMDebug::li( "startLink :  {$config->getHost()} , {$config->getUser()}, {$config->getPassword()} , {$config->getDBName()} " ) ;
			
			$dbLink = new mysqli(  $config->getHost() , $config->getUser(), $config->getPassword() , $config->getDBName() ) ;




			if( mysqli_errno( $dbLink ) ) {
				echo "erro de banco";
				exit();
			}
			$config->setLink( $dbLink ) ;

		} catch (Exception $e){
			$ReturnResultVO->sucess  = FALSE;
			$ReturnResultVO->addMessage($e);
			echo "erro de conexao com o banco";
			exit();
		} // end try{
		
	}
	
}