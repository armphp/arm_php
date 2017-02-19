<?php
/**
 *
 * Módulo para configurar o token da api via json e não precisar deixar hardcoded
 *
 * User: renatomiawaki
 * Date: 6/9/15
 * 
 */

class ImageConfigVO {
	/**
	 * @var int 404 image id
	 */
	public $image404id ;
	//configs do sdk de imagens
	/**
	 * @var string app api name
	 */
	public $app ;
	/**
	 * @var string
	 */
	public $alias ;
	/**
	 * @var string token
	 */
	public $token_to_view ;
	/**
	 * @var string token
	 */
	public $token_to_save ;
	/**
	 * @var api url
	 */
	public $url ;
}