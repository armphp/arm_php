<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 28/10/15
 * Time: 03:20
 */

class FacebookAppModule extends ARMBaseModuleAbstract{
	/**
	 * @param string $alias
	 * @return FacebookAppModule
	 */
	public static function getInstance( $alias = "" ){
		return parent::getInstance( $alias ) ;
	}
	protected $fb ;

	public function facebookRegisterOrLogin( $fbId , $data ){
		$fbId = trim($fbId) ;
		$result = new ARMReturnResultVO();
		if(!$fbId){
			$result->addMessage("id?");
			return $result ;
		}
		//pesquisa o user pelo fb id
		$vo = PetFacebookModelGateway::getInstance()->getVO();
		$vo->facebook_id = $fbId ;
		$returnData = PetFacebookModule::getInstance()->getByVO($vo);
		$result->success = $returnData->hasResult() ;
		if( $result->success ){
			//LOGANDO
			/* @var $fbUserVO PetFacebookVO */
			$fbUserVO = $returnData->result[0] ;
			//já existe pega os dados do user para fazer o login
			$ReturnDataUser = PetUserDAO::getInstance()->selectByVO((object)array("id" => $fbUserVO->user_id, "active" => 1));
			if( $ReturnDataUser->hasResult() ){
				$result->success = TRUE ;
				$this->startUserSession( $ReturnDataUser->result[0] ) ;
				return $result ;
			}
		}
		//CADASTRANDO PELO FACEBOOK

		/**
		 * data:
		{
		"id": "720352338",
		"name": "Renato Seiji Miawaki",
		"last_name": "Miawaki",
		"gender": "male",
		"age_range": {
		"min": 21
		},
		"devices": [
		{
		"os": "iOS"
		},
		{
		"os": "Android"
		}
		],
		"picture": {
		"data": {
		"is_silhouette": false,
		"url": "https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpt1/v/t1.0-1/p50x50/12108959_10153252361787339_6166892101953405300_n.jpg?oh=4d3561aef28a25783b1dab5bebc76318&oe=56B309E1&__gda__=1454151324_a55c841b23751f08b5cdfc21f78f521c"
		}
		},
		}
		 */
		d($fbId);
		dd($data);
		return $result ;
	}
	/**
	 * retorna Singleton da classe do Facebook
	 * @return \Facebook\Facebook
	 */
	public function getFb(){
		if( ! $this->fb ){
			$app_id = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "app_id" ) ;
			$app_secret = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "app_secret" ) ;
			$default_graph_version = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "default_graph_version" ) ;
			$arrayConfig = [
				'app_id' => $app_id ,
				'app_secret' => $app_secret ,
				'default_graph_version' => $default_graph_version,
			];
			ARMDebug::ifPrint($arrayConfig, "fb_module") ;
			$this->fb = new Facebook\Facebook($arrayConfig);

			$access_token = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "access_token" ) ;
			ARMDebug::ifLi("access_token:".$access_token, "fb_module") ;
			if( $access_token ){
				$this->fb->setDefaultAccessToken( $access_token ) ;
			}
		}
		return $this->fb ;
	}

	/**
	 * @param array $permissions
	 * @return \Facebook\GraphNodes\GraphUser
	 */
	public function getUserInfo($permissions = ['id','email', "name"]){
		$fb = $this->getFb() ;
		$accessResult = $this->getAccessToken() ;
		if($accessResult->success){
			try {
				// Returns a `Facebook\FacebookResponse` object
				$response = $fb->get('/me?fields='.implode("," , $permissions), $accessResult->result);
				return $response->getGraphUser();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
		}
		return NULL ;
	}

	/**
	 * Retorna a url para se fazer login externo no facebook já com o callback configurado
	 * @param array $permissions
	 * @return string
	 */
	public function getLoginUrl($permissions = ['email', 'user_likes']){
		$helper = $this->getFb()->getRedirectLoginHelper();
		return $helper->getLoginUrl($this->getCallbackUrl(), $permissions);
	}
	public function getCallbackUrl(){
		return htmlspecialchars( str_replace("{app_url}", ARMNavigation::getAppUrl(), ARMDataHandler::getValueByStdObjectIndex( $this->_config , "callback" ) ) ) ;
	}
	//singleton de resultado
	protected $_accessToken ;
	/**
	 * Após o retorno do login, pegue o token para acesso as informações
	 * @return ARMReturnResultVO
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function getAccessToken(){
		$accessToken = ARMSession::getVar('fb_access_token' );

		if( ! $this->_accessToken){
			$result = new ARMReturnResultVO();
			if( $accessToken ){
				//em session
				$result->success = TRUE ;
				$result->result = $accessToken ;
				return $result ;
			}
			$fb = $this->getFb();
			$helper = $fb->getRedirectLoginHelper();

			try {
				$accessToken = $helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				$result->addMessage('Graph returned an error: ' . $e->getMessage());
				return $result ;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				$result->addMessage('Facebook SDK returned an error: ' . $e->getMessage());
				return $result ;
			}

			if (! isset($accessToken)) {
				if ( $helper->getError() ) {
					$result->addMessage('Unauthorized:401');
					$result->addMessage("Error: " . $helper->getError() .
						"\n"."Error Code: " . $helper->getErrorCode() .
						"\n"."Error Reason: " . $helper->getErrorReason() .
						"\n"."Error Description: " . $helper->getErrorDescription() );
				} else {
					$result->addMessage('Unauthorized:400 Bad request');
				}
				return $result ;
			}

			// Logged in
//		d($accessToken->getValue());
			// The OAuth 2.0 client handler helps us manage access tokens
			$oAuth2Client = $fb->getOAuth2Client();
			// Get the access token metadata from /debug_token
			$tokenMetadata = $oAuth2Client->debugToken($accessToken);

			$app_id = ARMDataHandler::getValueByStdObjectIndex( $this->_config , "app_id" ) ;
			// Validation (these will throw FacebookSDKException's when they fail)
			$tokenMetadata->validateAppId($app_id);
			// If you know the user ID this access token belongs to, you can validate it here
			// $tokenMetadata->validateUserId('123');
			$tokenMetadata->validateExpiration();

			if (! $accessToken->isLongLived()) {
				// Exchanges a short-lived access token for a long-lived one
				try {
					$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
				} catch (Facebook\Exceptions\FacebookSDKException $e) {
					$result->addMessage("Error getting long-lived access token: " . $helper->getMessage() ) ;
					return $result ;
				}
			}

			$result->success = TRUE ;
			$result->result = (string) $accessToken ;
			ARMSession::setVar('fb_access_token', (string) $accessToken );
			// User is logged in with a long-lived access token.
			// You can redirect them to a members-only page.
			// header('Location: https://example.com/members.php');
			//deu certo, salva o resultado
			$this->_accessToken = $result ;
		}
		return $this->_accessToken ;
	}
}