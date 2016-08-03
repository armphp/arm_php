<?php




/**
 *
 * ARM = Amplified Resource Modulate
 *
 * @author Alan Lucian
 * @author Mauricio Amorim
 * @author Renato Seiji Miawaki
 *
 */

//include path sample for an diferent ARM core path
//set_include_path(get_include_path() . PATH_SEPARATOR . "../");

include_once "arm/core/http/ARMHttpRequestController.php";

//para mudar a pasta padrão do config, modifique aqui 
ARMConfig::$APP_CONFIG_FOLDER = "app_config/" ;

new ARMHttpRequestController() ;


