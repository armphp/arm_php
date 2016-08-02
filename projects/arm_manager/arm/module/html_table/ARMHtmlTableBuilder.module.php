<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alanlucian
 * Date: 11/20/13
 * Time: 10:53 AM
 */

class ARMHtmlTableBuilderModule extends ARMBaseModuleAbstract {

	/**
	 * @var ARMHtmlTableBuilderConfigVO
	 */
	protected $_config ;

	/**
	 * @var array
	 */
	public $properties_list ;

	/**
	 * @var array
	 */
	public $table_title_list ;


	/**
	 * @var Object
	 */
	public $generic_info;

	/**
	 * @var ARMListResult
	 */
	public $table_data ;

	public $action_list;

	public function setGenericInfo( $info ){
		$this->generic_info =  $info ;
	}


	protected $_raw_data = NULL ;
	public function setRawData( $data ){
		$this->_raw_data = $data ;
	}

	public function getRawData(){
		return $this->_raw_data ;
	}
	public function setTableHeader( $dataObject ){

		$this->properties_list = array();
		$this->table_title_list = array();

		foreach( $dataObject as $propertie=>$title ){
			if( is_null($title) )
				continue ;

			$this->properties_list[] = $propertie ;
			$this->table_title_list[] = $title ;
		}

	}

	/**
	 * @param ARMListResult $table_data
	 */
	public function setData( ARMListResult $table_data ){
		$this->table_data = $table_data ;
	}

	public function show( $configInfo = NULL ){
		include $this->_config->template_path ;
	}

	/**
	 * @return ARMHtmlTableBuilderModule|null
	 */
	public static function getDefaultInstance()	{
		return parent::getDefaultInstance();
	}


	/**
	 * @param string $alias
	 * @return ARMHtmlTableBuilderModule
	 */
	public static function getInstance($alias = self::DEFAULT_GLOBAL_ALIAS, $useDefaultIfNotFound = FALSE ) {
		return parent::getInstance($alias , $useDefaultIfNotFound  );
	}


}