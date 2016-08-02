<?php
/**
 *
 * Para guardar informações de um período simples
 *
 * User: renatomiawaki
 * Date: 1/9/14
 * 
 */

class ARMDatePeriodVO extends ARMAutoParseAbstract {
	public $date_init ;
	public $date_end ;

	/**
	 * strtotime em todos os atributos de data
	 */
	public function convertToTime(){
		$this->date_init    = strtotime( $this->date_init ) ;
		$this->date_end     = strtotime( $this->date_end ) ;
	}
}