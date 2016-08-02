<?php
/**
 * @author 		: Brian Vaughn
 * @reference 	: http://www.devshed.com/c/a/PHP/Dynamic-ARMImageWatermarking-with-PHP/
 *
 * @author 		: Renato Miawaki
 * @version 	: 1.1
 * @desc 		: tornei a classe statica, agora ela já cria o arquivo dentro do metodo create_watermark
 */

class ARMImageWatermark{
	private static function imageCreateFromX($img_url){
		$type = ARMDataHandler::returnExtensionOfFile($img_url);
		switch(strtolower($type)){
			case "gif":
				return imagecreatefromgif($img_url);
				break;
			case "png":
				return imagecreatefrompng($img_url);
				break;
			case "jpeg":
			case "jpg":
			default:
				return imagecreatefromjpeg($img_url);
				break;
		}
	}
	private static function imageX($p_image, $p_img_url, $p_quality = 100){
		$type = ARMDataHandler::returnExtensionOfFile($p_img_url);
		switch(strtolower($type)){
			case "gif":
				imagegif($p_image, $p_img_url);
				break;
			case "png":
				imagepng($p_image, $p_img_url);
				break;
			case "jpeg":
			case "jpg":
			default:
				imagejpeg($p_image, $p_img_url, 100);
				break;
		}
	}
	/**
	 * @param $main_img_src			src da imagem que vai ter marca dagua
	 * @param $watermark_img_src	src do PNG da imagem que será usada como marca dagua
	 * @param $new_image_path		src da imagem que deve resultar já com marca dagua
	 * @param $alpha_level			transparencia da marca dagua
	 * @return void
	 */
	public static function create_watermark( $main_img_src, $watermark_img_src, $new_image_path, $alpha_level = 100 ) {
		$main_img_obj				= self::imageCreateFromX($main_img_src);
		$watermark_img_obj			= self::imageCreateFromX($watermark_img_src);
		
//		gambiarra q transforma a imagem de fundo em jpg caso ela e a merca d'agua sejam .png para evitar zica
		$type_main_img 		= ARMDataHandler::returnExtensionOfFile($main_img_src);	
		$type_watermark_img = ARMDataHandler::returnExtensionOfFile($watermark_img_src);	
		if($type_main_img == "png" && $type_main_img == "png"){
			$outputFile 	= str_replace(".jpg", ".png", $main_img_src);
//			print_r($outputFile);exit;
			imagejpeg($main_img_obj, $outputFile);
    		imagedestroy($main_img_obj);
			
    		$main_img_obj	= imagecreatefromjpeg($outputFile);
		}
//		fim da gambiarra
		
		$alpha_level	/= 100;	# convert 0-100 (%) alpha to decimal
	
		# calculate our images dimensions
		$main_img_obj_w	= imagesx( $main_img_obj );
		$main_img_obj_h	= imagesy( $main_img_obj );
		$watermark_img_obj_w	= imagesx( $watermark_img_obj );
		$watermark_img_obj_h	= imagesy( $watermark_img_obj );
		
		# determine center position coordinates
		$main_img_obj_min_x	= floor( ( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ) );
		$main_img_obj_max_x	= ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
		$main_img_obj_min_y	= floor( ( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ) );
		$main_img_obj_max_y	= ceil( ( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ) ); 
		
		# create new images to hold merged changes
		$return_img	= imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );
	
		# walk through main images
		for( $y = 0; $y < $main_img_obj_h; $y++ ) {
			for( $x = 0; $x < $main_img_obj_w; $x++ ) {
				$return_color	= NULL;
				
				# determine the correct pixel location within our watermark
				$watermark_x	= $x - $main_img_obj_min_x;
				$watermark_y	= $y - $main_img_obj_min_y;
				
				# fetch color information for both of our images
				$main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );
				
				# if our watermark has a non-transparent value at this pixel intersection
				# and we're still within the bounds of the watermark images
				if (	$watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
							$watermark_y >= 0 && $watermark_y < $watermark_img_obj_h ) {
					$watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );
					
					# using images alpha, and user specified alpha, calculate average
					$watermark_alpha	= round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
					$watermark_alpha	= $watermark_alpha * $alpha_level;
				
					# calculate the color 'average' between the two - taking into account the specified alpha level
					$avg_red		= self::_get_ave_color( $main_rgb['red'],		$watermark_rbg['red'],		$watermark_alpha );
					$avg_green	= self::_get_ave_color( $main_rgb['green'],	$watermark_rbg['green'],	$watermark_alpha );
					$avg_blue		= self::_get_ave_color( $main_rgb['blue'],	$watermark_rbg['blue'],		$watermark_alpha );
					
					# calculate a color index value using the average RGB values we've determined
					$return_color	= self::_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );
					
				# if we're not dealing with an average color here, then let's just copy over the main color
				} else {
					$return_color	= imagecolorat( $main_img_obj, $x, $y );
					
				} # END if watermark
		
				# draw the appropriate color onto the return images
				imagesetpixel( $return_img, $x, $y, $return_color );
		
			} # END for each X pixel
		} # END for each Y pixel
		
		# display our watermarked images - first telling the browser that it's a JPEG, 
		# and that it should be displayed inline
//		header( 'Content-Type: images/jpeg' );
//		header( 'Content-Disposition: inline; filename=' . $_GET['src'] );
		//imagejpeg( $return_img, $new_image_path, 100);
		self::imageX($return_img, $new_image_path, 100);
	
	} # END create_watermark()
	
	
	private static function create_watermark_com_png(){
	}
	
	# average two colors given an alpha
	private static function _get_ave_color( $color_a, $color_b, $alpha_level ) {
		return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b	* $alpha_level ) ) );
	} # END _get_ave_color()
		
	# return closest pallette-color match for RGB values
	private static function _get_image_color($im, $r, $g, $b) {
		$c=imagecolorexact($im, $r, $g, $b);
		if ($c!=-1) return $c;
		$c=imagecolorallocate($im, $r, $g, $b);
		if ($c!=-1) return $c;
		return imagecolorclosest($im, $r, $g, $b);
	} # EBD _get_image_color()

} # END watermark API