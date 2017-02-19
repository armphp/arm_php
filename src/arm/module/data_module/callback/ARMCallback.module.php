<?php

/**
 * 
 * @author renatomiawaki
 *
 */
class ARMCallbackModule extends ARMBaseModuleAbstract{
	//variaveis de regra de negócio
	const TASK_STATUS_todo 		= 0 ;
	const TASK_STATUS_doing 	= 1 ;
	const TASK_STATUS_done 		= 2 ;

	const CALLBACK_TYPE_module 		= "module";
	const CALLBACK_TYPE_webservice 	= "webservice";
	
	/**
	 * 
	 * @param unknown $callbackVO
	 * @return ARMReturnResultVO
	 */
	public function createCallback( $callbackVO , $useDatabase = TRUE){
		
		$Result = $this->validateCallback( $callbackVO );
		
		if(!$Result->success){
			return $Result ;
		}
		
		if(!$useDatabase){
			return $this->createMemoryCallback( $callbackVO );
		}
		$callbackVO->slug = ( $callbackVO->slug === NULL ) ? ARMMysqliModule::DATA_NULL : $callbackVO->slug ;
		
		$CallbackEntity = ARMCallbackModelGateway::getInstance()->getEntity() ;
		$CallbackEntity->fetchObject( $callbackVO ) ;
		
		$Return = $CallbackEntity->commit( TRUE ) ;
		 
		return $Return ;
	}

	public static function getConfigClassName()
	{

		return "ARMCallbackConfigVO" ;
	}

	public function setConfig($ob)
	{
		$config = new ARMCallbackConfigVO() ;
		$config->parseObject( $ob ) ;
		parent::setConfig( $config ) ;
	}


	/**
	 * 
	 * @var array de callbacks vo
	 */
	protected $__callbacks = array();
	
	/**
	 * cria um callback para ficar na memória
	 * //quando um callback de slug null é criado, significa que ele não se importa com o slug portanto
	 * @param ARMCallbackVO $callbackVO
	 */
	protected function createMemoryCallback( ARMCallbackVO $callbackVO ){
		if( FALSE ) $callbackVO = new ARMCallbackVO();
		if(! isset( $this->__callbacks[$callbackVO->trigger_action])){
			$this->__callbacks[$callbackVO->trigger_action] = array();
		}
		$slug = ( $callbackVO->slug === NULL ) ? ARMMysqliModule::DATA_NULL : $callbackVO->slug ;
		if(! isset( $this->__callbacks[$callbackVO->trigger_action][$slug] ) ){
			$this->__callbacks[$callbackVO->trigger_action][$slug] = array();
		}
		$this->__callbacks[$callbackVO->trigger_action][$slug][] = $callbackVO;
	}
	protected function memoryTrigger( $action , $slug = NULL){
		
		if(!isset($this->__callbacks[$action][ARMMysqliModule::DATA_NULL]) && !isset($this->__callbacks[$action][$slug])){
			return false ;
		}
		
		$callbacks 	= array() ;
		if(isset($this->__callbacks[$action][ARMMysqliModule::DATA_NULL])){
			//adiciona todos os callbacks que ignoram slug na lista de chamada
			$callbacks 	= $this->__callbacks[$action][ARMMysqliModule::DATA_NULL];
		}
		if($slug != NULL && isset($this->__callbacks[$action][$slug])) {
			$callbacks = array_merge($callbacks, $this->__callbacks[$action][$slug] ) ;
		}
		//@TODO: 8 - filtrar para ver se ( start_date == null or start_date <= now() )
		$instance 	= self::getInstance() ;
		foreach($callbacks as $call){
			$instance->executeCallback( $call ) ;
		}
	}
	/**
	 * 
	 * @param ARMCallbackVO $callbackVO
	 * @return ARMReturnResultVO
	 */
	public function validateCallback( $callbackVO ){
		$Result = new ARMReturnResultVO() ;
		$Result->success = ( $callbackVO->callback_type == self::CALLBACK_TYPE_module || $callbackVO->callback_type == self::CALLBACK_TYPE_webservice ) ;
		if( !$Result->success ){
			$Result->addMessage( "Envie o tipo correto de callback type" ) ;
		}
		$Result->success = ( $callbackVO->callback_type == self::CALLBACK_TYPE_module && $callbackVO->callback_module == "" ) ;
		if( !$Result->success ){
			$Result->addMessage( "Envie o nome do Modulo" ) ;
		}
		$Result->success = ( $callbackVO->callback_type == self::CALLBACK_TYPE_webservice && $callbackVO->callback_url == "" );
		if( !$Result->success ){
			$Result->addMessage( "Envie uma url para criar uma trigger do tipo webservice " ) ;
		}
		
		$Result->success = ( $callbackVO->trigger_action != "") ;
		if( !$Result->success ){
			$Result->addMessage( "Envie trigger action" ) ;
		}
		return $Result;
	}
	public function getConfig(){
		return $this->_config ;
	}
	/**
	 * 
	 * @return ARMCallbackModule
	 */
	public static function getInstance( $alias = NULL ){
		return parent::getInstance( $alias );
	}
	/**
	 * Esse metodo é para uso do cron
	 * Apenas marca o que precisa ser executado
	 */
	public function executeCronCallbacks(){
		//@TODO: executar todas as callbacks do tipo cron
		$VO = new ARMCallbackVO();
		//$VO->trigger_action 	= $action ;
// 		$VO->slug 				= ARMMysqliModule::DATA_NULL ;
		$VO->active				= 1 ;
		$VO->is_cron_task		= 1 ;
		
		$VO->task_status		= self::TASK_STATUS_todo ;
		//busca os triggers cadastrados para entrarem como null mesmo
		$ResultData = ARMCallbackDAO::getInstance()->selectByVO( $VO , 11 ) ;
		ARMDebug::li( "executeCronCallbacks" ) ;
		ARMDebug::dump( $ResultData ) ;
		
		if( $ResultData->hasResult() ){
			$callbackList = $ResultData->result ;
			
			$array_ids = ARMDataHandler::arrayObjectToArrayIds( $callbackList ) ;
			
			ARMCallbackModelGateway::getInstance()->getDAO()->setCallbackListStatus(  $array_ids ,  self::TASK_STATUS_doing ) ;
			
			foreach($callbackList as $callbackVO ) {
				/* @var $callbackVO ARMCallbackVO */
				//quer que seja executado na hora, entao executa
				$this->executeCallback($callbackVO);
			}
		}
		
		if( count( $ResultData->result ) > 10 ) {
			$this->executeCronCallbacks();
		}
	}
	/**
	 * triga um evento na trigger
	 * @param string $action
	 */
	public function trigger( $action , $slug = NULL ){
		//procura as trigger na memória
		self::memoryTrigger( $action , $slug );
		//as que ainda não foram para a lista do cron ou resolvidas
		ARMDebug::li( "trigger" ) ;
		$VO = new ARMCallbackVO();
		$VO->trigger_action 	= $action ;
		$VO->slug 				= $slug ;
		$VO->active				= 0 ;
		$VO->task_status		= self::TASK_STATUS_todo ;

		if( ! $slug ){
			$VO->slug 				= ARMMysqliModule::DATA_NULL ;
			//busca os triggers cadastrados para entrarem como null mesmo
			$ResultData = ARMCallbackDAO::getInstance()->selectCurrentsByVO( $VO ) ;
			if( $ResultData->hasResult() ){
				$this->executeCallbackList( $ResultData->result );
			}
			return ;
		}

		$ResultData = ARMCallbackDAO::getInstance()->selectCurrentsByVO( $VO ) ;
		ARMDebug::dump( $ResultData ) ;
		if( $ResultData->hasResult() ){
			$this->executeCallbackList( $ResultData->result );
		}
	}
	/**
	 * Recebe um array de ARMCallbackVO
	 * @param ARMCallbackVO[] $callbackList
	 */
	protected function executeCallbackList( $callbackList ){
		if( ! is_array( $callbackList ) ){
			return ;
		}
		ARMDebug::li( "executeCallbackList" ) ;
		ARMDebug::dump( $callbackList ) ;

		foreach( $callbackList as $callbackVO ){
			if( FALSE ) $callbackVO = new ARMCallbackVO();
			
			if($callbackVO->is_cron_task > 0 ){

				if($callbackVO->execution_count == 0 || $callbackVO->execution_count < $callbackVO->execution_limit){
					//é pro cron e ele ainda nao está fazendo, só ativa
					$callbackVO->executions_remain += ARMDataIntHandler::forceInt( $callbackVO->executions_remain ) + 1 ;
					$callbackVO->active 			= 1 ;
					$callbackVO->task_status 		= self::TASK_STATUS_todo ;
					//cria ou atualiza uma callbackVO
					ARMCallbackDAO::getInstance()->commitVO( $callbackVO ) ;
				}
			} else {
				if($callbackVO->execution_limit > 0){
					//se não for infinito, da um lock da tarefa para não ser mais listada enquando a tarefa é executada
					$callbackVO->task_status 		= self::TASK_STATUS_doing;
					ARMCallbackDAO::getInstance()->updateVO( $callbackVO ) ;
				}
				//quer que seja executado na hora, entao executa
				$this->executeCallback($callbackVO);
			}
		}
	}
	/**
	 * executa um callback se o task_status não for == TASK_STATUS_done
	 * @param $callbackVO ARMCallbackVO
	 */
	public function executeCallback( &$callbackVO ){

		if($callbackVO->task_status == self::TASK_STATUS_done){
			//ta done, não executa
			return false;
		}
		if($callbackVO->callback_type == self::CALLBACK_TYPE_module){
			$callbackVO = $this->executeCallbackModule($callbackVO);

		} else {
			$callbackVO = $this->executeCallbackWebservice($callbackVO);

		}

		//executado, entao soma as vezes que ele foi executado e diminui a quantidade de vezes que ele foi executado
		$callbackVO->execution_count 	+= 1 ;
		$callbackVO->executions_remain 	-= 1 ;
		$callbackVO->executions_remain 	= ($callbackVO->executions_remain < 0)?0:$callbackVO->executions_remain;

		if( $callbackVO->execution_limit > 0 ){
			
			//tem limite para execução
			if($callbackVO->execution_count > $callbackVO->execution_limit ){
				//passou dos limites
				$callbackVO->task_status = self::TASK_STATUS_done ;
			}
		}
		if( $callbackVO->id ) ARMCallbackDAO::getInstance()->updateVO( $callbackVO ) ;
	}
	protected function executeCallbackModule( &$callbackVO ){
		if(FALSE) $callbackVO = new ARMCallbackVO() ;
		
		$ModuloClassName 	= $callbackVO->callback_module;
		$methodName 		= ($callbackVO->callback_method == "")?"callback":$callbackVO->callback_method;
		$parameter 			= $callbackVO->data_info;
		if($ModuloClassName == ""){
			return FALSE;
		}
		ARMClassIncludeManager::load( $ModuloClassName ) ;
		
		$ModuleInstance  = call_user_func("{$ModuloClassName}::getInstance" ) ;

		if( !method_exists( $ModuleInstance ,  $methodName ) ){
			ARMDebug::dbLog( __CLASS__ . __FUNCTION__ . " {$ModuloClassName}->{$methodName} nao existe." ,  $parameter ) ; 
		}else{
			$return = $ModuleInstance->{$methodName}( $parameter ) ;
			
			if($return && $callbackVO->is_conditional_auto_done){
				$callbackVO->task_status = self::TASK_STATUS_done;
				ARMCallbackDAO::getInstance()->commitVO( $callbackVO ) ;
			}
		}
		return $callbackVO;
	}

	/**
	 * @param $callbackVO ARMCallbackVO
	 * @return bool
	 */
	protected function executeCallbackWebservice( &$callbackVO ){
		include_once 'library/Zend/http/Client.php' ;
		if( !ARMValidation::validateUrl( $callbackVO->callback_url ) ){
			ARMDebug::dbLog( "CALLBACK ERROR , url inválida " , $callbackVO) ;
			return $callbackVO;
		}
		$client = new Zend_Http_Client( $callbackVO->callback_url  );
		
		$client->setParameterPost('dataInfo',  					$callbackVO->data_info ) ;
		
		$response = $client->request('POST')  ;
		if( $response->getStatus() != 200 ){
			ARMDebug::dbLog( __CLASS__ . __FUNCTION__ , $callbackVO ) ;
			
		}else{
			//sucesso
			if( $callbackVO->is_conditional_auto_done ){
				$callbackVO->task_status 	= self::TASK_STATUS_done;
				ARMCallbackDAO::getInstance()->updateVO( $callbackVO ) ;
			}
		}
		return $callbackVO;
	}
}