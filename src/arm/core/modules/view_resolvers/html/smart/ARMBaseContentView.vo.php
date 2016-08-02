<?php
/**
 * Class With all information that an SmartView may need
 * @author alanlucian
 *
 */
class ARMBaseContentViewVO {

	/**
	 * Controller result
	 * @var ARMHttpRequestDataVO
	 */
	public $data ;


	/**
	 * return from an valid ARMViewController
	 * @var mixed
	 */
	public $view_data ;

	/**
	 * Data extracted from the first  SmartCommonData  found using the file reverse recursive search by request folders
	 * @var mixed
	 */
	public $common_view_data;

	public $global_data;

	public $asset_path ;

	public $folder_view ;

	public $app_url ;

	public $current_controller_url ;
}