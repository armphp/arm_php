<?php

/**
 * 
 * VO de configuração para todos os assets e views em comum de ambientes
 * @author Leal
 *
 */
class ARMCommonPHPResultConfigVO extends ARMAutoParseAbstract {
	/**
	 * 
	 * path da pasta onde ficam os arquivos php para front e common front
	 * 
	 * @var string
	 */
	public $view_folder = "front/view/" ;

	public $asset_path = "front/assets/";

	public $common_view_folder = "common_front/view/" ;

	public $common_asset_path = "common_front/assets/";
	
	
}