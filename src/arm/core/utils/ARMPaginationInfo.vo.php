<?php
/**
 * User: alanlucian
 * Date: 11/21/13
 * Time: 4:08 PM
 */

class ARMPaginationInfoVO {


	/**
	 * action with necessary data do add a page number
	 * eg: http://sinename.com/list/page.
	 * @var string
	 */
	public $action ;

	/**
	 * @var int
	 */
	public $current_page ;

	/**
	 * @var int
	 */
	public $results_per_page ;

	/**
	 * @var int
	 */
	public $total_results ;
}