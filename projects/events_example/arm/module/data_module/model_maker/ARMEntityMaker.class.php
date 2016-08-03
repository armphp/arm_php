<?php

/**
 * @author Renato Miawaki
 * @date 15/3/2013
 *
 *exemple: http://localhost/indikabem/admin/make_vo/?table_name=city&folder=library/
 */ 
class ARMEntityMaker {
	/**
	 * 
	 * @param string $className nome base da classe (sem dao, vo, data no nome)
	 * @param string $folderTarget
	 * @return ARMReturnResultVO
	 */
	public static function make($className, $folderTarget, array $p_attributes , $override = FALSE ){
		$ReturnResultVO = new ARMReturnResultVO();
		//$row->Field, $row->Type
		if($folderTarget){
			
			$fileEditableName = $className.".entity.php";
			$templateEditableContent 	= self::getTemplateEditableClassContent($className, $p_attributes) ;
			
			try{
				//gravando arquivo
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				ARMDataHandler::createRecursiveFoldersIfNotExists($folderTarget);
				
				if(  !file_exists( $folderTarget . $fileEditableName ) ){
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
	public static function makeBase( $className, $gatewayClassName,  $folderTarget, array $p_attributes , $override = TRUE ){
		$ReturnResultVO = new ARMReturnResultVO();
		//$row->Field, $row->Type
		if($folderTarget){
			
			$fileName 					= "ARMBase".$className.".abstract.php";
			$templateContent 			= self::getTemplateClassContent( $className, $p_attributes , $gatewayClassName);
			
			try{
				//gravando arquivo
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				ARMDataHandler::createRecursiveFoldersIfNotExists( $folderTarget );
				
				if( $override || !file_exists( $folderTarget . $fileName ) ){
				
					ARMDataHandler::writeFile($folderTarget, $fileName, $templateContent, "w+");
					chmod( $folderTarget.$fileName , 0777 );
					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("sucesso ao criar BaseEntity");
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
	protected static function getGetsByAttributes( $p_attributes ){
		$attributes = "";
		foreach($p_attributes as $row) {
			if( $row->Type == "datetime" || $row->Type == "date" ){
				$attributes .= self::getGetAttributeDate($row->Field);
			}
		}
		return $attributes;
	}
	protected static function getSetsByAttributes( $p_attributes ){
		$attributes = "";
		foreach($p_attributes as $row) {
			if( $row->Type == "datetime" || $row->Type == "date" ){
				$attributes .= self::getSetAttributeDate($row->Field);
			}
		}
		return $attributes;
	}
	protected static function getGetAttributeDate($field){
		$fieldName = ARMDataHandler::urlFolderNameToClassName($field) ;
		
		return "
	/**
	* Converte automático para o formato definido no locale do config do projeto
	* @return string 
	*/
	public function get{$fieldName}(){
		if(!\$this->VO){
			return NULL ;
		}
		return ARMDataHandler::convertDbDateToLocale( ARMTranslator::getCurrentLocale(), \$this->VO->{$field} ) ;
	}";
	}
	protected static function getSetAttributeDate($field){
		$fieldName = ARMDataHandler::urlFolderNameToClassName($field) ;
		
		return "
	/**
	* Converte automático para o formato YYYY/MM/DD
	* @param string \$date
	*/
	public function set{$fieldName}(\$date){
		\$this->getLinkVO();
		\$this->VO->{$field} = ARMDataHandler::convertDateToDB(\$date);
	}";
	}
	protected static function getTemplateClassContent( $baseClassName, $p_attributes, $gatewayClassName ){
		$date = date( "d/m/Y h:m:i" );
		
		$sets = self::getSetsByAttributes($p_attributes);
		$gets = self::getGetsByAttributes($p_attributes);
		
		return <<<STRING
<?php
/**
* created by ARMEntityMaker ( automated system )
* ! Please, don't change this file
* insted change {$baseClassName}Entity  class
*
* ARMBase{$baseClassName}Entity 
* @date $date
*/

abstract class ARMBase{$baseClassName}EntityAbstract extends ARMBaseEntityAbstract{
	
	{$sets}{$gets}
	protected function startVO(){
		if(!\$this->VO){
			\$this->VO = new {$baseClassName}VO();
		}
	}
	/**
	 * 
	 * @param string \$alias
	 * @return {$baseClassName}DAO
	 */
	protected function getDAO( \$alias = "" ){
		return {$gatewayClassName}::getInstance()->getDAO( \$alias ) ;
	}
	/**
	 * @return {$baseClassName}VO
	 */
	public function getLinkVO(){
		return parent::getLinkVO();
	}
}
STRING;
	}
	protected static function getTemplateEditableClassContent($baseClassName, $p_attributes){
		$date = date( "d/m/Y h:m:i" );
		return <<<STRING
<?php
	/**
	* created by ARMEntityMaker ( automated system )
	* Please, change this file
	* don't change ARMBase{$baseClassName}Entity class
	*
	* {$baseClassName}Entity
	* @date $date
	*/

class {$baseClassName}Entity extends ARMBase{$baseClassName}EntityAbstract {
	//put your changes and rewrited methods here
	//Good developers always comment theirs code
}
STRING;
	}
}