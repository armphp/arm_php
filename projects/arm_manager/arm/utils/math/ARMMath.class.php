<?php
/**
 * Classe para calculos matematicos basicos
 * A maioria dos metodos já existem no php, a utilidade de encapsular são:
 * 
 * 1 - Fácil de lembrar onde estão os metodos
 * 2 - Caso um dia algum metodo fique "deprecated" é simples de atualizar no sistema todo
 * 
 * @author renatomiawaki
 *
 * @TODO: implementar todos os metodos a ver com matematica
 * 
 */
class ARMMath{ 
	/**
	 * Encapsulando para evitar retrabalho longo em caso de depreciação
	 * 
	 * valor de pi
	 * @return number
	 */
	public static function PI(){
		return pi() ;
	}
	/**
	 * Encapsulando para evitar retrabalho longo em caso de depreciação
	 * 
	 * Arredonda pra baixo
	 * @param number $value
	 * @return number
	 */
	public static function floor( $value ){
		return floor( $value ) ;
	}
	/**
	 * Retorna um numero randomico
	 * Encapsulando para evitar retrabalho longo em caso de depreciação
	 * 
	 * @param number $min
	 * @param number $max
	 * @return number
	 */
	public static function rand($min, $max){
		return rand( $min, $max ) ;
	}
	public static function ceil( $value ){
		return ceil( $value ) ;
	}
	public static function abs( $number ){
		return abs( $number ) ;
	}
	public static function cos( $float ){
		return cos( $float ) ;
	}
	/**
	 * 
	 * @param number $value valor a ser comparado
	 * @param number $comparedValue valor de base para comparação
	 * @return boolean
	 */
	public static function isHigher( $value, $comparedValue ){
		return ( $value > $comparedValue ) ;
	}
	/**
	 *
	 * @param number $value valor a ser comparado
	 * @param number $comparedValue valor de base para comparação
	 * @return boolean
	 */
	public static function isLower( $value, $comparedValue ){
		return ( $value < $comparedValue ) ;
	}
}