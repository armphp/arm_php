<?php
/**
 * Renato Miawaki
 * User: renato
 * Date: 08/08/16
 * Time: 14:56
 */

class Exemplo {

	public function doSomething(){
		//its done

		//ADDING LOG
		$log = new ARMLogInfoVO() ;
		$log->action = "doSomething" ;
		$log->action_label = __CLASS__ . "::" . __METHOD__ ;
		$log->ref_alias = "product";
		$log->ref_id = 2 ;
		$log->data = $_GET;
		$log->user_id = 4 ;//opcional
		ARMLogModule::getInstance("exemplo")->addLog( $log ) ;
		dd( "done" ) ;
		///log está adicionado
	}
	public function logs(){

		//READING A LOG
		$logFilterVO = new ARMLogFilterVO() ;
		//$logFilterVO->ref_alias = "product";
		$logFilterVO->action = "doSomething";

		$resultData = ARMLogModule::getInstance("exemplo")->getLog( $logFilterVO );
		if( $resultData->hasResult() ){
			//if here, there has result

			dd( $resultData->result ) ;

		}
		dd("done ;)") ;
		die ;
	}
}