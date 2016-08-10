<?php

class ContentMenuLeft{
	
	const MENU_NOVO_PROJETO = "project" ;
	const MENU_DATA_MAKER 	= "data_maker_manager" ;
	const MENU_STORED 		= "stored" ;
	
	
	/**
	 * 
	 * @var string
	 */
	public $appURL ;
	
	/**
	 * Nome do item selecionado (alias)
	 * @var string
	 */
	public $selected ;
}