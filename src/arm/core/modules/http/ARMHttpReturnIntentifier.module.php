<?php
/**
 * 
 * (pt-br) 	Retorna o tipo de retorno que a requisição pretende, segundo a configuração basica do arm
 * 			Essa classe pode ser trocada no config do arm e basta implementar a HttpReturnIndentifierInterface
 * 
 * @author renatomiawaki
 *
 */
class ARMHttpReturnIntentifierModule extends ARMBaseModuleAbstract implements ARMHttpReturnIndentifierInterface{
	/**
	 * 
	 * @var ARMHttpReturnIndentifierConfigVO
	 */
	protected $_config;
	/**
	 * 
	 * @return 
	 */
	public function getParsedConfigData( $configResult ){
		return new ARMHttpReturnIndentifierConfigVO( $configResult );
	}
	/* (non-PHPdoc)
	 * @see HttpReturnIndentifierInterface::getType()
	 */
	public function getType() {
		$type = ARMConfig::getDefaultInstance()->getDefaultRequestResultType() ;
		
		$settedType = ARMNavigation::getVar( $this->_config->default_variable_name ) ;
		if( $settedType ){
			$type = $settedType ;
		}
		
		return $type;
	}
		
}

