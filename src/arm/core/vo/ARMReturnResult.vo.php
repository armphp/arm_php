<?php
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @description	: Essa classe representa o padrao de retorno para as VOs
	 */

class ARMReturnResultVO {
	public $success;
	/**
	 * @var PetUserVO
	 */
	public $result;
	public $array_messages = array();
	function __construct($success = FALSE, $messages = array()){
		$this->success 				= $success;
		$this->array_messages 		= $messages;
	}
	public function addMessage($msg){
		$this->array_messages[] = $msg;
	}
	public function appendMessage($array){
		for($i = 0; $i < count($array); $i++){
			$this->addMessage($array[$i]);
		}
	}
	/**
	 * Checa se tem resultado em result
	 * Não se pode dizer que o result é array
	 * @return boolean
	 */
	public function hasResult(){
		return ($this->success && $this->result != NULL);
	}
	public function toJson(){
		$StdJson = new stdClass();
		$StdJson->success = ($this->success);
		$StdJson->response = $this->result;
		$StdJson->message = $this->array_messages;
		return json_encode($StdJson);
	}
}