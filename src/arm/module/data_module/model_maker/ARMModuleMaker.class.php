<?php

/**
 * @author Renato Miawaki
 * @date 15/3/2013
 *
 *exemple: http://localhost/indikabem/admin/make_vo/?table_name=city&folder=library/
 */
class ARMModuleMaker {
	/**
	 *
	 * @param string $className nome base da classe (sem dao, vo, data no nome)
	 * @param string $folderTarget
	 * @return ARMReturnResultVO
	 */
	public static function make($className, $folderTarget , $override = FALSE ){
		$ReturnResultVO = new ARMReturnResultVO();
		//$row->Field, $row->Type
		if($folderTarget){

			$fileEditableName = $className.".module.php";
			$templateEditableContent 	= self::getTemplateEditableClassContent($className) ;

			try{
				//gravando arquivo
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				ARMDataHandler::createRecursiveFoldersIfNotExists($folderTarget);

				if(  !file_exists( $folderTarget . $fileEditableName )  ){
					ARMDataHandler::writeFile($folderTarget, $fileEditableName, $templateEditableContent, "w+");
					chmod($folderTarget.$fileEditableName , 0777) ;

					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("sucesso ao criar o Module");
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
	public static function makeBase( $className, $gatewayClassName,  $folderTarget , $override = TRUE ){
		$ReturnResultVO = new ARMReturnResultVO();
		//$row->Field, $row->Type
		if($folderTarget){

			$fileName 					= "ARMBase".$className."Module.abstract.php";
			$templateContent 			= self::getTemplateClassContent( $className , $gatewayClassName);

			try{
				//gravando arquivo
				$folderTarget = ARMDataHandler::removeDoubleBars( $folderTarget . "/" ) ;
				ARMDataHandler::createRecursiveFoldersIfNotExists( $folderTarget );

				if( $override || !file_exists( $folderTarget . $fileName ) ){

					ARMDataHandler::writeFile($folderTarget, $fileName, $templateContent, "w+");
					chmod( $folderTarget.$fileName , 0777 );
					$ReturnResultVO->success = TRUE;
					$ReturnResultVO->addMessage("sucesso ao criar BaseModule");
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
	protected static function getTemplateClassContent( $baseClassName, $gatewayClassName ){
		$date = date( "d/m/Y h:m:i" );


		return <<<STRING
<?php
/**
* created by ARMModuleMaker ( automated system )
* ! Please, don't change this file
* insted change {$baseClassName}Module class
*
* ARMBase{$baseClassName}ModuleAbstract
* @date $date
*/

abstract class ARMBase{$baseClassName}ModuleAbstract extends ARMBaseDataModuleAbstract {
	/**
	 * @return {$gatewayClassName}
	 */
	function getModelGateway() {
		return {$gatewayClassName}::getInstance() ;
	}

	/**
	 * @param string \$alias
	 * @param bool \$useDefaultIfNotFound
	 * @return {$baseClassName}Module
	 */
	public static function getInstance(\$alias = self::DEFAULT_GLOBAL_ALIAS, \$useDefaultIfNotFound = FALSE) {
		return parent::getInstance( \$alias, \$useDefaultIfNotFound) ;
	}

	/**
	 * @param \$id
	 * @return {$gatewayClassName}
	 */
	public function getEntityById( \$id ) {
		return parent::getEntityById( \$id ) ;
	}

	/**
	 * Aviso: Não retorna a VO e sim a "Entity"->toStdClass() (que pode conter mais propriedades )
	 * @param \$id
	 * @return {$baseClassName}VO
	 */
	public function getStdById( \$id ) {
		return parent::getStdById( \$id ) ;
	}
}
STRING;
	}
	protected static function getTemplateEditableClassContent($baseClassName){
		$date = date( "d/m/Y h:m:i" );
		return <<<STRING
<?php
	/**
	* created by ARMModuleMaker ( automated system )
	* Please, change this file
	* don't change ARMBase{$baseClassName}ModuleAbstract class
	*
	* ARMBase{$baseClassName}ModuleAbstract
	* @date $date
	*/

class {$baseClassName}Module extends ARMBase{$baseClassName}ModuleAbstract {
	//put your changes and rewrited methods here
	//Good developers always comment theirs code
}
STRING;
	}
}