<?php

class ARMDataMaskBrazil extends ARMDataMask {
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getDate()
	 */
	public function getDate(){
		return "d/m/Y" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getTime()
	 */
	public function getTime(){
		return "H:i:s";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getDateTime()
	 */
	public function getDateTime(){
		return "d/m/Y H:i:s" ;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencyNumberFormat()
	 */
	public function getCurrencyNumberFormat(){
		return new ARMDataMaskNumberFormat( $decimals = 2, $dec_point = "," , $thousands_sep = "." );	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencyValidation()
	 */
	public function getCurrencyValidation(){
		return "/^(?:[1-9](?:[\d]{0,2}(?:\.[\d]{3})*|[\d]+)|0)(?:,[\d]{0,2})?$/";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ARMDataMaskInterface::getCurrencySymbolTemplate()
	 */
	public function getCurrencySymbolTemplate(){
		return "R$ %s" ;
	}
	static function validateCEP($cep){
		$return = false;
		$cep = str_replace('-', '', $cep);
		if(strlen($cep) == 8){
			if (is_numeric($cep)){
				$return = $cep = substr($cep, 0, 5).'-'.substr($cep, 5, 3);
			}
		}
		return $return;
	}
	/**
	 * @param $variable
	 * @return boolean
	 */
	static function validateCNPJ($variable){
		$retorno = false;
		if((strlen($variable) <> 14)){
			$variable = str_replace('.', '', $variable);
			$variable = str_replace('/', '', $variable);
			$variable = str_replace('-', '', $variable);
			if((strlen($variable) <> 14)){
				for($i = 1; $i < 15; $i++){
					$cnpj .= substr($variable,$i,1);
				}
			} else {
				$cnpj = $variable;
			}
		} else {
			$cnpj = $variable;
		}
		if(!is_numeric($cnpj) or strlen($cnpj) <> 14){
			return $return;
		}
		$i = 0;
		while($i < 14){
			$cnpj_d[$i] = substr($cnpj,$i,1);
			$i++;
		}
		$dv_ori = $cnpj[12] . $cnpj[13];
		$soma1 = 0;
		$soma1 = $soma1 + ($cnpj[0] * 5);
		$soma1 = $soma1 + ($cnpj[1] * 4);
		$soma1 = $soma1 + ($cnpj[2] * 3);
		$soma1 = $soma1 + ($cnpj[3] * 2);
		$soma1 = $soma1 + ($cnpj[4] * 9);
		$soma1 = $soma1 + ($cnpj[5] * 8);
		$soma1 = $soma1 + ($cnpj[6] * 7);
		$soma1 = $soma1 + ($cnpj[7] * 6);
		$soma1 = $soma1 + ($cnpj[8] * 5);
		$soma1 = $soma1 + ($cnpj[9] * 4);
		$soma1 = $soma1 + ($cnpj[10] * 3);
		$soma1 = $soma1 + ($cnpj[11] * 2);
		$rest1 = $soma1 % 11;
		if($rest1 < 2){
			$dv1 = 0;
		} else {
			$dv1 = 11 - $rest1;
		}
		$soma2 = $soma2 + ($cnpj[0] * 6);
		$soma2 = $soma2 + ($cnpj[1] * 5);
		$soma2 = $soma2 + ($cnpj[2] * 4);
		$soma2 = $soma2 + ($cnpj[3] * 3);
		$soma2 = $soma2 + ($cnpj[4] * 2);
		$soma2 = $soma2 + ($cnpj[5] * 9);
		$soma2 = $soma2 + ($cnpj[6] * 8);
		$soma2 = $soma2 + ($cnpj[7] * 7);
		$soma2 = $soma2 + ($cnpj[8] * 6);
		$soma2 = $soma2 + ($cnpj[9] * 5);
		$soma2 = $soma2 + ($cnpj[10] * 4);
		$soma2 = $soma2 + ($cnpj[11] * 3);
		$soma2 = $soma2 + ($dv1 * 2);
		$rest2 = $soma2 % 11;
		if($rest2 < 2){
			$dv2 = 0;
		} else {
			$dv2 = 11 - $rest2;
		}
		$dv_calc = $dv1 . $dv2;
		if($dv_ori == $dv_calc){
			$return = $cnpj = substr($cnpj, 0, -12).".".substr($cnpj, -12, 3).".".substr($cnpj, -9, 3)."/".substr($cnpj, -6, 4)."-".substr($cnpj, -2);
		}
		$return = false;
	}
	/**
	 * @param $cpf
	 * @return array (bool, string)
	 */
	static function validateCPF($cpf){
		@$cpf = ereg_replace("[^0-9]", "", $cpf);
		if (strlen($cpf) > 11){
			if(strlen($cpf) > 11){
				return array(FALSE, "muitos digitos");
			}
		} else if(strlen($cpf) < 10){
			return array(FALSE, "muito curto");
		}
		if (!is_numeric($cpf)){
			return array(FALSE, "apenas números são aceitos em cpf");
		} else {
			if ($cpf == "00000000000" or
			$cpf == "11111111111" or
			$cpf == "22222222222" or
			$cpf == "33333333333" or
			$cpf == "44444444444" or
			$cpf == "55555555555" or
			$cpf == "66666666666" or
			$cpf == "77777777777" or
			$cpf == "88888888888" or
			$cpf == "99999999999")
			{
				return array(FALSE, "cpf incorreto, numero obvio.");
			}
			$b = 0;
			$c = 11;
			for ($i=0; $i<11; $i++){
				$a[$i] = substr($cpf, $i, 1);
				if ($i < 9){
					$b += ($a[$i] * --$c);
				}
			}
			if (($x = $b % 11) < 2){
				$a[9] = 0;
			} else {
				$a[9] = 11-$x;
			}
				
			$b = 0;
			$c = 11;
			for ($y=0; $y<10; $y++){
				$b += ($a[$y] * $c--);
			}
			if (($x = $b % 11) < 2){
				$a[10] = 0;
			} else {
				$a[10] = 11-$x;
			}
			if ((substr($cpf, 9, 1) != $a[9]) or (substr($cpf, 10, 1) != $a[10])){
				return array(FALSE, "erro  no cpf");
			}
		}
		$cpf = substr($cpf, 0, 3).".".substr($cpf, 3, 3).".".substr($cpf, 6, 3)."-".substr($cpf, 9, 2);
		return array(TRUE, $cpf);
	}
}