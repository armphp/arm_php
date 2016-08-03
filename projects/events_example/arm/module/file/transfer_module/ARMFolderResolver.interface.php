<?php
/**
 * User: alanlucian
 * Date: 4/9/14
 * Time: 2:20 PM
 */

interface ARMFolderResolverInterface {

    /**
     *
     * Generate a sugested name for the specific param
     * @param $param
     * @return string
     */
    function getFolderFor( $ref ) ;

    function updateTo( $ref, $folderName ) ;


}