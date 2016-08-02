<?php

/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */

class RootController implements ARMApplicationInitInterface{
	
	public function __construct(){
		//
	}
	public function init(){

		return NULL ;
	}
	public static function applicationInit(){
		
	}
	public function testMail(){
		$mailer = ARMMailer::getInstaceByConfigVO( $vo );
		$mailer->setSubject("teste");
		$mailer->addTo("alanlucian+reciver@gmail.com");
		ARMDebug::li("EMail? >");
		ARMDebug::print_r( $mailer->send() );
	}
	public function testDatabase(){

		$DAO = UserDAO::getInstance() ;
		ARMDebug::print_r( $DAO ) ;
		$ReturnData = $DAO->selectAll() ;
		$ReturnData->fetchAll() ;
		ARMDebug::print_r( $ReturnData ) ;
		
	}
}
