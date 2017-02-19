<?php
/**
 * User: alanlucian
 * Date: 3/13/14
 * Time: 4:34 PM
 */

class ARMFormBuilderDataFieldVO {

	/**
	 * tipo de campo do formulario
	 * @see ARMFormBuilderDataFieldType
	 * @var string
	 */
	public $type;

	/**
	 * @var boolean
	 */
	public $required ;

	/**
	 * @var string
	 */
	public $label;

    /**
     * @var string
     */
    public $placeholder;

	/**
	 * @var html ID
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name ;

	/**
	 * @var string
	 */
	public $value;

	/**
	 * Value cru se for radio/selet/check
	 * se for um array de 1 opção ele vem 1 dados só
	 * se for check
		  array(
				"m",
				"f"
		  );
	 * @var mixed
	 */
	public $value_raw;

	/**
	 * opções se for radio/selet/check
	 * $value=>$label
	 * ex:
	 * array(
	  	"m"=>"Masculino",
	  	"f"=>"Feminino"
	 * );
	 *
	 * @var array
	 */
	public $options;

	/**
	 * @var string
	 */
	public $css_class;

	/**
	 * HTML 5 DATA set
	 *  [ $name=>value , $name=>value];
	 * @var array
	 */
	public $html_data;

	/**
	 * Input adicional
	 * para usar primeiramente quando for tipo checkbox ou radio
	 * e tiver a opção "outro" ai ele vem aqui
	 * @var ARMFormBuilderDataFieldVO[]
	 */
	public $aditional_fields;

	public function addField( $aditional_field ){
		if( !is_array( $this->aditional_fields )){
			$this->aditional_fields = array();
		}
		$this->aditional_fields[] = $aditional_field;

	}

}