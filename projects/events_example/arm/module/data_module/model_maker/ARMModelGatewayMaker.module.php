<?php


/**
 * @author Renato Miawaki
 * @date 15/3/2013
 * 
 * @TODO: refazer no novo esquema e padrões
 * 
 */
class ARMModelGatewayMakerModule extends ARMBaseModuleAbstract{
	/**
	 * 
	 * @param string $className nome Base da classe
	 * @param string $folderTarget
	 * @return ARMReturnResultVO
	 */
	public static function make( $className, $folderTarget, $override = TRUE ){
		$ReturnResultVO = new ARMReturnResultVO();
		
		if($folderTarget){
			$fileName = $className."Model.gateway.php";
			
			$templateContent = self::getTemplateClassContent($className);
			//o nome da classe
			$ReturnResultVO->result = $className . "ModelGateway" ;
				try{
					//gravando arquivo
					$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
					ARMDataHandler::createRecursiveFoldersIfNotExists($folderTarget);

					if( $override || !file_exists( $folderTarget . $fileName ) ){
						ARMDataHandler::writeFile($folderTarget, $fileName, $templateContent, "w+");
						chmod($folderTarget.$fileName , 0777);
						$ReturnResultVO->success = TRUE;
						$ReturnResultVO->addMessage("sucesso ao criar DataGateway");
					} else {
						$ReturnResultVO->addMessage("arquivo já existe");
					}
				} catch(Error $error){
					$ReturnResultVO->success = FALSE;
					$ReturnResultVO->addMessage(var_dump($error, TRUE));
				}
			
		} else {
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("envie o folder");
		}

		return $ReturnResultVO;
	}
	/**
	 * Lista os bancos de dados de uma determinada conexão 
	 * @return ARMReturnResultVO
	 */
	public static function listDatabase( $alias_mysql = NULL ){
		$Return = new ARMReturnResultVO() ;
		@$result = ARMMysqliModule::getInstance( $alias_mysql )->query("SHOW databases ") ;
		
		$Return->result 	= array() ;
		if( $result && $result->hasResult() ){
			$result->fetchAll() ;
			$Return->success = TRUE ;
			$Return->result = self::fetchDbNameResult( $result->result ) ;
			
		}
		$Return->success 	= $result->success ;
		
		
		if( !$result || ! $result->success ) $Return->addMessage( "erro ao conectar/listar com banco de dados" ) ;
		
		return $Return ;
	}
	/**
	 * 
	 * @param unknown $array
	 * @return array
	 */
	protected static function fetchDbNameResult( $array ){
		$result = array() ;
		if( $array ) {
			$listaNegada = array("mysql" , "information_schema", "cdcol") ;
			foreach( $array as $item ){
				if( in_array( $item->Database , $listaNegada ) ){
					continue ;
				}
				$result[] = $item->Database ;
			}
		}
		
		return $result ;
	}
	/**
	 * Vai pegar a instancia marcada como default, se não tiver, pega a padrão
	 * @param ARMModelGatewayConfigToMakeVO $ConfigInfo
	 * @return ARMReturnResultVO
	 */
	public static function makeByConfig( ARMModelGatewayConfigToMakeVO $ConfigInfo ){
		$returnResultVO = new ARMReturnResultVO() ;
		$returnResultVO->success = TRUE ;
		$results = array() ;
		foreach($ConfigInfo->tables as $table_name ){
			
			$folder 		= $ConfigInfo->targetFolder."/$table_name/";
			
			$className 		= $ConfigInfo->prefixClassName.ARMDataHandler::urlFolderNameToClassName($table_name);
			
			$itemResult = self::makeAll($table_name, $folder, $className, $ConfigInfo->forceOverride );
			$results[ $table_name ] = $itemResult;
			$message = ($itemResult->success)?" its success":" its fail";
			$returnResultVO->addMessage("* MakeModule has maked $table_name and $message :");
			//ao tornar false, fica false pra sempre
			$returnResultVO->success = ( ! $itemResult->success ) ? FALSE : $returnResultVO->success ;
		}
		
		$returnResultVO->result = $results;
		return $returnResultVO ;
	}
	/**
	 * Metodo para criar DAO, VO e DataFacade de uma tabela
	 * @param unknown $tableName
	 * @param unknown $folderTarget
	 * @param unknown $baseClassName
	 */
	public static function makeAll($tableName, $folderTarget, $baseClassName, $override = FALSE){
		
		$return = new ARMReturnResultVO();
		
		$attributes = self::getAttributes( $tableName );
		$return->success = TRUE;
		$return->result = array() ;
			
			$gatewayResult 		= self::make( $baseClassName, $folderTarget, $override ) ;
			$gatewayClassName 	= $gatewayResult->result ;
			$return->result["ModelGateway"] = $gatewayResult ;
			if( ! $return->result["ModelGateway"]->success ) $return->success = $return->result["ModelGateway"]->success ;
		
			$return->result["VO"] = ARMModelVoMaker::makeFromTable( $tableName, $folderTarget, $attributes, $baseClassName, $override );
			if( ! $return->result["VO"]->success ) $return->success = $return->result["VO"]->success ;
		
			//criando a ARMBaseEntityAbstract
			$return->result["BaseEntity"] = ARMEntityMaker::makeBase( $baseClassName, $gatewayClassName , $folderTarget."/bases/", $attributes , $override );
			if( ! $return->result["BaseEntity"]->success ) $return->success = $return->result["BaseEntity"]->success ;
		
			//criando a entity editavel
			$return->result["Entity"] = ARMEntityMaker::make( $baseClassName , $folderTarget, $attributes , $override );
			if( ! $return->result["Entity"]->success ) $return->success = $return->result["Entity"]->success ;
		
		
			$return->result["BaseDAO"] = ARMDaoMaker::makeBase($tableName, $baseClassName, $folderTarget."/bases/", $attributes, $override );
			if( ! $return->result["BaseDAO"]->success ) $return->success = $return->result["BaseDAO"]->success ;


			$return->result["DAO"] = ARMDaoMaker::make($tableName, $baseClassName, $folderTarget, $attributes, $override);
			if( ! $return->result["DAO"]->success ) $return->success = $return->result["DAO"]->success ;

			//agora faz o make do module
			$return->result["BaseModule"] = ARMModuleMaker::makeBase( $baseClassName, $gatewayClassName , $folderTarget."/bases/" , $override );
			if( ! $return->result["BaseModule"]->success ) $return->success = $return->result["BaseModule"]->success ;

			//criando a entity editavel
			$return->result["Module"] = ARMModuleMaker::make( $baseClassName , $folderTarget, $override );
			if( ! $return->result["Module"]->success ) $return->success = $return->result["Module"]->success ;

		return $return;
		
	}
	protected static function getAttributes( $tableName ){
		
		$attributes = array();
		$dbInterface = ARMMysqliModule::getDefaultInstance();
		if( ! $dbInterface ){
			$dbInterface = ARMMysqliModule::getInstance();
		}
		if( ! $dbInterface ){
			throw new ErrorException( "Erro ao pegar instancia de banco de dados" ) ;
		}
		
		$Result = $dbInterface->query("DESC `$tableName` ");
		
		if( ! $Result->success ){
			//ERRO
			throw new ErrorException(  "Erro ao acessar o banco de dados" , mysqli_errno() ) ;
		} else {
			$Result->fetchAll();
			foreach($Result->result as $row){
				$attributes[] = $row ;
			}
		}
		return $attributes ;
	}
	protected static function getTemplateClassContent($className, $sulfix = "ModelGateway"){
		$date = date( "d/m/Y h:m:i" );
		
		return "<?php
	/**
	* created by ARMModelGatewayMaker ( automated system ) 
	*
	* @date $date 
	* @baseclass ARMBaseSingletonAbstract 
	*/
	class {$className}{$sulfix} extends ARMBaseSingletonAbstract implements ARMModelGatewayInterface {
		/**
		* @return {$className}{$sulfix} 
		*/
		public static function getInstance( \$alias = \"\" ){
			return parent::getInstance( \$alias ) ;
		}
		/**
		* @return {$className}Entity
		*/
		function getEntity(){
			return new {$className}Entity() ;
		}
		/**
		* @return {$className}VO
		*/
		function getLinkVO(){
			return new {$className}VO() ;
		}
		/**
		* @return {$className}DAO
		*/
		function getDAO( \$alias = NULL ){
			//se nao foi enviado alias, tenta usar padrao
			if( ! \$alias ){
				\$default = {$className}DAO::getDefaultInstance() ;
				if( \$default ){
					return \$default ;
				}
				//se não foi setado default, vai buscar a instance por nada
			}
			return {$className}DAO::getInstance( \$alias ) ;
		}
	}
		";
	}
}