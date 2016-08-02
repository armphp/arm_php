<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 22/04/16
 * Time: 14:45
 */

class ARMProxyModule extends ARMBaseModuleAbstract{
	protected $accept_all = FALSE ;
	protected $proxy_list ;
	/**
	 * @param null $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ARMProxyModule
	 */
	public static function getInstance ( $alias = NULL , $useDefaultIfNotFound = FALSE  ){
		return parent::getInstance( $alias, $useDefaultIfNotFound ) ;
	}
	/**
	 * @return null|ARMProxyConfigVO
	 */
	protected function getProxyConfig(){
		return $this->_config ;
	}
	protected $_forcedPost = array() ;
	public function addPostVar( $key, $value ){
		$this->_forcedPost[$key] = $value ;
	}

	protected $_forcedGet = array() ;
	public function addGetVar( $key, $value ){
		$this->_forcedGet[$key] = $value ;
	}

	/**
	 * @param $alias
	 * @return null|string
	 */
	public function proxyByAlias(){
		$config = $this->getProxyConfig() ;
//		li("config");
//		d($config);
		if(!$config){
			return NULL ;
		}
		if(!$config->url){
			return NULL ;
		}
		//adicionando, ou sobreescrevendo as variaveis GET
		foreach($_GET as $key => $value){
			//dd($_GET);
			if(is_array($value) ){
				$config->get_vars[$key] = $value;
			}else{
				@$config->get_vars->$key = $value;
			}

		}
		//adicionando, ou sobreescrevendo as variaveis POST
		foreach($_POST as $key => $value){
			//ao modificar para a propriedade do objeto o valor deu erro
			//esse parametro foi adicionado por causa do id passado para a ws
			if(isset($_POST["access_token"]) ){
				$config->post_vars[$key] = $value;
			}else{
				@$config->post_vars->$key = $value;
			}

		}
		//adicionando variaveis locais para post forçado
		foreach($this->_forcedPost as $key => $value){
			$config->post_vars->$key = $value ;
		}

		if($config->forced_method == "proxy"){
			//aqui é post sempre
			return $this->curlTo( $config->url, $config->get_vars, $config->post_vars ) ;
		}

		return $this->proxyTo( $config->url, $config->get_vars, $config->post_vars, $config->forced_method, $config->content_type ) ;
	}

	public function setConfig( $ob ){
		parent::setConfig( $ob ) ;
		$this->accept_all = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "accept_all" ) ;
		$this->proxy_list = array() ;
		$proxy_list = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "proxy_list" ) ;
		if( $proxy_list && is_array( $proxy_list ) ){
			foreach( $proxy_list as $item ){
				$proxyItem = new ARMProxyItemVO() ;
				$proxyItem->parseObject( $item ) ;
				$this->proxy_list[$proxyItem->alias] = $proxyItem ;
			}
		}
	}

	/**
	 * Enviando a url, gets e posts, ele faz a requisição forçada
	 * @param $url
	 * @param array $gets
	 * @param array $posts
	 */
	public function proxyTo( $url, $gets = array() , $posts = array(), $REQUEST_METHOD = NULL , $contentType = "application/x-www-form-urlencoded"){
		//forçando para teste
//		d($url);
//		d($posts);
//		dd($gets);
		if(!$REQUEST_METHOD){
			$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"] ;
		}
		if( $gets && count($gets) > 0 ) {
			$url .= "?" . http_build_query($gets);
		}
		
		$options = array(
			'http' => array(
				'header'  => "Content-type: $contentType\r\n",
				'method'  => $REQUEST_METHOD,
				'content' => http_build_query($posts)
			)
		);
		$context  = stream_context_create($options);
                $file_contents = mb_convert_encoding(file_get_contents($url, false, $context), 'HTML-ENTITIES', "UTF-8");
		return $file_contents;
	}
	public function curlTo( $url, $gets = array() , $posts = array() ){
		if( $gets && count($gets) > 0 ) {
			$url .= "?" . http_build_query($gets);
		}
		$data_string = json_encode( $posts );


		$ch = curl_init( $url );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
		);

		return curl_exec($ch);
	}

	public function proxyReturnConn () {

		$config = $this->getProxyConfig() ;
		if(!$config){
			return NULL ;
		}
		if(!$config->url){
			return NULL ;
		}
		foreach($_POST as $key => $value){
			$config->post_vars[$key] = $value ;
		}
		//adicionando variaveis locais para post forçado
		foreach($this->_forcedPost as $key => $value){
			$config->post_vars->$key = $value ;
		}

		$vars = "";
		if(  $config->post_vars && count( $config->post_vars) > 0 ) {
			$vars .= http_build_query( $config->post_vars);
		}
		$data_string = json_encode(  $vars );
		$data_string   = str_replace('"', '', $data_string);


		$ch = curl_init();

		curl_setopt ($ch, CURLOPT_URL, $config->url );
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt ($ch, CURLOPT_REFERER, $config->url );
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt ($ch, CURLOPT_POST, 1);


		$result = curl_exec ($ch);

		return $ch ;

	}


	public function proxyByConn($ch) {


		$config = $this->getProxyConfig() ;
		if(!$config){
			return NULL ;
		}
		if(!$config->url){
			return NULL ;
		}
		foreach($_POST as $key => $value){
			$config->post_vars[$key] = $value ;
		}
		//adicionando variaveis locais para post forçado
		foreach($this->_forcedPost as $key => $value){
			$config->post_vars->$key = $value ;
		}

		$vars = "";
		if(  $config->post_vars && count( $config->post_vars) > 0 ) {
			$vars .= http_build_query( $config->post_vars);
		}
		$data_string = json_encode(  $vars );
		$data_string   = str_replace('"', '', $data_string);
		curl_setopt ($ch, CURLOPT_REFERER, $config->url . "?" . $data_string );
		curl_setopt ($ch, CURLOPT_URL, $config->url . "?" . $data_string );
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");

		$result = curl_exec ( $ch ) ;

		return $result ;

	}

}