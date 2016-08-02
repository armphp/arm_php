<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:42 PM
 */

class ARMViewRenderFieldDataModule extends ARMBaseModuleAbstract {

	/**
	 * @var ARMViewRenderFieldDataConfigVO
	 */
	protected $_config;

	/**
	 * @param null $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMViewRenderFieldDataModule
	 */
	public static function getInstance($alias = NULL, $useDefaultIfNotFound = FALSE) {
		return parent::getInstance($alias, $useDefaultIfNotFound) ;
	}




	/**
	 *
	 * @param $formData ARMFormBuilderFieldData
	 * @return string
	 */
	public function toHtmlForm( $formData ){

		$html = "";
		if( isset( $formData->fields ) && $formData->fields && is_array( $formData->fields ) ){
			foreach(  $formData->fields as $fieldVO ){
				$html.= $this->getFormFieldSet($fieldVO);
			}
		}

		return $html;
	}

	/**
	 * @param $formData ARMFormBuilderFieldData
	 * @return string
	 */
	public function toString( $formData , $prefix = "", $sulfix = ""){

		$html = $prefix;

		foreach(  $formData->fields as $fieldVO ){
			$html   .= $this->getStringFieldSet($fieldVO);
		}

		return $html.$sulfix;
	}

	/**
	 *
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 *
	 * @return string
	 */
	public function getStringFieldSet( $fieldVO ){

		$fieldSetBuilderClass =  $this->getStringRenderModule() ;
		return  call_user_func( array( $fieldSetBuilderClass ,  strtolower( $fieldVO->type ) ) , $fieldVO ) ;

	}

	/**
	 * @var ARMStringBuiderDataFieldSetInterface
	 */
	protected $__stringBuilder ;

	/**
	 * @return ARMStringBuiderDataFieldSetInterface
	 */
	protected function getStringRenderModule(){

		if( ! $this->__stringBuilder ) {
			ARMClassIncludeManager::load( $this->_config->stringFieldSetBuildModule );
			$this->__stringBuilder =  new $this->_config->stringFieldSetBuildModule() ;
		}

		return $this->__stringBuilder ;
	}

	/**
	 *
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 *
	 * @return string
	 */
	public function getFormFieldSet( $fieldVO ){

		$html_field_data = $this->getFieldData( $fieldVO );

		$fieldSetBuilderClass =  $this->getFormRenderModule() ;

		return  call_user_func( array( $fieldSetBuilderClass ,  strtolower( $fieldVO->type ) ) , $html_field_data ) ;

	}


	/**
	 * @var ARMStringBuiderDataFieldSetInterface
	 */
	protected $__formBuilder ;

	/**
	 * @return ARMStringBuiderDataFieldSetInterface
	 */
	protected function getFormRenderModule(){

		if( ! $this->__formBuilder ) {
			ARMClassIncludeManager::load( $this->_config->formFieldSetBuildModule );
			$this->__formBuilder =  new $this->_config->formFieldSetBuildModule() ;
		}

		return $this->__formBuilder ;
	}

//	private static $multiIndex = array();

	protected static $field_index = 0 ;

	/**
	 * @param $fieldVO  ARMFormBuilderDataFieldVO
	 * @return ARMFormBuilderDataFieldHTMLVO
	 */
	protected function getFieldData( $fieldVO ){

//		$fie	 ldVO->name  = ;

		$json_vo = json_encode( $fieldVO );

		$multiValue = NULL ;
		$name = $fieldVO->name;
		/*  honestamente não lembro pq fiz isso....   comentei seja lá o q for um dia...
		 if( strpos( $name , "[]" ) !== FALSE ){
			$name = str_replace("[]" , "" , $name );
			if( !isset( self::$multiIndex[ $name ]) ){
				self::$multiIndex[ $name ] = self::$field_index++;
			}
			$currentIndex = self::$multiIndex[ $name ] ;
			$multiValue = "[]" ;
		} else{
		*/

		$currentIndex = self::$field_index++;


		$field_data_name =  $this->getVariableName()."[{$fieldVO->name}][data]$multiValue";
		$field_value_name  = $this->getVariableName()."[{$fieldVO->name}][value]$multiValue";
		$field_child_value_name  = $this->getVariableName()."[{$fieldVO->name}][child_value]$multiValue";

		$html_field_info_vo = new ARMFormBuilderDataFieldHTMLVO();

		ARMClassIncludeManager::load( $this->_config->fieldBuildModule );

		$fieldBuilderClass =  new $this->_config->fieldBuildModule() ;

		$fieldVO->name = $field_value_name ;
		$html_field_info_vo->html_field  = call_user_func( array( $fieldBuilderClass ,  strtolower( $fieldVO->type ) ) , $fieldVO ) ;


		// aditional
		if(!is_null($fieldVO->aditional_fields)){
			$childCount = 0 ;
			$html_field_info_vo->aditional_fields = array();
			foreach( $fieldVO->aditional_fields as $childField ){
				if( !is_object( $childField )  ){
					continue ;
				}
				$field_child_name  = "{$field_child_value_name}[{$childCount}]";
				$childField->name = $field_child_name;
				$html_field_info_vo->aditional_fields[] = call_user_func( array( $fieldBuilderClass ,  strtolower( $childField->type ) ) , $childField ) ;
				$childCount++;
			}

		}

		$dataFieldVO = 	new ARMFormBuilderDataFieldVO();
		$dataFieldVO->name =  $field_data_name;
		$dataFieldVO->value = $json_vo ;

		$html_field_info_vo->html_field_data = call_user_func( array( $fieldBuilderClass ,  "hidden" ) , $dataFieldVO ) ;
		$html_field_info_vo->fieldVO = $fieldVO;

		return $html_field_info_vo ;
	}

	/**
	 * nome dos campos que vão ser utilizados
	 * @return string
	 */
	public function getVariableName(){
		return $this->_config->fieldNamePrefix ;
	}


}