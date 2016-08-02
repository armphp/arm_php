<?php

class ARMHtmlHeader{

    private $js = array();
    private $css = array();

    private $description ;
    private $keywords ;
    private $title ;
    private $is_html5 = TRUE ;
    private $iPadIcon = NULL ;
    private $favicon = NULL ;

    private $charset = "utf-8";

    private $customJSVariable = array();


    private $prepend_css	= "";
    private $append_css		= "";

    private $prepend_js		= "";
    private $append_js		= "";


    public function addJSVar( $name , $value ){
    	$this->customJSVariable[$name] = $value;
    }

    public function addJS( $js, $assetFilePath = NULL){
        if(is_array($js)){
            foreach ($js as $j)
                $this->js[$j] = $j;
        }else{
            $this->js[$js] = $assetFilePath;
        }
    }

    public function addCSS($css,  $assetFilePath = NULL, $media = 'all', $condition = FALSE){
        if(is_array($css)){
            foreach ($css as $c)
                $this->css[$c] = (object)array('media'=>$media, 'path'=>$c, 'condition'=>$ieOnly);
        }else{
            $this->css[$css] = (object)array('media'=>$media, 'path'=>($assetFilePath?$assetFilePath:$css), 'condition'=>$condition);
        }
    }

    public function asHTML5() {
        $this->is_html5 = TRUE ;
    }


    public function show( $_return = FALSE){
//         $url_config = ARMConfig::getRootUrl("config");

        if ( $this->is_html5  ) {
            $head = '<!DOCTYPE html>' . "\n" ;
            $head .= '<html lang="pt-br">' . "\n" ;
        }
        else {
            $head = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n" ;
            $head.= '<html xmlns="http://www.w3.org/1999/xhtml">'. "\n";
        }

        $head.= '<head>' . "\n";
//         $head.= '<meta http-equiv="X-UA-Compatible" content="IE=edge" >' . "\n";
        $head.= '<meta http-equiv="Content-Type" content="text/html; charset='. $this->charset .'" />'. "\n";
        $head.= '<meta name="description" content="' . $this->description . '" />' . "\n";

//         $head.= '<link rel="icon" type="ima	ge/x-icon" href="'  . Config::getRootUrl("favicon.ico") . '">' . "\n";
//         $head.= '<link rel="shortcut ico" type="images/x-icon" href="'  . Config::getRootUrl("favicon.ico") . '">' . "\n";

        $head.= '<meta name="keywords" content="' . $this->keywords . '" />' . "\n";
        $head.= '<title>' . $this->title . '</title>' . "\n";

//         if($this->FacebookHeaderInfoVO){
        	//@TODO: implementar isso que nem gente
//         	$head.= '<meta property="og:site_name" content="'.Config::getRootUrl().'" />'. "\n";;

//         	if($this->FacebookHeaderInfoVO->title != "")
//         		$head.= '<meta property="og:title" content="'.$this->FacebookHeaderInfoVO->title.'" />'. "\n";;

//         	if($this->FacebookHeaderInfoVO->images != "")
// 	        $head.= '<meta property="og:images" content="'.$this->FacebookHeaderInfoVO->images.'"/>'. "\n";;

//         }
        $head.= '<link rel="apple-touch-icon" href="' . $this->getIpadIcon() . '" />';

        $head.= '<link rel="shortcut icon" type="images/x-icon" href="' . $this->getFavicon() .'" />';



		$head.= $this->getHtmlCSSBlock() ;

		$head.= $this->getHtmlJSBlock() ;

        $head.="\n</head>" ;

		if($_return) return $head ;

        echo $head;
    }


    public function getHtmlCSSBlock(){

    	$html_code_block = $this->prepend_css . "\n";

    	foreach ($this->css as $slug =>$data){

    		if( ARMValidation::validateUrl($slug) ){
    			$uri_path = $slug ;
    		} else{
    			$uri_path = $data->path ;
    		}
    		if( !ARMValidation::validateUrl( $uri_path ) ) {
    			$html_code_block.= ("\n<!-- arquivo CSS {$uri_path} não é valido -->");
    			continue;
    		}
    		if($data->condition){
    			$cssIfStatment = "\n<!--[if {$data->condition}]>";

    			$html_code_block.= $cssIfStatment ;
    		}

    		$html_code_block.= "\n<link type=\"text/css\" media=\"" . $data->media . "\" rel=\"stylesheet\" href=\"" . $uri_path . "\" />";

    		if($data->condition)
    			$html_code_block.="\n<![endif]-->";
    	}

    	return $html_code_block . "\n" . $this->append_css ;

    }

    public function getHtmlJSBlock(){

    	$html_code_block = $this->prepend_js . "\n";

		$html_code_block.= "\n<script type=\"text/javascript\" >\n";
		$html_code_block.= "var APP_URL= '". ARMNavigation::getAppUrl() ."';\n";

		foreach ( $this->customJSVariable as $name=>$value ) {
			if( is_string($value)  ){
				$html_code_block.= sprintf("var %s= '%s';\n" , $name , $value );
			} elseif ( is_numeric($value) ){
				$html_code_block.= sprintf("var %s= %s ;\n" , $name , $value );
			} else{
				$html_code_block.= sprintf("var %s= %s;\n" , $name , json_encode($value) );
			}
		}

		$html_code_block.= "</script>\n";

    	foreach ($this->js as $slug =>$path){
    		if(  ARMValidation::validateUrl( $slug ) ||  is_null( $path ) ){
    			$uri_path = $slug;
    		}else{
    			$uri_path = $path;
    		}
    		if(  !ARMValidation::validateUrl( $uri_path ) ){
    			$html_code_block.= ("\n<!-- arquivo '{$slug}' não é válido ( tentando  : {$path}  )-->");
    			continue;
    		}

    		$html_code_block.= "\n<script type=\"text/javascript\" src=\"" . $uri_path . "\"></script>";
    	}



    	return $html_code_block . "\n" . $this->append_js;
    }

	public function setFavicon( $filename ) {
        $this->favicon = $filename ;
    }

    public function getFavicon() {
        if ( is_null( $this->favicon ) ) {
            return "/favicon.ico"  ;
        }

        return  $this->favicon ;
    }

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	public function getKeywords() {
		return $this->keywords;
	}

	public function setKeywords($keywords) {
		$this->keywords = $keywords;
		return $this;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	public function getCharset() {
		return $this->charset;
	}

	public function setIpadIcon( $filename ) {
		$this->iPadIcon = $filename ;
	}

	public function getIpadIcon() {
		return $this->iPadIcon ;
	}

	public function setCharset($charset) {
		$this->charset = $charset;
		return $this;
	}


	public function prependCss($prepend_css) {
		$this->prepend_css.= $prepend_css;
		return $this;
	}


	public function appendCss($append_css) {
		$this->append_css.= $append_css;
	}

	public function prependJs($prepend_js) {
		$this->prepend_js.= $prepend_js;
	}


	public function appendJs($append_js) {
		$this->append_js.= $append_js;
		return $this;
	}



}
