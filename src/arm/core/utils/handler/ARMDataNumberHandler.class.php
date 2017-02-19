<?php 

class ARMDataNumberHandler extends ARMDataIntHandler {
	

	/**
	 * Pode mandar com virgula que transforma em nÃºmero, mantendo os decimais
	 * @param string $valor
	 * @return Number
	 */
	static function forceNumber($valor){
		//verifica se tem virgula
		if(strpos($valor, ",")){
			//tem virgula, entÃ£o ve se Ã© sÃ³ uma
			$temp_array = explode(",", $valor);
			if(count($temp_array) == 2){
				//tem apenas uma virgula, entÃ£o beleza
				$valor = str_replace(".", "", $valor);
				$valor = str_replace(",", ".", $valor);
			}//nÃ£o tem else, se tiver mais de uma virgula, transforma com o *1
		}
		return $valor*1;
	}
	
	/**
	 * calcular parcela com juros
	 * @param number $totalPrice valor total a parcelar
	 * @param number $interestRate % do juros, ex: 1.99
	 * @param number $times quantas parcelas
	 * @return number
	 */
	public static function interestInstallment($totalPrice, $times, $interestRate = 0.0199){
		return ($totalPrice*$interestRate)/(1-(1/pow(1+$interestRate, $times)));
	}
	

	/**
	 * Totalmente traduzindo o metodo em cima pois sei que nunca mais vou acha-lo em ingles
	 * @param number $precoTotal
	 * @param number $juros
	 * @param number $quantidade_de_parcelas
	 * @return number
	 */
	public static function calculoDeJurosParaParcelas($precoTotal, $quantidade_de_parcelas, $juros = 0.0199){
		//@TODO: método em PT-BR... mudar
		return self::interestInstallment($precoTotal, quantidade_de_parcelas, $juros);
	}
	/**
	 * 
	 * @param number $value
	 * @param number $minValue
	 * @param number $maxValue
	 * @return number
	 */
	public static function limitRange( number $value, number $minValue, number $maxValue ){
		
		if( $value < $minValue ){
			$value = $minValue ;
			return $value ;
		}
		if( $value < $maxValue ){
			$value = $maxValue ;
			return $value ;
		}
		return $value ;
	}
}