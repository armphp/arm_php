<?php
/**
 * User: alanlucian
 * Date: 11/25/13
 * Time: 2:24 PM
 */

class ARMHtmlSimpleFormModule extends ARMBaseModuleAbstract {

	/**
	 * @var ARMHtmlSimpleFormVo
	 */
	protected $_config;


	/**
	 * @var stdClass
	 */
	protected $content_vo;

	/**
	 * @var ARMHtmlSimpleFormFieldVO[]
	 */
	protected $fields;

	/**
	 * @param \stdClass $content_vo
	 */
	public function setContentVo($content_vo) {
		$this->content_vo = $content_vo;
	}

	/**
	 * @return ARMHtmlSimpleFormFieldVO[]
	 */
	public function getFields(){
		if( !$this->fields ){
			$this->parseFields();
		}
		return $this->fields;
	}


	/**
	 * Parse field datas to getFields or to_string work
	 */
	public function parseFields(){
		$this->fields = array() ;
		foreach( $this->_config->content as $fieldData ){
			$builder =  "build" . ARMDataHandler::urlFolderNameToClassName( $fieldData->type ) ;
			$this->fields[] = $this->{$builder}( $fieldData ) ;
		}

	}

	/**
	 * @param $formContentVO
	 * @return ARMHtmlSimpleFormFieldVO
	 */
	protected function buildCheckBox( $formContentVO ){
		ARMDebug::print_r( $this->getOptionsData($formContentVO->options ) );
		die;
	}

	/**
	 * @param $formContentVO
	 * @return ARMHtmlSimpleFormFieldVO
	 */
	protected function buildOption( $formContentVO ){
		ARMDebug::print_r( $formContentVO );
		die;
	}

	/**
	 * @param $formContentVO
	 * @return ARMHtmlSimpleFormFieldVO
	 */
	protected function buildInputText( $formContentVO ){
		$fieldData = $this->getBaseFormFileld( $formContentVO );

		$properties = $this->parseProperties( $formContentVO->properties ) ;
		$fieldData->field = "<input type='text' {$properties} />";

		return $fieldData ;
	}

	/**
	 * @param $formContentVO
	 * @return ARMHtmlSimpleFormFieldVO
	 */
	protected function buildSelect( $formContentVO ){

		$fieldData = $this->getBaseFormFileld( $formContentVO );

		$properties = $this->parseProperties( $formContentVO->properties ) ;
		$fieldData->field = "<select {$properties} />\n";


		$data = $this->getOptionsData( $formContentVO->options );
		/** @var $data ARMReturnDataVO */

		$value_property = str_replace( array("{" , "}" ) , "" , $formContentVO->options->protocol->value) ;
		$label_property = str_replace( array("{" , "}" ) , "" , $formContentVO->options->protocol->label) ;

		if( $data->hasResult() ){
			foreach( $data->result as $rowVO ){
				if( isset($formContentVO->selected ) ) {
					$selected = NULL;
					/** extract $formContentVO->selected  to variables  $vo_property=>$value */
					foreach( $formContentVO->selected as $vo_property=>$value);
					if( $rowVO->$vo_property == $this->getPropertyValue( $value )  ){
						$selected  = "selected='selected'";
					}

				}
				$fieldData->field.= "\t<option  {$selected} value='{$rowVO->$value_property}'>{$rowVO->$label_property}</option>\n";
			}
		}

		$fieldData->field.= "\n</select>";

		return $fieldData ;

	}

	public function getFormOpenTag(){

		$name = ($this->_config->name)? "name='{$this->_config->name}'" :  NULL;

		$id = ($this->_config->id)? "id='{$this->_config->id}'" :  NULL;

		$method = ($this->_config->method)? "id='{$this->_config->method}'" :  NULL;

		$action = NULL ;
		if( $this->_config->action && is_object( $this->_config->action->type )  && $this->_config->action->type == "Controller"){
			$action = $this->_config->action;

			{ // Building action parameters
				$params  =  NULL ;
				if( $action->parameters ){
					ARMDebug::print_r( $action->parameters ) ;
					$params  =  array() ;
					foreach( $action->parameters as $key=>$value_key ) {
						$value = $this->getPropertyValue( $value_key );
						/**
						 * @TODO: aqui tem que pegar dinânico a cola de parametros p/ url
						 */
						$params[] = "{$key}.{$value}";
					}

					$params = implode( "/" , $params);


				$action = "action='". ARMNavigation::getLinkToController( $action->controller, $action->method  ) .  $params  . "'";

				} elseif( $this->_config->action && is_string( $this->_config->action->type ) ){
					$action = $this->_config->action ;
					$action = "action='". $action . "'";
				}
			}

				/** @var $action ARMHtmlSimpleFormActionVO */

		}
		$form = "<form {$id} {$name} {$method} {$action} >\n";

		return $form;
	}


	/**
	 * @param $formContentOptions
	 * @return ARMReturnDataVO
	 */
	protected function getOptionsData( $formContentOptions ) {
		$data = new ARMReturnDataVO();
		if ( $formContentOptions->type  == "DataModule" ){
			ARMClassIncludeManager::load( $formContentOptions->DAO ) ;
			$DAO = call_user_func( "{$formContentOptions->DAO}::getInstance" ) ;
			return  $DAO->selectByVO( $formContentOptions->filter ) ;
		}

		if ( $formContentOptions->type  == "Module" ){
			ARMClassIncludeManager::load( $formContentOptions->class) ;

			$moduleInstance = call_user_func( $formContentOptions->class ."::getInstance" ,  $formContentOptions->instance ) ;

			$params = array() ;
			foreach( $formContentOptions->parameters as $param ){
				$params[] = $param ;
			}

			$result = call_user_func_array( array( $moduleInstance , $formContentOptions->method ) , $params ) ;

			if( ARMClassHandler::getClassName($result) !== "ARMReturnDataVO" ){

				ARMDebug::error( " {$formContentOptions->class } The result of  Must be an instance of 'ARMReturnDataVO' ");
				die;
			}

			return $result ;
		}

		return $data ;
	}

	/**
	 * Fill ARMHtmlSimpleFormFieldVO with label info
	 * @param $formContentVO
	 * @return ARMHtmlSimpleFormFieldVO
	 */
	protected function getBaseFormFileld( $formContentVO ){
		$fieldData = new ARMHtmlSimpleFormFieldVO();
		$fieldData->label = "<label for='{$formContentVO->properties->id}'>" .  $formContentVO->label ;
		return $fieldData ;
	}

	/**
	 * Parse an object of properties to an string
	 * @param $properties
	 * @return string
	 */
	protected function parseProperties( $properties ){
		$properties_str = "" ;
		foreach( $properties as $key=>$value ){
			$value = $this->getPropertyValue( $value );

			$properties_str.= " {$key}='{$value}'";
		}
		return $properties_str;
	}


	/**
	 * Verify if the property value is an reference for the content_VO
	 * eg:  {id}  will be changed for the value of the $this->content_vo->id
	 * @param $value_key
	 * @return string|null
	 */
	protected function getPropertyValue( $value_key ){
		$value  = NULL ;
		if( strpos( $value_key , "{" ) !== FALSE  &&  strpos( $value_key , "}" ) !== FALSE ){
			$value_key = str_replace( array("{" , "}" ) , "" , $value_key) ;
			if( isset($this->content_vo->{$value_key} ) ){
				$value = $this->content_vo->{$value_key} ;
			}
		} else {
			$value = $value_key;
		}
		return $value ;
	}



	/**
	 * @param string $alias
	 * @return ARMHtmlSimpleFormModule
	 */
	public static function getInstance($alias = self::DEFAULT_GLOBAL_ALIAS) {
		return parent::getInstance($alias); // TODO: Change the autogenerated stub
	}

	/**
	 * Parse the config data from json to an typed ARMHtmlSimpleFormVO
	 * @param object $configResult
	 * @return object
	 */
	public function getParsedConfigData($configResult) {

		$vo = new ARMHtmlSimpleFormVO();

		$vo = ARMDataHandler::objectMerge( $vo , $configResult, TRUE, TRUE );

		return $vo ;
	}

}