<?php

/**
 *
 * VO de configuração ximples para
 *
 * @author renato miawaki
 *
 */
class ARMSmartViewConfigVO extends ARMAutoParseAbstract {

	/**
	 * ViewController DefaultName
	 * @var string
	 */
	public $defaultViewController = "DefaultViewController" ;

	/**
	 * Class to respond as an B4ViewManager
	 * @see ARMB4ViewManagerInterface
	 * @var string
	 */
	public $B4ViewMannager = "ARMB4ViewManager" ;

	/**
	 * Packege folder for module data and folders;
	 * @var string
	 */
	public $package_folder = "./" ;

	/**
	 *
	 * path da pasta onde ficam os arquivos php para view
	 *
	 * @var string
	 */
	public $view_folder = "view/frontend/" ;

	/**
	 * Path where assets like CSS and JS can be found
	 * @var string
	 */
	public $assets_folder = "view/frontend/assets/" ;

	/**
	 * Path where B4View classes will be found
	 * @var string
	 */
	public $b4view_folder = "view/b4view/";

	/**
	 * Path where B4View classes will be found
	 * @var string
	 */
	public $view_controller_folder = "view/controller/";

	/**
	 * Path where a SmartCommonData classes to be found
	 * @var string
	 */
	public $smart_commmon_data_folder = "view/common_data/" ;


	/**
	 * Folder name for assets to be included on recustively request search
	 * @var string
	 */
	public $asset_auto_load_folder = "auto_load";

	/**
	 * javascript folder name
	 * @var string
	 */
	public $js_folder_name = "js" ;

	/**
	 * JAvascript file extension
	 * @var string
	 */
	public $js_file_extension = "js";

	/**
	 * CSS folder name
	 * @var string
	 */
	public $css_folder_name = "css" ;

	/**
	 * CSS file extension
	 * @var string
	 */
	public $css_file_extension = "css";


	/**
	 * config file for an asset folder to order when needed
	 * not in use yet
	 * @var string
	 */
	public $asset_settings_file_name = "settings.txt";

	/**
	 * @TODO: fazer rolar
	 * @var unknown
	 */
	public $view_vars;

	public function getViewFolder() {
		return  $this->package_folder . $this->view_folder;
	}

	public function getAssetsFolder() {
		return $this->package_folder . $this->assets_folder;
	}

	public function getB4viewFolder() {
		return $this->package_folder . $this->b4view_folder;
	}

	public function getViewControllerFolder() {
		return $this->package_folder . $this->view_controller_folder;
	}

	public function getSmartCommmonDataFolder() {
		return $this->package_folder . $this->smart_commmon_data_folder;
	}


}