<?php
/**
 *
 * Interface para caso queira criar uma classe própria para resover header de view para o módulo ARMSmartyView
 *
 * @author renatomiawaki
 *
 */
interface ARMB4ViewManagerInterface {
	/**
	 * recebe a configuração setada na ARMSmartView
	 * @param ARMSmartViewConfigVO $configVO
	 */
	function __construct( ARMSmartViewConfigVO $configVO ) ;
	/**
	 *
	 * Deve carregar o header e assets do html em questão conforme o pathFolder recebido em forma de array
	 *
	 * @param string $view_file
	 * @param array $arrayPathFolder
	 * @param object $data_controller_result
	 * @param object $view_controller_result
	 *
	 */
	function loadHtmlHeader( $view_file,  $arrayPathFolder , $BaseContentViewVO ) ;
}