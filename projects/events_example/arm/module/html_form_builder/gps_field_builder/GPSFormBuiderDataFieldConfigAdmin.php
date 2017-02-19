<?php
/**
 * Class GPSFormBuiderDataFieldSimpleShort
 *
 * Essa versão é curta e simples. boa para mostrar em meio a listagens
 *
 */
class GPSFormBuiderDataFieldConfigAdmin implements ARMFormBuiderDataFieldSetInterface{

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function text($field_html_VO) {
		$html =  "
            <div class='widget-separator grid-12 item'><h5>Questão do tipo Texto com resposta Pequena</h5>
			<div class='widget-separator no-border grid-12' >
				<h4 class='typo'>{$field_html_VO->fieldVO->label}</h4>
				<br />
				{$field_html_VO->html_field_data}
				{$field_html_VO->html_field}
			</div>
			<div class='btn btn-error btn-small remover' ><i>&#xf014;</i> Remover</div></div>
		";
		return $html;

	}

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function password($field_html_VO) {
		$html =  "
            <div class='widget-separator grid-12 item'><h5>Questão do tipo Senha</h5>
			<div class='widget-separator no-border grid-12' >
				<h4 class='typo'>{$field_html_VO->fieldVO->label}</h4>
				<br />
				{$field_html_VO->html_field_data}
				{$field_html_VO->html_field}
			</div>
			<div class='btn btn-error btn-small remover' ><i>&#xf014;</i> Remover</div></div>
		";
		return $html;
	}

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function long_text($field_html_VO) {
		$html =  "
            <div class='widget-separator grid-12 item'><h5>Questão do tipo Texto com resposta Grande</h5>
			<div class='widget-separator no-border grid-12'>
				<h4 class='typo'>{$field_html_VO->fieldVO->label}</h4>
				{$field_html_VO->html_field_data}
				{$field_html_VO->html_field}
			</div>
			<div class='btn btn-error btn-small remover' ><i>&#xf014;</i> Remover</div></div>
		";
		return $html;
	}


	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function select($field_html_VO) {

		if( !is_null($field_html_VO) && is_array( $field_html_VO->aditional_fields  ) ){
			$aditional_field = implode( "<br/>",$field_html_VO->aditional_fields );
			$field = "
				<div class='widget-separator no-border grid-6' >
					{$field_html_VO->html_field}
				</div>
				<div class='widget-separator no-border grid-6' >
					{$aditional_field}
				</div>
			";
		} else{
			$field = $field_html_VO->html_field;
		}


		$html =  "
            <div class='widget-separator grid-12 item'><h5>Questão do tipo Escolha Única ( select ) </h5>
			<div class='widget-separator no-border grid-12' >
				<h4>{$field_html_VO->fieldVO->label}</h4>
				<br />
				{$field_html_VO->html_field_data}
				{$field}
			</div>
			<div class='btn btn-error btn-small remover' ><i>&#xf014;</i> Remover</div></div>
		";
		return $html;
	}


	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	protected function multiOptionsBuilder( $field_html_VO ){
		$total_options = count($field_html_VO->html_field);

		$aditional_field = "";
		if(!is_null($aditional_field)){
			$aditional_field = implode( "<br/>",$field_html_VO->aditional_fields );

			$aditional_field = "<div class='grid-12'>{$aditional_field}</div>";
		}


		$option_grid_size =  floor( 12/$total_options ) ;
		$html =  "
            <div class='widget-separator grid-12 item'><h5>Questão do tipo Multipla Escolha</h5>
			<div class='widget-separator no-border grid-12' >
				<div class='grid-3 '>
					<h4 class='typo'>{$field_html_VO->fieldVO->label}</h4>
										{$field_html_VO->html_field_data}


				</div>
    			<div class='grid-9' >
    	";


		foreach ( $field_html_VO->html_field as $input /** @var $input ARMFormBuilderDataFieldListItemVO */ ){
			$html.=  "
					<div class='grid-{$option_grid_size}'>
						<div class=''>
							<span>{$input->field}</span>
						</div>
						<h5 class='typo inline tipsy-s' original-title=''>{$input->label}</h5>
					</div>
				";
		}
		$html.= $aditional_field ;
		$html.=  "</div>";
		$html.="</div><div class='btn btn-error btn-small remover' ><i>&#xf014;</i> Remover</div></div>";

		return $html;
	}

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return array
	 */
	function radio( $field_html_VO ) {
		return $this->multiOptionsBuilder( $field_html_VO );
	}

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return array
	 */
	function checkbox($field_html_VO) {
		return $this->multiOptionsBuilder( $field_html_VO );
	}

	/**
	 * @param $field_html_VO ARMFormBuilderDataFieldHTMLVO
	 * @return string
	 */
	function hidden($field_html_VO) {
		// TODO: Implement hidden() method.
	}


}