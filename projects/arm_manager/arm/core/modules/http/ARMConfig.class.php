<?php

include_once "arm/core/vo/ARMReturnResult.vo.php";


include_once "arm/core/interface/ARMSingleton.interface.php";
include_once "arm/core/application/ARMBaseSingleton.abstract.php";
include_once "arm/core/modules/interface/ARMModule.interface.php";

include_once 'arm/core/vo/ARMConfig.VO.php';




/**
 * Ele é um módulo e não extende base module pois base module utiliza o config
 *
 *
 * @author Renato Miawaki
 * ARMConfigInterface
 *
 * @version 1.1
 * Agora tem array_request_range_init e array_request_range_max para indicar onde inicia contabilizar as pastas a partir da request
 */
class ARMConfig implements ARMModuleInterface {
	
	const DEFAULT_INSTANCE_NAME = "config.json" ;
	
	public static $APP_CONFIG_FOLDER = "app_config/" ;
	protected static $default_instance ;
	/**
	 * 
	 * @var ARMConfig
	 */
	protected static $instance ;
	protected static $lastInstanceName ;
	
	
	/* (non-PHPdoc)
	 * @see ARMModuleInterface::getInstaceByConfigVO()
	 */
	public static  function getInstaceByConfigVO( $configVO , $alias = self::DEFAULT_INSTANCE_NAME) {
		
		self::$instance = new ARMConfig();
		
		self::$instance->getParsedConfigData( $configVO ) ;
		
		return self::$instance ;
	}
	/**
	 * Seta o nome da config para esse módulo para que encontre os valores padrão
	 * @return string
	 */
	public static function getConfigClassName(){
		return "ARMConfigVO" ;
	}
	/**
	 * 
	 * @param string $alias nesse caso é o nome das pastas em que ele vai procurar o config seguindo a lógina de tree
	 * @return ARMConfig this unique instance
	 */
	public static function getInstance( $alias = "" ) {
		
		if( self::$instance ){
			return self::$instance ;
		}
		
		$alias 			= ( $alias == "") ? self::DEFAULT_INSTANCE_NAME  : $alias ;
		
		
		self::$instance = new ARMConfig();
		$config_folder_name 	= self::getConfigFolderName()  ;
		
		//passa o config parseado para o setConfig
		$configResult 	= self::$instance->getConfig( $config_folder_name , $alias ) ;
		
		ARMDebug::ifLi( "Config:::" , "config") ;
		if( ! $configResult ){
			ARMDebug::error( "config file not found on " . $config_folder_name ) ;
		}
		self::$instance->getParsedConfigData( $configResult ) ;
		
		
		return self::$instance ;
		
	}
	
	protected function getConfig( $folder , $alias ){
		
		$rawURL =  ARMDataHandler::removeDoubleBars( $folder ."/". $alias ) ;
		
		
		$arrayFolderTree = explode("/",$rawURL ) ;

		//$configFile = ARMFileFinder::searchByFolder( ARMCoreSettings::getConfigDir(), $arrayFolderTree , "config.json" ) ;
		$configFile = ARMFileFinder::searchByFolder( ".", $arrayFolderTree , "config.json" , "json") ;
		ARMDebug::ifli(  " CFG " . $configFile, "config");
		
		if( file_exists( $configFile ) ){
			$data = file_get_contents( $configFile ) ;
			$dataObject = json_decode( $data ) ;
			
			if( is_null($dataObject) ){
		
		
				ARMDebug::error( "JSON sintax ERROR on file :: " .  $configFile ) ;
				ARMDebug::li( "File content: ");
				ARMDebug::print_r($data );
		
				die ;
			}

			$baseConfigVO = new ARMConfigVO();


			if ( isset($dataObject->view_module_list ) ) {
				$dataObject->view_module_list = ARMDataHandler::objectMerge($dataObject->view_module_list, $baseConfigVO->view_module_list);
			}


			$dataObject = ARMDataHandler::objectMerge( $dataObject , $baseConfigVO );
//			ARMDebug::print_r($dataObject );die;
			return $dataObject ;
		}
		
		return NULL ;
		
	}

	public function getCurrentConfigVO(){
		return $this->configVO ;
	}
	public function setConfig(  $configVO ){
		$this->configVO = $configVO ;
	}
	
	/**
	 *
	 * metodo que salva o config do módulo
	 *
	 * @param string $alias
	 * @param object $data
	 */
	public static function install( $alias ="" , $data ) {
		//@TODO: criar a pasta com o config.json 
	}
	
	

	/**
	 *
	 * @param ARMModuleInterface $instance
	 * @return ARMConfig
	 */
	public static function setDefaultInstance( ARMModuleInterface $instance ) {
		self::$default_instance = $instance ;
		return self::$default_instance;
	
	}
	
	/**
	 * 
	 * @return ARMConfig
	 */
	public static function getDefaultInstance() {
		if( !isset( self::$default_instance ) ) return NULL ;
		return self::$default_instance;
	}
	
	protected static function getConfigFolderName(){
		return   self::$APP_CONFIG_FOLDER . $_SERVER[ 'SERVER_NAME' ] ;
	}

	/**
	 * 
	 * @return ARMConfig
	 */
	 public static function getLastInstance() {
		return self::$instance ;
	}

	
	/**
	 * 
	 * @var ARMConfigVO
	 */
	private $configVO ;
	
	/**
	 * @return string
	 */
	public function getDefaultConfigLoaderDriver() {
		return $this->configVO->default_config_loader_driver ;
	}
	
	public function getRewriteHandler(){
		return $this->configVO->rewrite_handler ;
	}
	public function getArrayRequestRangeInit(){
		return $this->configVO->array_request_range_init*1 ;
	}
	public function getArrayRequestRangeMax(){
		return $this->configVO->array_request_range_max*1 ;
	}
	/**
	 * @return ARMConfigVO
	 */
	public function getParsedConfigData( $configResult ){
		$configError = "CONFIG Error:: ";
		
		$def = new ARMConfigVO();
		
		$view_module_list 		= ARMDataHandler::getValueByStdObjectIndex( $configResult , "view_module_list" ) ;
		
		$default_module_list 	= ARMDataHandler::getValueByStdObjectIndex( $def , "view_module_list" ) ;
		if( $view_module_list ){
			foreach( $view_module_list as $alias => $item_module ){
				if( isset( $default_module_list[ $alias ] ) ){
					$def->view_module_list[ $alias ] = $item_module ;
				}
			}
		}
		
		$this->configVO = ARMDataHandler::objectMerge(  $configResult , $def) ; 
		
		
		if( ! $this->configVO->folder_request_controler ){
			ARMDebug::error( $configError."folder_request_controler  can't be NULL" );
		}
		
		if( !$this->configVO->folder_modules_config ){
			ARMDebug::error( $configError."folder_modules_config  can't be NULL" );
		}
		if( ! $this->configVO->app_url ){
			ARMDebug::error( $configError."app_url  can't be NULL" );
		} else if( ! $this->configVO->root_url ){
			//não foi setado root path, poe um valor baseado na app_url
			$this->configVO->root_url = $this->configVO->app_url ;
		}
		
		$this->configVO->root_url 		= preg_replace("/(https?:\/\/)/", "", $this->configVO->root_url );
		$this->configVO->app_url 	= preg_replace("/(https?:\/\/)/", "", $this->configVO->app_url );
		
		if( ! $this->configVO->class_path_list ){
			ARMDebug::error( $configError."class_path_list  can't be NULL" );
		} elseif ( count($this->configVO->class_path_list) < 1){
			ARMDebug::error( $configError."class_path_list  must have more itens" );
		} else {
			$this->configVO->class_path_list[] = $this->configVO->folder_request_controler ;
			
		}
		
		
		
		
		if( $this->configVO->dev )
			error_reporting(E_ALL);
		
		
		return $this->configVO ;
	}

	
	/**
	 * 
	 * @return boolean
	 */
	public function useController(){
		return $this->configVO->use_controller ? TRUE : FALSE ;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function useView(){
		return $this->configVO->use_view ? TRUE : FALSE ;
	}
	
	public function getViewModuleList(){
		return $this->configVO->view_module_list ;
	}
	
	/**
	 * define se está em ambiente de dev
	 * @var boolean
	 */
	
	public function isDev(){
		return ( $this->configVO->dev  == true);
	}
	
	/**
	 * 
	 * @return string class name
	 */
	public function getDefaultController(){
		
		return $this->configVO->default_controller ;
	}
	/**
	 * 
	 * @return ARMRequestAccessControllInterface class name (string)
	 */
	public function getRequestAccessControll(){
		return $this->configVO->request_access_controll ;
	}
	public function getFolderRequestController(){
		return $this->configVO->folder_request_controler;
	}
	

	public function getDefaultRequestResultType(){
		return $this->configVO->default_request_return_type;
	}
	/**
	 * (pt-br) Retorna do config o nome da classe que resolve o resultado de data no tipo requerido
	 * @return string
	 */
	public function getHttpReturnIndentifierModule(){
		return $this->configVO->http_return_indentifier_module;
	}
########################################## <DIR_LIST> ##########################################
	
	/**
	 * Used primary by the ARMClassHunter to locale Classes
	 * @return array
	 */
	public function getAllClassPath(){
		return $this->configVO->class_path_list ;
	}

########################################## </DIR_LIST> ##########################################
	
########################################## <APP_TEMP_DIR> ##########################################
		
	public function getTempFolder($relative_file_path = ""){
		return ARMDataHandler::removeDoubleBars( $this->configVO->temp_folder ."/$relative_file_path");
	}
	
########################################## </APP_TEMP_DIR> ##########################################
	
	public function getFolderModulesConfig(){
		
		return $this->configVO->folder_modules_config ;
		
	}	
	
	
	public function viewResolverByPath(){
		return $this->configVO->view_resolver_by_path;
	}
	
    /**
     * @param $relative_url
     * @param $raw sem trocar https e etc
     * @return string
     */
    public function getRootUrl($relative_url = '', $raw = FALSE ){
    	if( $raw )
    		return $this->configVO->root_url ;
    	
    	if( ! $this->configVO ){
    		ARMDebug::print_r( $this->configVO ) ;
    		throw new ErrorException( "ConfigVO não encontrado" ) ;
    	}
    	// @TODO: FIX it! @UPGRADE! pool para não fazer o 'calculo' varias vezes
	    $SSL = "" ;//isset( $_SERVER["HTTPS"] ) ? "s" :  "" ;
		return "http$SSL://".ARMDataHandler::removeDoubleBars( $this->configVO->root_url ."/". $relative_url);
	}
	
	public function getAppUrl($relative_url = '', $raw = FALSE ){
    	if( $raw )
    		return $this->configVO->app_url ;
    	
    	// @TODO: FIX it! @UPGRADE! pool para não fazer o 'calculo' varias vezes
    	$SSL = "" ;//isset( $_SERVER["HTTPS"] ) ? "s" :  "" ;
		return "http$SSL://".ARMDataHandler::removeDoubleBars( $this->configVO->app_url ."/". $relative_url);
	}

	/**
	 * Retorna o caminho da pasta da aplicação instalada
	 * @param string $relativeFolder
	 * @return string
	 */
	public function getAppPath( $relativeFolder = "" ){
		return ARMDataHandler::removeDoubleBars( $this->configVO->app_path ."/". $relativeFolder);

	}
}

