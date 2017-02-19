<?php
/**
 * @author 	Mauricio Amorim
 * @desc	Interface da Classe RewriteRulerVO.
*/
include_once("arm/core/http/interface/ARMRewriteRuleInterface.php");
// include_once("library/facil3/core/vo/ReturnResultVO.class.php");
class ARMRewriteRuleVO implements ARMRewriteRuleInterface {
	/**
	 * @var (string) uma breve descrição da expressão regular
	 */
	private $description;
	
	/**
	 * @var (string or regexp) expressão regular ou string para verificar se bate com a string passada
	 */
	private $valueToFind;
	
	/**
	 * @var (string or regexp) expressão regular ou string para reescritura da string passada
	 */
	private $valueToRewrite;
	
	public function ARMRewriteRuleVO($valueToFind, $valueToRewrite, $description = NULL){
		$this->description 		= $description;
		$this->valueToFind 		= $valueToFind;
		$this->valueToRewrite 	= $valueToRewrite;
	}
	

	//------------------------------------------------------------- sets
	
	/**
	 * @desc uma breve descrição da expressão regular
	 * @param $description 
	 * @return void
	 */
	public function setDescription($description){
		$this->description = $description;
	}
	
	/**
	 * @desc seta uma expressão regular ou string para verificar se bate com a string passada
	 * @param (string or regexp)
	 * @return void
	 */
	public function setValueToFind($regexp_or_string){
		$this->valueToFind = $regexp_or_string;
	}
	
	/**
	 * @desc seta uma expressão regular ou string para reescritura da string passada
	 * @param (string or regexp)
	 * @return void
	 */
	public function setValueToRewrite($regexp_or_string){
		$this->valueToRewrite = $regexp_or_string;
	}	

	//--------------------------------------------------------- gets
	
	/**
	 * @desc retorna uma breve descrição dessa regra
	 * @return string
	 */
	public function getDescription(){
		return $this->description;	
	}
	
	/**
	 * @desc retorna uma expressão regular ou string para verificar se bate com a string passada
	 * @return (string or regexp)
	 */
	public function getValueToFind(){
		return $this->valueToFind;	
	}

	/**
	 * @desc retorna uma expressão regular ou string para reescritura da string passada
	 * @return (string or regexp)
	 */
	public function getValueToRewrite(){
		return $this->valueToRewrite;	
	}

	/**
	 * @desc Recebe uma string que será reescrita caso haja uma expressão regular dentro
	 *  de algum indice do array de expressões regulares de cada RewriteRule.
	 *  essa funcão retorna um ARMReturnResultVO onde ARMReturnResultVO->result = string reescrita e
	 *  ARMReturnResultVO->array_messages recebe a descrição da(s) regra(s) utilizada(s)
	 * @param $string
	 * @return $ReturnResultVO
	 */
	public function rewrite($string){
		$ReturnResultVO = new ARMReturnResultVO();
		//reescreve a string caso aja uma regra para ela
		//echo $this->getValueToFind()." - ".$this->getValueToRewrite()." - ".$string;exit();
		$rewrite = preg_replace($this->getValueToFind(), $this->getValueToRewrite(), $string);
		//verifica se a string foi reescrita, se sim verifica se tem mais regras a serem seguidas e adiciona a 
		//descrição da regra utilizada
		if($rewrite != NULL && $rewrite != FALSE && $rewrite != $string){
			$ReturnResultVO->success = TRUE;
			$ReturnResultVO->result = $rewrite;
			$ReturnResultVO->addMessage($this->description);			
		}else{
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->result = $string;
		}
		return $ReturnResultVO;
	}
	
	
}