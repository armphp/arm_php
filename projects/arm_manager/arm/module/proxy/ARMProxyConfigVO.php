<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 22/04/16
 * Time: 14:45
 */

class ARMProxyConfigVO {
	/**
	 * Nome de referência
	 *
	 * @var string
	 */
	public $alias ;
	/**
	 * url onde será feita a requisição
	 * Ele repassa exatamente o que recebeu
	 * @var string
	 */
	public $url ;
	/**
	 * variaveis posts recebidas
	 * @var string[]
	 */
	public $post_vars ;
	/**
	 * variaveis gets recebidas
	 * @var string[]
	 */
	public $get_vars ;

	/**
	 * Deixe vazio se não precisar,
	 * Mas se preenchido, ele força um metodo de requisição, ex: GET ou POST
	 * Caso vazio, ele utiliza o mesmo metodo utilizado na requisição em que se encontra
	 * @var string
	 */
	public $forced_method ;

	/**
	 * Para outros formatos, modifique no config
	 * @var string
	 */
	public $content_type = "application/x-www-form-urlencoded" ;
}