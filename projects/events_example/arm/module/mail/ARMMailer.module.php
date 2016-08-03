<?php

/**
 * Module class to send emial
 * @author alanlucian
 *
 */
class ARMMailerModule extends ARMBaseModuleAbstract {

	/**
	 * 
	 * @var ARMMailerConfigVO
	 */
	var $_config ;
	
	public function getParsedConfigData( $configResult ){

		$def = new ARMMailerConfigVO();
		$this->_config = ARMDataHandler::objectMerge(  $configResult , $def) ;
		
		if(  count( $this->_config->bcc_list ) > 0 ) {
			foreach ( $this->_config->bcc_list as $email_info ){
				$name = NULL ;
				if( is_array( $email_info ) )
					list( $email_info, $name ) = $email_info ;
				 
				$this->addBCC( $email_info ) ;
			} 
		}
		

		if(  count( $this->_config->cc_list ) > 0 ) {
			foreach ( $this->_config->cc_list as $email_info ){
				$name = NULL ;
				if( is_array( $email_info ) )
					list( $email_info, $name ) = $email_info ;
					
				$this->addCC( $email_info ) ;
			}
		}
		
		return $configResult ;
	}
	
	
	/**
	 * @return @return ARMMailer
	 */
	public static function getLastInstance() {
		return parent::getLastInstance() ;
	}
	
	/**
	 *  Singleton -  Método padrão para toda DAO.
	 *  @return ARMMailerModule
	 */
	public static function getInstance( $alias = "" ){
		return parent::getInstance( $alias );
	}
	
	
	/** (non-PHPdoc)
	 * @see ARMBaseModuleAbstract::getInstaceBy_config()
	 * @return ARMMailerModule
	 */
	public static function getInstaceBy_config( $_config , $alias = self::DEFAULT_GLOBAL_ALIAS ) {
		return parent::getInstaceBy_config($_config, $alias ); 
	}

	
	private $to = array() ;
	
	private $to_bcc = array();
	private $to_cc = array();
	
	private $content = "" ;
	private $subject = "";
	private $is_html = FALSE ;
	private $attachment = FALSE; // file sys path
	
	
	public function addTo( $email , $name=""){
		array_push( $this->to, array( $email , $name) );
	}
	
	
	public function addBCC( $email , $name = ""){
		
		
		array_push( $this->to_bcc, array( $email , $name) );
	}
	
	public function addCC( $email , $name=""){
		array_push( $this->to_cc, array( $email , $name) );
	}
	
	public function setContent( $content ,  $content_info = NULL ){
		$this->content = $content;
		if( $content_info != NULL && is_array($content_info)) {
	
			foreach( $content_info as $tplTag => $replacement ){
				$this->content = str_replace($tplTag, $replacement, $this->content);
			}
				
		}
	}
	
	
	/**
	 * 
	 * @return boolean
	 */
	public function send(){
		 
		if( sizeof($this->to) <= 0 )
			return "No recip";
		 
		$mail = new PHPMailer(); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch


		$mail->SMTPARMDebug = $this->_config->debug_mode;

		
		if( $this->_config->is_smtp ) {
			$mail->IsSMTP();
			$mail->Host = $this->_config->host ;
			$mail->Port = $this->_config->port ;
			$mail->SMTPAuth = $this->_config->smtp_auth ; 
			$mail->Username = $this->_config->user_name ;
			$mail->Password = $this->_config->password ; 
			if( $this->_config->smtp_secure_type ) {
				$mail->SMTPSecure = $this->_config->smtp_secure_type ;
			}
				
		}else{
			$mail->IsMail();
		}
		
		$mail->SetFrom( $this->_config->from, $this->_config->from_name );
		
		$mail->FromName = $this->_config->from_name;
		
		$mail->Subject = $this->subject;
		
		$mail->Subject = $this->subject;
		$mail->IsHTML($this->is_html);
		$mail->MsgHTML($this->content);
		//$mail->AltBody = $msgFinal;
		
		if( is_string( $this->attachment ) )
			$mail->AddAttachment($this->attachment);
		
		//Destinatarios
		foreach( $this->to as $index=>$data ){
			$mail->AddAddress($data[0] , $data[1] );
		}

		foreach( $this->to_cc as $index=>$data ){
			$mail->AddCC($data[0] , $data[1] );
		}
		
		
		foreach( $this->to_bcc as $index=>$data ){
			$mail->AddBCC($data[0] , $data[1] );
		}
		
		//envia email atravez do phpmailer
		$rt = @$mail->Send();
		//var_dump($rt);
		if($rt){
			return true;
		}else{
			return false;
		}
	}

	public function getSubject() {
		return $this->subject;
	}
	
	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}
	
	public function getIsHtml() {
		return $this->is_html;
	}
	
	public function setIsHtml($is_html) {
		$this->is_html = $is_html;
		return $this;
	}
	
	public function getAttachment() {
		return $this->attachment;
	}
	
	public function setAttachment($attachment) {
		$this->attachment = $attachment;
		return $this;
	}
	
}