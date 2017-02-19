<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:43 PM
 */

class ARMFormBuilderFieldDataParser {

	/**
	 * Serialize dá merda no banco de dados
	 * @param $data ARMFormBuilderFieldData
	 */
	public static function toString( $data ){
		return json_encode( $data ) ;
	}

	/**
	 * @param $string
	 * @return ARMFormBuilderFieldData
	 */
	public static function parseString( $string ){
		return json_decode( $string ) ;
	}


	/**
	 * @param $_POST array
	 * @return ARMFormBuilderFieldData
	 */
	public static function parseArray( $array_post ){
		/**
		 * Parsear o POST e gerar um ARMFormBuilderFieldData válido
		 *
		 */

		$array = $array_post[  ARMViewRenderFieldDataModule::getInstance()->getVariableName()];


		$FormBuilderData = new ARMFormBuilderFieldData();

		foreach( $array as $fieldName => $fieldInfo){


			if( !isset( $fieldInfo["data"] ) ){
				throw new ErrorException( "Missing 'data' for {$fieldName}" );
			}

			/** @var $DataFieldVO ARMFormBuilderDataFieldVO */
			$DataFieldVO = json_decode( $fieldInfo["data"] ) ;

			if(is_object($DataFieldVO->options)){
				$DataFieldVO->options =  (array)$DataFieldVO->options;
			}

			if( isset( $fieldInfo["value"] ) ){
				$DataFieldVO->value = $fieldInfo["value"] ;
			}

			$hasOptions =
				(
					$DataFieldVO->type == ARMFormBuilderDataFieldType::TYPE_SELECT  ||
					$DataFieldVO->type == ARMFormBuilderDataFieldType::TYPE_RADIO ||
					$DataFieldVO->type == ARMFormBuilderDataFieldType::TYPE_CHECKBOX
				) ? TRUE : FALSE ;

			// SE for campo de escolhas gravar o value com dados humanóides
			if( $hasOptions ){
//				$DataFieldVO->value_raw é sempre um array
				$DataFieldVO->value_raw = !is_array($DataFieldVO->value)?array($DataFieldVO->value) : $DataFieldVO->value ;

				$DataFieldVO->value = array();

				foreach( $DataFieldVO->value_raw as $option_value ){
					if( isset( $DataFieldVO->options[$option_value] ) ){
						$DataFieldVO->value[$option_value] = $DataFieldVO->options[$option_value];
					}
				}
			}

			if( isset( $fieldInfo["child_value"] ) ){
				for( $child_count = 0 ; $child_count < count( $fieldInfo["child_value"] ) ; $child_count++){
					$child_value = $fieldInfo["child_value"][$child_count];
					if(!strlen($child_value )>0){
						continue;
					}
					if( $hasOptions ){
						//coloca o child como selecionado
						$DataFieldVO->value_raw[] = "child_".$child_count ;
						$DataFieldVO->value["child_".$child_count] = $child_value ;
					}
					$DataFieldVO->aditional_fields[$child_count]->value = $child_value ;
				}
			}
			$FormBuilderData->addField( $DataFieldVO );
		}

		return $FormBuilderData ;
	}

}