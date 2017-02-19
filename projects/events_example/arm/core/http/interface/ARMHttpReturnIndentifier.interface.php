<?php
/**
 * (pt-br) Interface de classes que reconhecem qual o tipo de retorno que a requisição está pedindo
 * @author renatomiawaki
 *
 */
interface ARMHttpReturnIndentifierInterface{
	/**
	 * Need to return type of request result format
	 * if html, json, xml etc
	 * @return string
	 */
	function getType();
}