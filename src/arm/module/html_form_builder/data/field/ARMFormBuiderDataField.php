<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:57 PM
 */

class ARMFormBuiderDataField implements ARMFormBuiderDataFieldInterface {

	/**
	 * dados comuns para todos os campos de fornulário
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	protected function commomHtmlInfo( $fieldVO  , $use_id = TRUE ){

		$fieldVO->type = strtolower($fieldVO->type);

		$fieldVO->css_class.= " field_{$fieldVO->type}";

		$commomFieldInfo = "" ;

		if( $fieldVO->required == TRUE ){
			$commomFieldInfo.= " required ";
			$fieldVO->css_class.= " required ";
		}

		if ( $use_id ) {
			$fieldVO->id = !isset($fieldVO->id)? $fieldVO->name : $fieldVO->id;
			$fieldVO->id = str_replace("[", "_" ,$fieldVO->id);
			$fieldVO->id = str_replace("]", "" ,$fieldVO->id);
			$commomFieldInfo .= " id='{$fieldVO->id}' ";
		}
		$commomFieldInfo.= " name='{$fieldVO->name}' ";
		$commomFieldInfo.= " class='{$fieldVO->css_class}'" ;

		return $commomFieldInfo ;

	}

	/**
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return string
	 */
	function text($fieldVO) {
        $placeholder = ARMDataHandler::getValueByStdObjectIndex( $fieldVO, "placeholder" ) ;
		$commonInfo = $this->commomHtmlInfo($fieldVO);
		return "<input type='text' value='{$fieldVO->value}' placeholder='{$placeholder}' {$commonInfo} />" ;
	}

	/**
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return string
	 */
	function password( $fieldVO ) {
		$commonInfo = $this->commomHtmlInfo($fieldVO);
		return "<input type='password' value='{$fieldVO->value}' {$commonInfo} />" ;
	}

	/**
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return string
	 */
	function long_text($fieldVO) {
        $placeholder = ARMDataHandler::getValueByStdObjectIndex( $fieldVO, "placeholder" ) ;
        $commonInfo = $this->commomHtmlInfo($fieldVO);
		return "<textarea  {$commonInfo} placeholder='{$placeholder}'>{$fieldVO->value}</textarea>" ;
	}

	/**
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return string
	 */
	function hidden($fieldVO) {
		$commonInfo = $this->commomHtmlInfo($fieldVO);
		return "<input type='hidden' value='{$fieldVO->value}' {$commonInfo} />" ;
	}


	/**
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return string
	 */
	function select($fieldVO) {
		$options = array();
		foreach( $fieldVO->options as $option_value=>$option_label ){
			$selected =  $option_value == $fieldVO->value_raw  ?  "selected='selected'" : "" ;
			$options[] = "<option value='{$option_value}' {$selected} >{$option_label}</option>";
		}

		if( !is_null( $fieldVO->aditional_fields ) ){
			$childCount = 0 ;
			foreach(  $fieldVO->aditional_fields as $childFieldVO ){
				$option_value = "child_" . $childCount++;
				$selected =  $option_value == $fieldVO->value_raw  ?  "selected='selected'" : "" ;
				$options[] = "<option value='{$option_value}' {$selected} >{$childFieldVO->label}</option>";
			}

		}

		$commonInfo = $this->commomHtmlInfo($fieldVO);
		$options = implode("\n",$options);
		return "<select {$commonInfo} >{$options}</select>";
	}


	/**
	 * Lis for radio and checkbox
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return ARMFormBuilderDataFieldListItemVO[]
	 */
	protected function multiOptionsBuilder( $fieldVO ){
		$commonInfo = $this->commomHtmlInfo( $fieldVO , FALSE ) ;
		$type =  strtolower( $fieldVO->type ) ;
		$itens = array();
		foreach( $fieldVO->options as $option_value=>$option_label ){
			$selected =  $this->isSelectedValue( $option_value , $fieldVO->value_raw  ) ?  "checked='checked'" : "" ;
			$item = new ARMFormBuilderDataFieldListItemVO();
			$item->field = "<input {$selected} type='{$type}' value='$option_value' {$commonInfo}>";
			$item->label = $option_label ;
			$itens[] = $item ;
		}

		if( !is_null( $fieldVO->aditional_fields ) ){
			$childCount = 0 ;
			foreach(  $fieldVO->aditional_fields as $childFieldVO ){
				if( !is_object( $childFieldVO ) ){
					continue ;
				}
				$option_value = "child_" . $childCount++;
				$selected =  $option_value == $fieldVO->value_raw  ?  "selected='selected'" : "" ;
				$item = new ARMFormBuilderDataFieldListItemVO();
				$item->field = "<input {$selected} type='{$type}' value='$option_value' {$commonInfo}>";
				$item->label = $childFieldVO->label;
				$itens[] = $item ;
			}

		}

		return $itens;
	}

	/**
	 * Verifica se o option está entre os pré selecionados
	 * @param $option_value
	 * @param $selected_values
	 * @return bool
	 */
	protected function isSelectedValue( $option_value , $selected_values ){
		if( is_array( $selected_values ) ){
			return in_array( $option_value , $selected_values ) ;
		}
		return $option_value == $selected_values ;
	}

	/**
	 * @param ARMFormBuilderDataFieldVO $fieldVO
	 * @return ARMFormBuilderDataF	ieldListItemVO[]
	 */
	function radio( $fieldVO ) {
		return $this->multiOptionsBuilder( $fieldVO );
	}

	/**
	 * @param $fieldVO ARMFormBuilderDataFieldVO
	 */
	function checkbox($fieldVO) {
		$fieldVO->name = $fieldVO->name."[]";
		return $this->multiOptionsBuilder( $fieldVO );
	}




}