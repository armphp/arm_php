<?php
/**
 *
 * User: renato
 * Date: 05/08/16
 * Time: 13:27
 */

class MinhaClasseSoltaNoMundo {
	public static function meuMetodoEstatico( $ob ){
		//echo
		li( "chamou ".__CLASS__." > ".__METHOD__." ˜ ".__LINE__ ) ;
		//dump
		dd($ob) ;
	}
	public function metodoDaClasse($ob){
		//echo
		li( "chamou ".__CLASS__." > ".__METHOD__." ˜ ".__LINE__ ) ;
		//dump
		dd($ob) ;
	}
}