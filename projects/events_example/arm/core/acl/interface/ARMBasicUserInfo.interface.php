<?php
/**
 * interface básica para objetos de controle de acesso a usuarios logados
 * @author renatomiawaki
 *
 */
interface ARMBasicUserInfoInterface {
	static function getId() ;
	static function getTypeId() ;
	static function getTypeAlias() ;
	static function getName() ;
	static function getToken() ;
	static function getActiveTime() ;
	static function getActive() ;
	static function keepAlive() ;
}