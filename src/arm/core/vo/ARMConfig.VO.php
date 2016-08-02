<?php

class ARMConfigVO extends ARMAutoParseAbstract{
	
	/**
	 * 
	 * @var boolean
	 */
	public $dev = FALSE ;
	/**
	 * se true ele tenta pegar a instancia do módulo de view por path viewResolverByPath passando o restfolder como path
	 * @var bool
	 */
	public $view_resolver_by_path = FALSE ;
	
	/**
	 * 
	 * @var string
	 */
	public $default_config_loader_driver 	= "ARMModuleJsonConfigLoader" ;
	/**
	 * se estiver preenchido, ele vai dar redirect para esse caminho
	 * @var string
	 */
	public $redirect_to;
	/**
	 * Class to resolve result of data, need to implements ARMHttpReturnIndentifierInterface
	 * @var string ARMHttpReturnIndentifierInterface class name
	 */
	public $http_return_indentifier_module 	= "ARMHttpReturnIntentifierModule" ;
	/**
	 * Setar no config um nome de classe que implemente o ARMRewriteRuleInterface 
	 * @var RewriteRuleInterface
	 */
	public $rewrite_handler = NULL ;
	
	/**
	 * 
	 * @var array key = type , value = class name with ARMViewResolverInterface interface implmements
	 */
	public $view_module_list = array( 
			"html" 	=> "ARMSimplePHPResult" ,
			"json" 	=> "ARMJsonViewModule" ,
			"js" 	=> "ARMJsViewModule" ,
			"xml" 	=> "ARMXmlViewModule" 
	);
	
	/**
	 * Caso não seja definido o tipo de retorno, por padrão vai adotar o tipo escrito aqui 
	 * @var string 
	 */
	public $default_request_return_type = "html";
	
	/**
	 * Retorna o tipo padrão de retorno caso não seja especificado um
	 * @return string
	 */
	public function getDefaultRequestReturnType(){
		return $this->default_request_return_type ;
	}
	
	/**
	 * Used primary by the ARMClassHunter to locale Classes
	 * @var array
	 */
	public $class_path_list ;
	
	/**
	 * 
	 * @var string
	 */
	public $temp_folder 	= "temp/";
	
	
	/**
	 * (pt-br) Nome da classe que implementa ARMRequestAccessControllInterface
	 * @var string name of class 
	 */
	public $request_access_controll ;
	
	public $default_controller = "RootController" ;
	
	
	/**
	 * Determines if the system will parse the request and use any kind of Controller
	 * @var boolean
	 */
	public $use_controller = TRUE ;
	
	/**
	 * Determines if the system will parse the request and use any kind of View
	 * @var boolean
	 */
	public $use_view = TRUE ;
	
	public $FOLDER_APPLICATION			= "";
	
	/**
	 * Por padrão é a pasta configs na raiz do projeto
	 * @var string 
	 */
	public $folder_modules_config = "configs/" ;
	
	/**
	 * (pt_br) Pasta para iniciar a busca de controllers
	 * (en) Folder to begin the search of an controllers
	 *
	 * @var string
	 */
	public $folder_request_controler;
	
	
	public $app_url	;
	public $root_url ;

	public $app_path = "";

	public $array_request_range_init = 0 ;
	public $array_request_range_max;
}