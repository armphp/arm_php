<?php
/**
 * Class GPSFormBuiderDataFieldSimpleShort
 *
 * Essa versão é curta e simples. boa para mostrar em meio a listagens
 *
 */
class GPSDataSetBuiderSimpleShort implements ARMStringBuiderDataFieldSetInterface{

	/**
	$str = "<table style=\"border: 0px;\" >" ;
	foreach( $obj as $item ){
	$str .= "<tr style=\"border: 0px;\" >" ;
	$str .= "   <td style=\"border: 0px;\" >".$item->label ."</td><td style=\"border: 0px;\" >:</td>";
	$str .= "   <td style=\"border: 0px;\" >".$item->value ."</td>";
	$str .= " </tr> " ;
	}
	return $str ."</table>";
	 */
	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function text($fieldDataVO) {
		$str = "<tr style=\"border: 0px;\" >" ;
		$str .= "   <td style=\"border: 0px;\" >".$fieldDataVO->label."</td><td style=\"border: 0px;\" >:</td>";
		$str .= "   <td style=\"border: 0px;\" >".$fieldDataVO->value ."</td>";
		$str .= " </tr> " ;
		return $str ;
	}

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function password($fieldDataVO) {
		$html =  "";
		return $html;
	}

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function long_text($fieldDataVO) {
		$str = "<tr style=\"border: 0px;\" >" ;
		$str .= "   <td style=\"border: 0px;\" >".$fieldDataVO->label."</td><td style=\"border: 0px;\" >:</td>";
		$str .= "   <td style=\"border: 0px;\" >". substr( $fieldDataVO->value, 0 , 40 ) ."</td>";
		$str .= " </tr> " ;
		return $str ;
	}


	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function select($fieldDataVO) {
		$value = "" ;
		foreach( $fieldDataVO->value as $key => $temp_value ){
			if( $value != "" ){
				$value .= " ,";
			}
			$value .= $temp_value ;
		}


		$str = "<tr style=\"border: 0px;\" >" ;
		$str .= "   <td style=\"border: 0px;\" >".$fieldDataVO->label."</td><td style=\"border: 0px;\" >:</td>";
		$str .= "   <td style=\"border: 0px;\" >". $value ."</td>";
		$str .= " </tr> " ;
		return $str ;
	}


	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return array
	 */
	function radio( $fieldDataVO ) {
		$value = "" ;
		foreach( $fieldDataVO->value as $key => $temp_value ){
			if( $value != "" ){
				$value .= " ,";
			}
			$value .= $temp_value ;
		}


		$str = "<tr style=\"border: 0px;\" >" ;
		$str .= "   <td style=\"border: 0px;\" >".$fieldDataVO->label."</td><td style=\"border: 0px;\" >:</td>";
		$str .= "   <td style=\"border: 0px;\" >". $value ."</td>";
		$str .= " </tr> " ;
		return $str ;
	}

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return array
	 */
	function checkbox($fieldDataVO) {
		$value = "" ;
		foreach( $fieldDataVO->value as $key => $temp_value ){
			if( $value != "" ){
				$value .= " ,";
			}
			$value .= $temp_value ;
		}


		$str = "<tr style=\"border: 0px;\" >" ;
		$str .= "   <td style=\"border: 0px;\" >".$fieldDataVO->label."</td><td style=\"border: 0px;\" >:</td>";
		$str .= "   <td style=\"border: 0px;\" >". $value ."</td>";
		$str .= " </tr> " ;
		return $str ;
	}

	/**
	 * @param $fieldDataVO ARMFormBuilderDataFieldVO
	 * @return string
	 */
	function hidden($fieldDataVO) {
		return  "" ;
	}


}