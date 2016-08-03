<?php

/**
 * @author Renato Miawaki
 * @date 27/1/2013
 *
 *exemple: http://localhost/indikabem/admin/make_vo/?table_name=city&folder=library/
 */ 
class ARMModelVoMaker {
	/**
	 * cria o arquivo VO baseado na tabela do banco
	 * @param unknown $tableName
	 * @param unknown $folderTarget
	 * @param string $className default = "TableNameVO.class.php"
	 * 
	 * @return ARMReturnResultVO
	 */
	public static function makeFromTable( $tableName, $folderTarget, array $p_attributes, $className = NULL, $override = TRUE ){
		$ReturnResultVO = new ARMReturnResultVO();
		if( $tableName && $folderTarget ) {
			
			$className = ($className)?$className:self::tableNameToFileNameHandler($tableName);
			
			$fileName = $className.".vo.php";
			$templateContent = self::getTemplateClassContent($className, $tableName);
			$attributes = "";
			
				foreach($p_attributes as $row) {
					$attributes .= self::getTemplateAttributeContent($row->Field, $row->Type);
				}
				$templateContent = str_replace("#attributes", $attributes, $templateContent);
				//gravando arquivo
				
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				
				ARMDataHandler::createRecursiveFoldersIfNotExists( $folderTarget );
				
				if( $override || !file_exists( $folderTarget . $fileName ) ){
				
					ARMDataHandler::writeFile( $folderTarget,  $fileName , $templateContent, "w+");
					chmod($folderTarget.$fileName , 0777);
					
					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("sucesso ao criar vo");
				} else {
					$ReturnResultVO->addMessage("arquivo já existe");
				}
				
				
		} else {
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage("envie o tableName e folder");
		}
		
		return $ReturnResultVO;
	}
	protected static function getTemplateClassContent($className, $table_name){
		$date = date( "d/m/Y h:m:i" );
		return "<?php
/**
 * created by ARMModelVoMaker ( automated system )
 * 
 * @date $date
 * @from_table $table_name 
 */ 
class {$className}VO extends ARMAutoParseAbstract{
	
	 #attributes
}
	";
	}
	protected static function getTemplateAttributeContent($attributeName, $type){
		return "
	
	/**
	 * @type : $type			
	 */
	public $".$attributeName.";";
	}
	protected static function tableNameToFileNameHandler($tableName){
		return ARMDataHandler::urlFolderNameToClassName($tableName);
	}
}