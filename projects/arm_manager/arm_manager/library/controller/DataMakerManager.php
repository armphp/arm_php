<?php

class DataMakerManager {
	/**
	 * 
	 * @var ARMModelGatewayConfigToMakeVO
	 */
	protected $configToMake ;
	/**
	 * 
	 * @var ARMDbConfigVO
	 */
	protected $configConnection ;
	
	/**
	 * @boolean
	 */
	protected $no_redirect ;
	public function __construct(){

		$this->configToMake 		= new ARMModelGatewayConfigToMakeVO() ;
		$this->configConnection		= new ARMDbConfigVO() ;
		$this->checkSavedValues() ;
		
		ARMDebug::ifPrint( $_SESSION , "maker") ;
		ARMDebug::ifPrint( $this->configConnection , "maker") ;
		if( ARMNavigation::getVar( "no_redirect" ) ){
			$this->no_redirect = TRUE ;
		}
			
		
	}
	private function checkSavedValues(){
		ARMSession::start() ;
		
		$this->configToMake->forceOverride 			= ARMSession::getVar( "data_maker_forceOverride" ) ;
		$this->configToMake->mysqli_instance_alias 	= ARMSession::getVar( "data_maker_mysqli_instance_alias" ) ;
		$this->configToMake->prefixClassName 		= ARMSession::getVar( "data_maker_prefixClassName" ) ;
		
		$this->configToMake->tables 				= ARMSession::getVar( "data_maker_tables" ) ;
		$this->configToMake->targetFolder 			= ARMSession::getVar( "data_maker_targetFolder" ) ;
		$this->configToMake->downloadZip 			= 1 ;

		$this->configConnection->host				= ARMSession::getVar( "data_db_host" ) ;
		$this->configConnection->server				= ARMSession::getVar( "data_db_server" ) ;
		
		$this->configConnection->password			= ARMSession::getVar( "data_db_password" ) ;
		$this->configConnection->user				= ARMSession::getVar( "data_db_user" ) ;
		$this->configConnection->database			= ARMSession::getVar( "data_db_database" ) ;
		
		
	}
	public function init(){
		session_destroy() ;
		ARMSession::start() ;
		
		$Return = new ContentDataMakerResultVO() ;
		
		$Return->configToMake = $this->configToMake ;
		
		return $Return ;
	}
	public function saveConnection(){
		
		$this->configToMake = new ARMModelGatewayConfigToMakeVO() ;
		
		$Return = new ContentDataMakerResultVO() ;
		
		if( $this->checkModuleToDoIt( ARMNavigation::getVar( "driver_module" ) ) ){
			
			$ResultTestConnection = $this->testConnection() ;
			
			if( $ResultTestConnection->result->success ){
				
				//ok, rediresiona para o próximo passo de navegação
				if( $this->no_redirect ){
					$Return->result->success = TRUE ;
					$Return->configToMake = $this->configToMake ;
					return $Return ;
				}
				ARMNavigation::redirect( "data_maker_manager/database/" ) ;
				
			}
			$Return->configToMake = $this->configToMake ;
			$Return->result = $ResultTestConnection->result ;
			return $Return ; 
			
		}
		
		$Return->result = new ARMReturnResultVO() ;
		$Return->configToMake = $this->configToMake ;
		$Return->result->addMessage( "Modulo selecionado não suportado " ) ;
		
		return $Return ;
	}
	private function connect( $alias ){
		$ReturnResult = new ARMReturnResultVO() ;
		
		//echo "database ::::: ". $this->configConnection->database ."   >>  ".$this->configConnection->getDBName();
		//ARMDebug::print_r( $this->configConnection ) ;
		@$ResultConnection = ARMMysqliModule::getInstaceByConfigVO( $this->configConnection , $alias ) ;

		//ARMDebug::print_r( $ResultConnection ) ;
		if( $ResultConnection && $ResultConnection->testConnection()){
				$ReturnResult->success = TRUE ;
				$ReturnResult->addMessage( "Sucesso ao conectar" ) ;
				return $ReturnResult ;
			 
		}
		
		$ReturnResult->addMessage( "Erro ao conectar com os dados enviados" ) ;
		return $ReturnResult ;
	}
	/**
	 * 
	 * @return ContentDataMakerResultVO
	 */
	public function testConnection(){
		
		$Return = new ContentDataMakerResultVO() ;
		if( $this->checkModuleToDoIt( ARMNavigation::getVar( "driver_module" ) ) ){
			
			if( ARMNavigation::getVar( "user" ) && ARMNavigation::getVar( "host" ) ){
				ARMSession::setVar( "data_db_host", ARMNavigation::getVar( "host" ) ) ;
				ARMSession::setVar( "data_db_user", ARMNavigation::getVar( "user" ) ) ;
				ARMSession::setVar( "data_db_password", ARMNavigation::getVar( "password" ) ) ;
			} 	
			$this->checkSavedValues() ;
			$Return->configToMake = $this->configToMake ;
			$Return->result = $this->connect( $this->getAliasByData() ) ;
			
			return $Return ;
				
		}
		$Return->configToMake = $this->configToMake ;
		$Return->result = new ARMReturnResultVO() ;
		$Return->result->addMessage( "Modulo selecionado não suportado " ) ;
		
		return $Return ;
	}
	/**
	 * Retorna um alias baseado nos dados de conexão enviado
	 * @return string
	 */
	private function getAliasByData(){
		return ARMDataHandler::removeSpecialCharacters( $this->configConnection->host )
		."_". $this->configConnection->user
		."_". $this->configConnection->password ;
		
	}
	/**
	 * 
	 * @return ContentDataMakerResultVO
	 */
	public function database(){
		$this->checkSavedValues() ;
		$Return = new ContentDataMakerResultVO() ;
		
		$this->connect( $this->getAliasByData() ) ;
		
		$ResultDb = ARMModelGatewayMakerModule::listDatabase( $this->getAliasByData() ) ;
		
		$Return->result = $ResultDb ;
		
		$Return->databases = $ResultDb->result ;
		$Return->configToMake = $this->configToMake ;
		return $Return ;
	}
	public function saveDatabase(){
		$Result = new ARMReturnResultVO() ;
		//salva o nome da base de dados
		ARMSession::setVar( "data_db_database", ARMNavigation::getVar( "database" ) ) ;
		$this->configConnection->database = ARMNavigation::getVar( "database" ) ;
		//consolida valores da session
		$this->checkSavedValues() ;
		
		
		//verifica a conexão
		$ContentDataMakerResultVO = $this->testConnection() ;
		
		if( $ContentDataMakerResultVO->result->success ){
			//sucesso ao conectar
			//se foi setado dados de configuraçao
			if( ARMNavigation::getVar( "save_config" ) ){
				//agora precisa verificar os dados de configuração
				return $this->saveConfig();
			}
			if( $this->no_redirect ){
				$Result->success = TRUE ;
				return $Result ;
			}
			//redirect to
			ARMNavigation::redirect( "data_maker_manager/config/" ) ;
		}
		//erro na conexao
		
		$Result = $ContentDataMakerResultVO->result ;
		
		return $Result ;
	}
	public function saveConfig(){
		$Result = new ARMReturnResultVO() ;
		$prefix = ARMNavigation::getVar( "prefix_name" ) ;
		$prefix = ( ! $prefix ) ? "" : $prefix ;

		$temp_folder = md5(time());
		ARMSession::setVar( "data_maker_forceOverride" , 	ARMNavigation::getVar( "force" ) ) ;
		ARMSession::setVar( "data_maker_prefixClassName" , 	$prefix ) ;
		ARMSession::setVar( "data_maker_targetFolder" , 	$temp_folder ) ;
		ARMSession::setVar( "downloadZip" , 				1 ) ;
		
		$this->checkSavedValues() ;
		
		//verfica se quer salvar tudo
		//verifica se quer que faça para todas as tabelas
		if( ARMNavigation::getVar( "all" ) ){
			//sem quer para todas, salva todas as tabelas na session e pula um passo
			//lista as tabelas
			$tables = $this->selectTables() ;
			if( count( $tables ) > 0 ){
				//tem tabelas para listar
				$_POST["tables"] = $tables ;
				$this->saveTables() ;
			}
			
			if( $this->no_redirect ){
				$Result->success = FALSE ;
				$Result->addMessage( "No tables founded" ) ;
				return $Result ;
			}
			
			//Erro . não conseguiu encontrar tabelas no banco de dados.
			ARMNavigation::redirect( "data_maker_manager/database/error.no_tables_founded/" ) ;
		}
		if( $this->no_redirect ){
			$Result->success = TRUE ;
			$Result->addMessage( "OK" ) ;
			return $Result ;
		}
		//nao quer todos, manda ele para selecionar tabelas
		ARMNavigation::redirect( "data_maker_manager/tables/" ) ;
	}
	private function selectTables(){
		$return = array() ;
		$instance = ARMMysqliModule::getInstaceByConfigVO( $this->configConnection , $this->getAliasByData() ) ;
		
// 		ARMDebug::li( "selectTables" ) ;
// 		ARMDebug::print_r( $instance ) ;
		
		$ReturnData = $instance ->query( " SHOW tables " ) ;
		
		if( $ReturnData->success && $ReturnData->hasResult() ){
			//tem tabelas para listar
			$ReturnData->fetchAll() ;
			$return = $this->fetchResultTables( $ReturnData->result ) ;
		}
		return $return ;
	}
	public function tables(){
		$Return = new ContentDataMakerResultVO() ;
		
		$Return->tables = $this->selectTables() ;
		
		return $Return ;
	}
	public function saveTables(){
		$Result = new ContentDataMakerResultVO() ;
		$tables = ARMNavigation::getVar( "tables" ) ;
		ARMSession::setVar( "data_maker_tables", $tables ) ;
		//todas as tabelas salvas e todos os dados para o maker pronto
		if( $this->no_redirect ){
			$Result->success = TRUE ;
			$Result->addMessage( "Tables saved" ) ;
			return $Result ;
		}
		//envia para doit
		ARMNavigation::redirect( "data_maker_manager/do_it/" ) ;
		
	}
	protected function fetchResultTables( $arrayDbResult ){
		$result = array() ;
		if( $arrayDbResult ){
			foreach( $arrayDbResult as $item ){
				$attributes = ARMClassHandler::getAttributes( $item ) ;
				if( isset( $attributes[0] ) ){
					$attr = $attributes[0] ;
					$result[] = $item->$attr ;
				}
			}
		}
		return $result ;
	}
	/**
	 * Chega se o modulo escolhido tem tratamento na classe
	 * @param string $driver_module nome do Modulo do triver que será utilizado
	 * @return boolean
	 */
	protected function checkModuleToDoIt( $driver_module ){
		$method_name = "doIt".$driver_module;
		return ( ARMClassHandler::hasMethod( $this , $method_name ) ) ? $method_name : NULL ;
	}
	/**
	 * Metodo para verificar se o tipo de maker desejado existe 
	 * @return ARMReturnResultVO
	 */
	public function doIt(){
		//no momento nem tem outro modulo, pq se fosse fazer mesmo para outros modulos teria que ser muito diferente
		
		$Return = new ARMReturnResultVO() ;
		$driver_module = "ARMMysqliModule" ;
		//verifica o nome do metodo interno pelo tipo de driver_module
		 
		
		if( $method_name = $this->checkModuleToDoIt( $driver_module ) ){
			//achou o metodo, chama o metodo para que seja executado o que for preciso
			
			return $this->$method_name();
		}
		$Return->addMessage( "Módulo de driver não encontrado" ) ;
		
		return $Return ;
	}
	protected $aliasLink ;
	/**
	 * 
	 * Maker para ARMMysqliModule
	 * 
	 */
	protected function doItARMMysqliModule(){
		$Return = new ContentDataMakerResultVO() ;
		$this->checkSavedValues() ;
		
		$this->configToMake->mysqli_instance_alias = $this->getAliasByData() ;
		//@TODO: fazer a opção override ser respeitada
		$Return->configToMake = $this->configToMake ;
		
		$instance = ARMMysqliModule::getInstaceByConfigVO( $this->configConnection , $this->getAliasByData() ) ;
		ARMMysqliModule::setDefaultInstance( $instance ) ;
		$Return->result = ARMModelGatewayMakerModule::makeByConfig( $this->configToMake ) ;
		
		$Return->driver_module = "ARMMysqliModule" ;
		
		return $Return ;
		
	}
	/**
	 * Pra fazer com todas as tabelas do banco
	 */
	protected function doItAllARMMysqliModule(){
		
	}
}