<?php


# ========================================================================#
#
#  Author:    Rajani .B
#  Version:     1.0
#  Date:      07-July-2010
#  Purpose:   Resizes and saves images
#  Requires : Requires PHP5, GD library.
#  Usage Example:
#                     include("classes/resize_class.php");
#                     $resizeObj = new resize('images/cars/large/input.jpg');
#                     $resizeObj -> resizeImage(150, 100, 0);
#                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
#
#
# ========================================================================#

/**
 * Upgrade class
 * User: Renato Miawaki - reytuty@gmail.com
 * Date: 01/12/15
 * @version
 */

class ARMResizeImage {
	//tipos possiveis
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

	// *** Class variables
	private $image;
	private $width;
	private $height;
	private $imageResized;
	private $extension_file;

	private $imageType ;
	function __construct($fileName){
		// *** Open up the file
		$this->imageType = $this->getImageType( $fileName ) ;
		$this->image = $this->openImage( $fileName );
		// save the extension
		$this->extension_file = strtolower(ARMDataHandler::returnExtensionOfFile($fileName));
		// *** Get width and height
		try{
			$this->width  = imagesx($this->image);
			$this->height = imagesy($this->image);
		} catch(ErrorException $e){
			var_dump($fileName);
			var_dump($this->image);
		}
	}

	/**
	 * 1	IMAGETYPE_GIF
	2	IMAGETYPE_JPEG
	3	IMAGETYPE_PNG
	4	IMAGETYPE_SWF
	5	IMAGETYPE_PSD
	6	IMAGETYPE_BMP
	7	IMAGETYPE_TIFF_II (intel byte order)
	8	IMAGETYPE_TIFF_MM (motorola byte order)
	9	IMAGETYPE_JPC
	10	IMAGETYPE_JP2
	11	IMAGETYPE_JPX
	12	IMAGETYPE_JB2
	13	IMAGETYPE_SWC
	14	IMAGETYPE_IFF
	15	IMAGETYPE_WBMP
	16	IMAGETYPE_XBM
	 * @param $file
	 * @return string
	 */
	public function getImageType($file){
		$type = exif_imagetype ( $file ) ;

		$types = array() ;
		$types[1] = ".gif";
		$types[2] = ".jpg";
		$types[3] = ".png";
		$types[4] = ".swf";
		$types[5] = ".psd";
		$types[6] = ".bmp";
		$types[7] = ".tiff";
		$types[8] = ".tiff";
		if(isset($types[$type])){
			return $types[$type] ;
		}
		return "";
	}
	## --------------------------------------------------------
	private function openImage($file){
		// *** Get extension
		$extension = $this->imageType;
		//echo ARMDebug::li($extension);
		//$extension = ARMDataHandler::returnExtensionOfFile($file);
		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				$img = @imagecreatefromjpeg($file);
				//echo ARMDebug::li("eh jpg");
				break;
			case '.gif':
				$img = @imagecreatefromgif($file);
				//echo ARMDebug::li("eh gif");
				break;
			case '.png':
				$img = @imagecreatefrompng($file);
//                        //abaixo do php.net
//                        imagealphablending($img, false);
//						imagesavealpha($img, true);
				// echo ARMDebug::li("eh png");
				break;
			default:
				//echo ARMDebug::li("eh outra coisa:".($extension == '.png'));
				$img = false;
				break;
		}
		return $img;
	}
	## --------------------------------------------------------
	/**
	 * Redimensiona a imagem
	 * @param $newWidth
	 * @param $newHeight
	 * @param string $option
	 * @param bool $cropStartX
	 * @param bool $cropStartY
	 * @param bool $cropWidth
	 * @param bool $cropHeight
	 */
	public function resizeImage($newWidth, $newHeight, $option = self::MODE_TYPE_AUTO, $cropStartX = false, $cropStartY = false, $cropWidth = false, $cropHeight = false){
		//http://www.democrart.com.br/image/get_image/max_width.994/max_height.268/crop.1/ref_id.882/?force=1&testephoto=1
		$debugando = isset($_GET["testephoto"]);
		// *** Get optimal width and height - based on $option
		$optionArray = $this->getDimensions($newWidth, $newHeight, $option);
		if($debugando){
			echo ARMDebug::li("resizeImage : $newWidth, $newHeight, $option ");
			ARMDebug::dump($optionArray);
		}
		$optimalWidth  = $optionArray['optimalWidth'];
		$optimalHeight = $optionArray['optimalHeight'];
		if($debugando){
			echo ARMDebug::li(" depois resizeImage  optimal: $optimalWidth, $optimalHeight, new : $newWidth, $newHeight, $option , this->width: $this->width, this->height : $this->height");
		}
		// *** Resample - create images canvas of x, y size
		$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
		if($this->extension_file == "png") imagealphablending($this->imageResized, false);

		imagecopyresampled( $this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height );
		if($this->extension_file == "png"){
			imagesavealpha($this->imageResized, true);
		}
		// *** if option is 'crop', then crop too
		if ($option == 'crop') {
			if ($cropStartX && $cropStartY && $cropWidth && $cropHeight) {
				$this->crop($optimalWidth, $optimalHeight, $cropWidth, $cropHeight, $cropStartX, $cropStartY);
			}
			else{
				$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
			}
		}
	}

	## --------------------------------------------------------

	/**
	 * @param $newWidth
	 * @param $newHeight
	 * @param $option
	 * @return array tosca com 2 indices
	 */
	private function getDimensions($newWidth, $newHeight, $option)
	{
		try{

		}catch(Exception $e){

		}
		switch ($option)
		{
			case self::MODE_TYPE_EXACT:
				$optimalWidth = $newWidth;
				$optimalHeight= $newHeight;
				break;
			case self::MODE_TYPE_PORTRAIT:
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight= $newHeight;
				break;
			case self::MODE_TYPE_LANDSCAPE:
				$optimalWidth = $newWidth;
				$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				break;
			case self::MODE_TYPE_AUTO:
				if(($this->width/$newWidth)>($this->height/$newHeight)){
					return $this->getDimensions($newWidth, $newHeight, self::MODE_TYPE_LANDSCAPE);
				} elseif(($this->width/$newWidth)<($this->height/$newHeight)){
					//calcula pela largura
					return $this->getDimensions($newWidth, $newHeight, self::MODE_TYPE_PORTRAIT);

				} else {
					//a relação é igual, pode manter o valor
					$optimalWidth	= $newWidth;
					$optimalHeight	= $newHeight;
				}
				break;
			case self::MODE_TYPE_CROP:
			default:
				$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
				$optimalWidth = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];
				break;
		}
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	## --------------------------------------------------------

	private function getSizeByFixedHeight($newHeight)
	{
		$ratio = $this->width / $this->height;
		$newWidth = $newHeight * $ratio;
		return $newWidth;
	}

	private function getSizeByFixedWidth($newWidth)
	{
		$ratio = $this->height / $this->width;
		$newHeight = $newWidth * $ratio;
		return $newHeight;
	}

	## --------------------------------------------------------

	private function getOptimalCrop($newWidth, $newHeight)
	{

		$heightRatio = $this->height / $newHeight;
		$widthRatio  = $this->width /  $newWidth;

		if ($heightRatio < $widthRatio) {
			$optimalRatio = $heightRatio;
		} else {
			$optimalRatio = $widthRatio;
		}

		$optimalHeight = $this->height / $optimalRatio;
		$optimalWidth  = $this->width  / $optimalRatio;

		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}
	public function showResized(){
		$extension = $this->imageType;
		//echo ARMDebug::li($extension);
		//$extension = ARMDataHandler::returnExtensionOfFile($file);
		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				header("Content-type: images/jpeg");
				imagejpeg( $this->imageResized );
				break;
			case '.gif':
				header("Content-type: images/gif");
				imagegif( $this->imageResized );
				break;
			case '.png':
				header("Content-type: images/png");
				imagepng( $this->imageResized );
				break;
			case '.bmp':
				header("Content-type: images/vnd.wap.wbmp");
				imagewbmp( $this->imageResized );
			default:
				return ;
				break;
		}
		imagedestroy( $this->imageResized ) ;
	}

	## --------------------------------------------------------

	private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight, $startX = false, $startY = false)
	{
		// *** Find center - this will be used for the crop
		$cropStartX = (($startX) ? ($startX) : ( ( $optimalWidth / 2) - ( $newWidth /2 ) ));
		$cropStartY = (($startY) ? ($startY) : ( ( $optimalHeight/ 2) - ( $newHeight/2 ) ));

		// *** Resample - create images canvas of x, y size
		$crop = imagecreatetruecolor($newWidth , $newHeight);
		if($this->extension_file == "png") imagealphablending($crop, false);

		imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
		//
		imagecopyresampled($crop, $this->imageResized, 0, 0, $cropStartX, $cropStartY, 	$newWidth, 		$newHeight , 	$newWidth, 		$newHeight);
		if($this->extension_file == "png"){
			imagesavealpha($crop, true);
		}


		$this->imageResized = $crop;
	}

	## --------------------------------------------------------

	public function saveImage($savePath, $imageQuality="100")
	{
		// *** Get extension
		$extension = strrchr($savePath, '.');
		$extension = strtolower($extension);
		$folder = ARMDataHandler::returnFoldernameOfFilepath($savePath) ;
		if($folder){
			ARMDataHandler::createRecursiveFoldersIfNotExists( $folder ) ;
		}

		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this->imageResized, $savePath, $imageQuality);
				}
				break;

			case '.gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($this->imageResized, $savePath);
				}
				break;

			case '.png':
				// *** Scale quality from 0-100 to 0-9
				$scaleQuality = round(($imageQuality/100) * 9);

				// *** Invert quality setting as 0 is best, not 9
				$invertScaleQuality = 9 - $scaleQuality;

				if (imagetypes() & IMG_PNG) {
					//echo ARMDebug::li("vai salvar a imagem: $savePath, $invertScaleQuality ");
					imagepng($this->imageResized, $savePath, $invertScaleQuality);
					//exit();

				}
				break;

			// ... etc

			default:
				// *** No extension - No save.
				break;
		}

		imagedestroy($this->imageResized);
	}


	## --------------------------------------------------------

}