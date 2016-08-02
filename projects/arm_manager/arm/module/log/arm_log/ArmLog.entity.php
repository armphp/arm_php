<?php
	/**
	* created by ARMEntityMaker ( automated system )
	* Please, change this file
	* don't change ARMBaseArmLogEntity class
	*
	* ArmLogEntity
	* @date 11/12/2013 07:12:18
	*/

class ArmLogEntity extends ARMBaseArmLogEntityAbstract {
	//put your changes and rewrited methods here
	//Good developers always comment theirs code


	public function getUser(){
		$companyMemberDAO = GPSDataCompanyMemberModelGateway::getInstance()->getDAO();
		$companyMemberVO = GPSDataCompanyMemberModelGateway::getInstance()->getVO();
		$companyMemberVO->user_id = $this->VO->user_id;

		$returnData = $companyMemberDAO->selectByVO(  $companyMemberVO );


		if( !$returnData->hasResult() )
			return $companyMemberVO;

		$returnData->fetchAllEntityToStd(GPSDataCompanyMemberModelGateway::getInstance());

		return $returnData->result[0];
	}
}