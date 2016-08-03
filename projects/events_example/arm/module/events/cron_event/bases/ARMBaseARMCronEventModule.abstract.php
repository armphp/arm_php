<?php
/**
* created by ARMModuleMaker ( automated system )
* ! Please, don't change this file
* insted change ARMCronEventModule class
*
* ARMBaseARMCronEventModuleAbstract
* @date 26/02/2016 04:02:34
*/

abstract class ARMBaseARMCronEventModuleAbstract extends ARMBaseDataModuleAbstract {
	/**
	 * @return ARMCronEventModelGateway
	 */
	function getModelGateway() {
		return ARMCronEventModelGateway::getInstance() ;
	}

	/**
	 * @param string $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMCronEventModule
	 */
	public static function getInstance($alias = self::DEFAULT_GLOBAL_ALIAS, $useDefaultIfNotFound = FALSE) {
		return parent::getInstance( $alias, $useDefaultIfNotFound) ;
	}

	/**
	 * @param $id
	 * @return ARMCronEventModelGateway
	 */
	public function getEntityById( $id ) {
		return parent::getEntityById( $id ) ;
	}

	/**
	 * Aviso: Não retorna a VO e sim a "Entity"->toStdClass() (que pode conter mais propriedades )
	 * @param $id
	 * @return ARMCronEventVO
	 */
	public function getStdById( $id ) {
		return parent::getStdById( $id ) ;
	}
}