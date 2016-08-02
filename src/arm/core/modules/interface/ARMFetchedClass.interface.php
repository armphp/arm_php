<?php

/**
 * 
 * Interface para garantir uma classe que implemente o metodo para dar fetch em um objeto
 * Util para Entitys e objetos do tipo config e para o uso dos módulos para encontrar o config
 * 
 * @author renatomiawaki
 *
 */
interface ARMFetchedClassInterface{
	/**
	 * Metodo para pegar um objeto e parsear dentro da classe específica
	 * @param object $object
	 */
	function fetchObject( object $object );
}