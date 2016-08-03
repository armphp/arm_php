<?php
/**
 * Todos os listeners receberam uma instancia dessa classe com os valores
 * @author: Renato Seiji Miawaki
 * Date: 20/02/16
 */

class ARMEventReciveInfoVO {
	/**
	 * simboliza a ordem da fila de vezes que o metodo já foi chamado
	 * @var int
	 */
	protected $count = 0 ;
	/**
	 * o valor que o próprio listener enviou para receber de volta
	 * @var
	 */
	public $listenerData ;
	/**
	 * o dado que quem despachou o evento enviou
	 * @var
	 */
	protected $data ;
	public function __construct( $count = 0 , $data ){
		$this->count = $count ;
		$this->data = $data ;
	}
	public function getData( $param = NULL ){
		if($param){
			return ARMDataHandler::getValueByStdObjectIndex( $this->data, $param ) ;
		}
		return $this->data ;
	}
	public function getCount(){
		return $this->count ;
	}
}