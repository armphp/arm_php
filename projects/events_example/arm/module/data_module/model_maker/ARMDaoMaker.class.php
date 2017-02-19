<?php

/**
 * @author Renato Miawaki
 * @date 15/3/2013
 *
 */ 
class ARMDaoMaker {
	
	/**
	 *
	 * @param string $className nome base da classe (sem dao, vo, data no nome)
	 * @param string $folderTarget
	 * @return ARMReturnResultVO
	 */
	public static function make($tableName, $className, $folderTarget, array $attributes , $override = FALSE ){
		$ReturnResultVO = new ARMReturnResultVO();
		//$row->Field, $row->Type
		if($folderTarget){
				
			$fileEditableName = $className.".DAO.php";
			$templateEditableContent 	= self::getTemplateEditableClassContent($className, $tableName, $attributes) ;
				
// 			var_dump( $templateEditableContent );
// 			die;
			
			try{
				//gravando arquivo
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				ARMDataHandler::createRecursiveFoldersIfNotExists($folderTarget);
				
				if( !file_exists( $folderTarget . $fileEditableName ) ){
					ARMDataHandler::writeFile($folderTarget, $fileEditableName, $templateEditableContent, "w+");
					chmod($folderTarget.$fileEditableName , 0777) ;
		
					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("sucesso ao criar Entity");
				} else {
					$ReturnResultVO->addMessage("arquivo já existe");
				}
			} catch(Error $error){
				$ReturnResultVO->success = FALSE;
				$ReturnResultVO->addMessage(var_dump($error, TRUE));
			}
				
		} else {
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("envie o tableName e folder");
		}
	
		return $ReturnResultVO;
	}
	
	/**
	 * 
	 * @param string $className nome base da classe (sem dao, vo, data no nome)
	 * @param string $folderTarget
	 * @return ARMReturnResultVO
	 */
	public static function makeBase( $tableName, $className, $folderTarget, array $attributes, $override = FALSE ){
		$ReturnResultVO = new ARMReturnResultVO();
		
		if($folderTarget){
			$fileName = "ARMBase".$className.".DAO.php";
			$templateContent = self::getTemplateClassContent($className, $tableName, $attributes);
			//
			try{
				//gravando arquivo
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				ARMDataHandler::createRecursiveFoldersIfNotExists($folderTarget);

				if( $override || !file_exists( $folderTarget . $fileName ) ){
					ARMDataHandler::writeFile($folderTarget, $fileName, $templateContent, "w+");
					chmod($folderTarget.$fileName , 0777);
					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("sucesso ao criar vo");
				} else {
					$ReturnResultVO->addMessage("arquivo já existe");
				}
			} catch(Error $error){
				$ReturnResultVO->success = FALSE;
				$ReturnResultVO->addMessage(var_dump($error, TRUE));
			}
		} else {
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("envie o tableName e folder");
		}
		
		return $ReturnResultVO;
	}
	protected static function getTemplateAttributeContent($attributeName, $type){
		return "
		/**
		* type : $type
		*/
		const FIELD_".$attributeName." = '$attributeName';";
	}
	protected static function getTemplateClassContent($baseClassName, $table, $p_attributes){
		$attributes = "";
		foreach($p_attributes as $row) {
			$attributes .= self::getTemplateAttributeContent($row->Field, $row->Type);
		}
		
		$date = date( "d/m/Y h:m:i" );
return <<<STRING
<?php
	/**
	 * created by ARMDaoMaker ( automated system )
	 * ! Please, don't change this file
	 * insted change ARM{$baseClassName}DAO class
	 * $baseClassName
	 * @date $date 
	 */ 
	abstract class ARMBase{$baseClassName}DAOAbstract extends  ARMBaseDAOAbstract {
		
		protected \$TABLE_NAME = '$table';
		
		$attributes
		
		/**
		* @return {$baseClassName}DAO 
		*/
		public static function getInstance( \$alias = ""){
			return parent::getInstance( \$alias  ) ;
		}
		/**
		 *  @return {$baseClassName}DAO 
		 */
		public static function getInstaceByConfigVO( \$configVO , \$alias = self::DEFAULT_INSTANCE_NAME ){
			return parent::getInstaceByConfigVO( \$configVO , \$alias ) ;
		}
		/**
		 * @return {$baseClassName}DAO
		 */
		public static function getDefaultInstance() {
		 	return parent::getDefaultInstance() ;
		}
	}
STRING;
	}
	
	protected static function getTemplateEditableClassContent($baseClassName, $p_attributes){
		$date = date( "d/m/Y h:m:i" );
		
		return <<<STRING
<?php
	/**
	* created by ARMDaoMaker ( automated system )
	* Please, change this file
	* don't change ARMBase{$baseClassName}DAO class
	*
	* {$baseClassName}DAO
	* @date $date
	*/
	
class {$baseClassName}DAO extends ARMBase{$baseClassName}DAOAbstract {
	//put your changes and rewrited methods here
	//Good developers always comment theirs code
}
STRING;
	}
}