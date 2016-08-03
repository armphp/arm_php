<?php
	/**
	* created by ARMDaoMaker ( automated system )
	* Please, change this file
	* don't change ARMBaseARMCronEventDAO class
	*
	* ARMCronEventDAO
	* @date 26/02/2016 04:02:34
	*/
	
class ARMCronEventDAO extends ARMBaseARMCronEventDAOAbstract {

	/**
	 * Seleciona as agendas que são menores que agora
	 * @param null $limit
	 * @param null $offset
	 * @return ARMReturnDataVO
	 * @throws ErrorException
	 * @throws Exception
	 */
	public function selectScheduleEvents( $limit = NULL , $offset = NULL ){
		$vo = ARMCronEventModelGateway::getInstance()->getVO() ;
		$vo->active = 1 ;
		$vo->date_to_show = ARMCronEventDAO::DATA_NOW ;
		$infoQuery = $this->getQueryFilteredByVO( $vo , null  , array( ARMCronEventDAO::FIELD_date_to_show => "<=" ) );
		//query basica no indice 0 na array
		$query 				= $infoQuery[0] ;
		$array_parameters	= $infoQuery[1] ;
		//$array_parameters[] = ARMCronEventDAO::DATA_NOW ;
		$query .=  $this->getQueryLimit(  $limit , $offset ) ;
		return $this->select($query, $array_parameters);
	}
}