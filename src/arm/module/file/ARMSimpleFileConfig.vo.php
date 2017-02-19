<?php
/**
 * 
 * User: renatomiawaki
 * Date: 1/7/14
 *
 * Utiliza a ARMGenericDAO para acessodos dados
 * 
 */

class ARMSimpleFileConfigVO {
	public $tableName               = "arm_files" ;
	public $acceptedFileTypes       = "*" ;
	/**
	 * em megas, zero para infinito
	 * @var number
	 */
	public $maxUploadSizeInMb       = 0 ;
	public $folderToSaveFiles       = "arm_files/" ;
}