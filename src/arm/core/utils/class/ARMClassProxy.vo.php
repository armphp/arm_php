<?php
/**
 *
 * User: alanlucian
 * Date: 4/8/14
 * Time: 10:49 AM
 */

class ARMClassProxyVO {

    /**
     * @var mixed className or class instance
     */
    public $class ;

    /**
     * @var string
     */
    public $method ;

    /**
     * Array with all request params
     * @var array
     */
    public $params = array();

}