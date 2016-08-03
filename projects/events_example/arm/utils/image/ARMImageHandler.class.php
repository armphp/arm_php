<?php
/**
 * @copyright iDress - All rights reserved | Todos os direitos reservados
 * 
 * Class responsable in create and show Thumb Images, using how original images
 * that had been passed by argument in the Constructor method.
 * 
 * Classe responsável em criar e exibir miniatura de imagens, utilizando como imagem original
 * a mesma que foi passado como argumento para o mótodo construtor.
 * 
 * @date 2007/09/24 11:16
 * @author Renato Seiji Miawaki / Victor Godinho
 * @company iDress Assessoria e Soluçoes em Informática
 * @version 1.3.2
 * @upgrade Renato Seiji Miawaki
 * @upgrade Renato Seiji Miawaki - reytuty@gmail.com
 *
 *
 * @version 2.0
 * Date: 01/12/15
 * @upgrade Renato Seiji Miawaki
 */


class ARMImageHandler {
	/**
	 * Retorna quantos dpi s tem uma imagem
	 * @param $image_url
	 * @return array
	 */
	public static function getDpi( $image_url ){
		// open the file and read first 20 bytes.
		$a = fopen($image_url, 'r');
		$string = fread($a, 20);
		fclose($a);
		// get the value of byte 14th up to 18th
		$data = bin2hex(substr($string, 14, 4));
		$x = substr($data, 0, 4);
		$y = substr($data, 4, 4);
		return array(hexdec($x), hexdec($y));
	}

	/**
	 * @var string da o strash
	 */
	const MODE_TYPE_EXACT 			= 'exact';
	/**
	 * @var string RETRATO
	 */
	const MODE_TYPE_PORTRAIT		= 'portrait';
	/**
	 * @var string paisagem
	 */
	const MODE_TYPE_LANDSCAPE		= 'landscape';
	/**
	 * @var string calculo automático respeitando o máximo possível
	 */
	const MODE_TYPE_AUTO			= 'auto';
	/**
	 * @var string cropa
	 */
	const MODE_TYPE_CROP			= 'crop';

	/**
	 * @param $original_image_path
	 * @param $new_width
	 * @param $new_height
	 * @param $new_image_path
	 * @param bool $crop force to images croping
	 * @param int $quality 1 > 100
	 * @return null
	 */
	public static function generateImage( $original_image_path, $new_image_path = NULL, $new_width = 100, $new_height = 100, $mode = NULL, $quality = 100){
		if($mode == NULL){
			$mode = self::MODE_TYPE_AUTO ;
		}
		$ARMResizeImage = new ARMResizeImage( $original_image_path ) ;
		$ARMResizeImage->resizeImage($new_width, $new_height, $mode ) ;
		if( ! $new_image_path ){
			//direct show
			$ARMResizeImage->showResized() ;
			die;
		}
		return $ARMResizeImage->saveImage( $new_image_path , $quality) ;
	}
	public static function getImageRenamedByConfig( $fileName , $new_width = 100, $new_height = 100, $mode = NULL, $quality = 100 ){
		$newFileName =  ARMDataHandler::returnFilenameWithoutExtension( ARMDataHandler::returnFilenameOfFolderPath( $fileName ) ) ;
		return "{$newFileName}_{$new_width}x{$new_height}_m_{$mode}_a{$quality}.".ARMDataHandler::returnExtensionOfFile( $fileName ) ;
	}
}
