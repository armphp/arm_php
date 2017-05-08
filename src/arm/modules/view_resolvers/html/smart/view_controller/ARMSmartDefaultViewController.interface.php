<?php
/**
 *
 * Interface para todas as controllers dentro de ARMSmartyView > Controller
 *
 * @author Alan Lucian
 *
 */
interface ARMSmartDefaultViewController extends ARMSmartViewControllerInterface {

	/**
	 *
	 * Metodo que será chamado sempre para todas as views
	 * Será colocado no ARMBaseContenteViewVO->global_data ;
	 */
	static function getGlobalData() ;

}