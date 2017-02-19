<?php
/**
 * User: alanlucian
 * Date: 11/25/13
 * Time: 2:26 PM
 */

class ARMHtmlSimpleFormActionVO {

	/**
	 * type of the url action
	 * ex: controller to user:  ARMNavigation::getLinkToController()
	 * @var string
	 */
	public $type;

	/**
	 * controller name to make an action URL
	 * @var string
	 */
	public $controller;

	/**
	 * method on controller
	 * @var string
	 */
	public $method;

	/**
	 * An   "name"=>value object of the action
	 * @var object
	 */
	public $parameters;

}