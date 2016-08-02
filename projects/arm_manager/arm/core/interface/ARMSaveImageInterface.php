<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 09/05/16
 * Time: 23:18
 */

interface ARMSaveImageInterface {
	/**
	 * Em ->result deve vir o caminho da imagem para salvar no banco ou algo do tipo
	 * @param string $imagePath caminho da imagem enviada
	 * @param string $subFolder //caminho de sub folder caso queira
	 * @param string $forceFormat se null, guarda o original, se jpg força jpg, mesmo para gif, png e assim por diante
	 * @return string
	 * @throws Exception
	 */
	function saveImage( $imagePath , $subFolder = "" );
}