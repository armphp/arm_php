<?php
/**
 * 
 * Inteface a ser implementada caso queira usar regras de rewrite
 * O ARM deixa livre para que faça a própria regra de rewrite
 * 
 * Caso precise fazer um redirect baseado em alguma url não permitida ou www. ou sem www.
 * Utilize a construtora da sua classe de rewrite
 * 
 * @author Renato Miawaki
 * @date 24/09/2013
 *
 */
interface ARMRewriteRuleInterface {
	
	/**
	 * @desc Recebe uma string que será reescrita caso haja uma regra que caiba dentro da url
	 * @param string $string
	 * @return string
	 */
	function rewrite($string);
}