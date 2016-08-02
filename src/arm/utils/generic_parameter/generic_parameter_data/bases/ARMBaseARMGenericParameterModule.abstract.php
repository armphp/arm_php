<?php
/**
* created by ARMModuleMaker ( automated system )
* ! Please, don't change this file
* insted change ARMGenericParameterModule class
*
* ARMBaseARMGenericParameterModuleAbstract
* @date 18/03/2014 01:03:21
*/

abstract class ARMBaseARMGenericParameterModuleAbstract extends ARMBaseDataModuleAbstract {
	/**
	 * @return ARMGenericParameterModelGateway
	 */
	function getModelGateway() {
		return ARMGenericParameterModelGateway::getInstance() ;
	}

	/**
	 * @param string $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMGenericParameterModule
	 */
	public static function getInstance($alias = self::DEFAULT_GLOBAL_ALIAS, $useDefaultIfNotFound = FALSE) {
		return parent::getInstance( $alias, $useDefaultIfNotFound) ;
	}

	/**
	 * @param $id
	 * @return ARMGenericParameterModelGateway
	 */
	public function getEntityById( $id ) {
		return parent::getEntityById( $id ) ;
	}

	/**
	 * Aviso: Não retorna a VO e sim a "Entity"->toStdClass() (que pode conter mais propriedades )
	 * @param $id
	 * @return ARMGenericParameterVO
	 */
	public function getStdById( $id ) {
		return parent::getStdById( $id ) ;
	}
}