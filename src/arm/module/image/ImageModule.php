<?php
/**
 * Módulo para pegar utilizar a imagem sdk
 * Renato Seiji Miawaki
 */

use ARM\Modules\Images;

class ImageModule extends ARMBaseImageModuleAbstract {
	/**
	 * @var ARM\Modules\Images\ImageClientSDK
	 */
	protected static $imageModule ;
	/**
	 * new ImageClientSDK() ;
	 * @param null|string $alias
	 * @param bool $useDefaultIfNotFound
	 * @return ImageModule
	 */
	public static function getInstance( $alias = self::DEFAULT_GLOBAL_ALIAS, $useDefaultIfNotFound = FALSE ) {
		$instance = parent::getInstance( $alias, $useDefaultIfNotFound) ;
		self::$imageModule = new ARM\Modules\Images\ImageClientSDK() ;
		return $instance ;
	}
	/**
	 * Salva a imagem na api e retorna o id, independente de já existir imagem
	 * retorna o id da imagem em result
	 * @param ImageInfoPostVO $ImageInfoPostVO
	 * @param string $subFolderName
	 * @return ARMReturnResultVO
	 */
	public function saveImage( $arrayFileData , $subFolderName = "img" ){
		$ReturnResultVO = new ARMReturnResultVO() ;
		//MDemoLinkDAO::getInstance()->sele
		$slug = ARMDataHandler::getValueByStdObjectIndex($this->_config, "slug") ;
		self::$imageModule->setConfig( $this->getConfigImageToSave() ) ;
		$path = $arrayFileData["tmp_name"];
		$album_id = NULL ; //$subFolderName ; //esse subFolderName está vindo o id do quadro
		$alias = $slug ;//"quadro";

		$result 	= self::$imageModule->sendImage( $path, $album_id, $alias ) ;
		dd( $result ) ;
		$obj 		= json_decode( $result ) ;
		$code 		= ARMDataHandler::getValueByStdObjectIndex( $obj , "code" ) ;
		if( $code == 200 ){
			$result_api = ARMDataHandler::getValueByStdObjectIndex( $obj , "result" ) ;
			$id = ARMDataHandler::getValueByStdObjectIndex( $result_api , "id" ) ;
			if( $id ) {
				$ReturnResultVO->success = TRUE ;
				//precisa do caminho físico da imagem
				dd( self::$imageModule->getImageRawSrc( $id , $alias ) ) ;
				$ReturnResultVO->result = $id ;
			}
		}
		return $ReturnResultVO ;
	}

	/**
	 * TODO: ver o que se deve fazer aqui
	 * @param $path
	 * @param $locale string
	 * @throws Exception
	 */
	public function saveImageDatabase( $path , $locale = NULL ){
		$slug = ARMDataHandler::getValueByStdObjectIndex($this->_config, "slug") ;
		//usar o locale como alias. Ver o que fazer.
		$vo = ImageModelGateway::getInstance()->getVO() ;
		$vo->locale = $locale ;
		$vo->url = $path ;
		$result = ImageDAO::getInstance()->commitVO( $vo ) ;
		if( $result->hasResult() ) {
			return $result->getReturnId() ;
		}
		return NULL ;
	}

	/**
	 * Config para salvar imagens
	 * @return \ARM\Modules\Images\ImageClientConfigVO
	 */
	public function getConfigImageToSave(){
		//pega o config do image
		$app = ARMDataHandler::getValueByStdObjectIndex($this->_config, "app") ;
		$token_to_save = ARMDataHandler::getValueByStdObjectIndex($this->_config, "token_to_save") ;
		$url = ARMDataHandler::getValueByStdObjectIndex($this->_config, "url") ;
		$image404id = ARMDataHandler::getValueByStdObjectIndex($this->_config, "image404id") ;
		$config = new ARM\Modules\Images\ImageClientConfigVO() ;
		$config->app = $app ;
		$config->token = $token_to_save ;
		$config->url = $url ;
		return $config ;
	}

	/**
	 * Config para ler imagens
	 * @return \ARM\Modules\Images\ImageClientConfigVO
	 */
	public function getConfigImageToView(){
		$app = ARMDataHandler::getValueByStdObjectIndex($this->_config, "app") ;
		$token_to_view = ARMDataHandler::getValueByStdObjectIndex($this->_config, "token_to_view") ;
		$url = ARMDataHandler::getValueByStdObjectIndex($this->_config, "url") ;
		$image404id = ARMDataHandler::getValueByStdObjectIndex($this->_config, "image404id") ;
		$config = new ARM\Modules\Images\ImageClientConfigVO() ;
		$config->app = $app ;
		$config->token = $token_to_view ;
		$config->url = $url ;

		return $config ;
	}
	public function getImage404Id(){
		return ARMDataHandler::getValueByStdObjectIndex($this->_config, "image404id") ;
	}
}