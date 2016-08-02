<?php
/**
 * Interface para classes que se proponham a salvar e exibir imagem
 * Os erros possíveis trate a Exception
 * User: Renato Seiji Miawaki
 * Date: 06/05/16
 * Time: 13:29
 */

interface ARMReadImagesInterface extends ARMModuleInterface{

	/**
	 * Distorce a imagem tornando-a desse tamanho sem cropar
	 * @param $original_image_path
	 * @param int $new_width
	 * @param int $new_height
	 * @param int $quality
	 * @return string
	 */
	function getImageExactlyMode( $original_image_path, $new_width = 100, $new_height = 100, $quality = 100) ;

	/**
	 * não distorce a imagem, mas faz um corte desse tamanho na imagem, possivelmente perdendo em todos os lados da imagem
	 * @param $original_image_path
	 * @param int $new_width
	 * @param int $new_height
	 * @param int $quality
	 * @return string
	 */
	function getImageCroped( $original_image_path, $new_width = 100, $new_height = 100, $quality = 100) ;

	/**
	 * faz um crop na imagem, baseado na largura, a altura é consequência
	 * @param $original_image_path
	 * @param int $new_width
	 * @param int $new_height
	 * @param int $quality
	 * @return string
	 */
	function getImageCropedPortraitMode( $original_image_path, $new_width = 100, $new_height = 100, $quality = 100) ;

	/**
	 * faz um crop na imagem, baseado na altura, a largura é consequência
	 * @param $original_image_path
	 * @param int $new_width
	 * @param int $new_height
	 * @param int $quality
	 * @return string
	 */
	function getImageCropedLandscapeMode( $original_image_path, $new_width = 100, $new_height = 100, $quality = 100) ;

	/**
	 * faz um crop na imagem e reconhece automaticamente, cropando o mínimo possível da imagem
	 * @param $original_image_path
	 * @param int $new_width
	 * @param int $new_height
	 * @param int $quality
	 * @return string
	 */
	function getImageAutoMode( $original_image_path, $new_width = 100, $new_height = 100, $quality = 100) ;

	/**
	 * Ele exibe a imagem, literalmente. não seria o redirect, e sim exibir mesmo
	 * Com header do arquivo correspondente
	 * @param $path
	 */
	function show( $path ) ;
}