<?php

/**
 * SmartHtmlHeader is an class to automaticly generate some information for the header
 * @see ARMHtmlHeader
 * @author alanlucian
 *
 */
class ARMSmartHtmlHeader extends ARMHtmlHeader{


	function addCssFiles(  $css_files ) {

		if( !is_array( $css_files) )
			return ;

		foreach (  $css_files as $file ){
			$conditional = NULL ;
			preg_match_all("/_(IF|if)_(.*)\.css/", $file , $out);
			if( count($out[2]) > 0  ){
				$conditional = str_replace("_", " ", $out[2][0]);
			}

			$file_url =   $this->getAssetUrl( $file ) ;

			parent::addCSS( $file_url , $file_url,"all", $conditional );

		}
	}

	protected function getAssetUrl( $file ){

		$file_url = ARMConfig::getDefaultInstance()->getRootUrl( $file )  ;

		if( file_exists( $file ) && ARMConfig::getDefaultInstance()->isDev()  ) {
			$time = filemtime( $file ) ;
			$url_var_concat = (  strpos( $file_url , "?" ) !==FALSE ? "&" : "?" ) ;
			$file_url .= $url_var_concat . "filetime=". $time ;
		}

		return $file_url;
	}


	function addJsFiles( $js_files) {

		if( !is_array( $js_files) )
			return ;

		foreach (  $js_files as $file ){
			parent::addJS( $this->getAssetUrl( $file ) ) ;
		}
	}

}
