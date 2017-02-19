<?php
/**
 * @author 	Mauricio Amorim
 * @desc	Interface da Classe de ARMRewriteVO.
*/
class ARMRewriteVO implements ARMRewriteRuleInterface {
	/**
	 * @var (array) array de RewriteRuleVO
	 */
	private $arrayRewriteRuleVO = array();
	
	public $stopInFirstSuccess = FALSE;
	
	/**
	 * @desc adiciona uma RewriteRuleVO a ser utilizada na string a ser passada
	 * @return void
	 */
	public function addRewriteRuleVO(ARMRewriteRuleInterface $RewriteRuleVO){
		$this->arrayRewriteRuleVO[] = $RewriteRuleVO;
	}
	
	/**
	 * @desc Recebe uma string que será reescrita caso haja uma regra para a mesma
	 * @param $string
	 * @return string
	 */
	public function rewrite($string){
		foreach($this->arrayRewriteRuleVO as $RewriteRuleVO){
			$ReturnResultVO = $RewriteRuleVO->rewrite($string);
			//ARMDebug::print_r($ReturnResultVO);
			if($ReturnResultVO->success){
				$string = $ReturnResultVO->result;
				if($this->stopInFirstSuccess){
					break;
				}
			}
		}
		return $string;
	}
}