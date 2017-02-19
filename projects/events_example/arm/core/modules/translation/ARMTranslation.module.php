<?php

/*
 * @author 		: 	Alan Lucian Milanês Tormente ( alanlucian@gmail.com)
 * @date		: 	05/12/2011
 * @version		: 	0.1
 * @desc		: 	simply add MO files to use translations. Work on Windows (IIS/Apache) and Unix systems
 * 					@TODO: Refazer o Translation
 */

 
 
class ARMTranslationModule extends ARMBaseModuleAbstract {
 	
	private static $locale = '';
	private static $def    = 'default-';
	public static function setLocale($new_locale=''){
		
		if(!is_string($new_locale))
			return NULL;
			//$new_locale = ARMConfig::getDefaultInstance()->getDefaultLocale();
			
		self::$locale = $new_locale;
		self::loadMOFiles();
		self::$def = self::$locale;
	}
	
	public static function text($text, $domain = FALSE, $echo = FALSE ){
		// @TODO: Refazer
		if(!$domain || $domain == FALSE || $domain == ''){
			$domain =  "default";//self::$def;
		}
		
		$domain = $domain . '-' .  self::$locale;
		
		
		// var_dump($domain, $text);
		try{
			$rt = $text;
			//$rt = dgettext($domain, $text);
		}catch(Exception $error){
			var_dunp($error);
		}
		//exit();
	
		//$rt = $text;
		if($echo){
			echo $rt;
		}else{
			return $rt;
		}
		
	}
	
	private static function updateMOFiles(){
		
		$def_lang_folder = ARMConfig::getDefaultInstance()->getMoFilesFolder() . DIRECTORY_SEPARATOR. ARMConfig::getDefaultInstance()->getDefaultLocale();  
		
		$def_lang_category_folder = $def_lang_folder . DIRECTORY_SEPARATOR. ARMConfig::getDefaultInstance()->getTranslationCategoryFolderName();
		if(!is_dir($def_lang_folder))
			mkdir($def_lang_folder);
			
			
		if(!is_dir($def_lang_category_folder))
			mkdir($def_lang_category_folder);
		
		$domains = ARMConfig::getDefaultInstance()->getTranslationDomains();	
		if( $domains ){
			foreach( $domains as $domain ){
				$domain_file = $domain . "-" . self::$locale . ".mo";
				
				$domain_mo_path =  ARMConfig::LANGUAGE_FOLDER . DIRECTORY_SEPARATOR. $domain_file ;
				
				if(file_exists($domain_mo_path)){
					copy($domain_mo_path, $def_lang_category_folder . DIRECTORY_SEPARATOR. $domain_file);
				}
			}
		}
		
		
		if(self::$locale != ARMConfig::getDefaultInstance()->getDefaultLocaleFolderName() ){
			$lang_folder = ARMConfig::getDefaultInstance()->getMoFilesFolder() . DIRECTORY_SEPARATOR. self::$locale;
			$lang_category_folder = $lang_folder . DIRECTORY_SEPARATOR. ARMConfig::getDefaultInstance()->getTranslationCategoryFolderName() ;
			
			if(!is_dir($lang_folder))
				mkdir($lang_folder);
				
			
			if(!is_dir($lang_category_folder))
				mkdir($lang_category_folder);
			
			
			$domains = ARMConfig::getDefaultInstance()->getTranslationDomains();	
			foreach($domains as $domain){
				$domain_file = $domain . "-" . self::$locale . ".mo";
				
				$domain_mo_path =  ARMConfig::LANGUAGE_FOLDER . DIRECTORY_SEPARATOR. $domain_file ;
				
				if(file_exists($domain_mo_path)){
					copy($domain_mo_path, $lang_category_folder . DIRECTORY_SEPARATOR. $domain_file);
				}
			}
		}
	} 
	
	private static function loadMOFiles(){
		
		self::updateMOFiles();
		
		$lang_path = ARMConfig::getDefaultInstance()->getLanguageFolder();
		
		$domains = ARMConfig::getDefaultInstance()->getTranslationDomains();
		if( $domains ){
			foreach($domains as $domain){
				
				$domain = $domain . '-' . self::$locale;
				
				bindtextdomain( $domain, ARMConfig::MO_FILES_FOLDER );
				bind_textdomain_codeset( $domain, ARMConfig::TRANSLATION_ENCODE);
				
			}
		}
		//setlocale(Config::TRANSLATION_CATEGORY, self::$locale);
		if(!ARMConfig::getDefaultInstance()->useTranslation() )
			return;//para nao dar erro no mac
		
		// var_dump(self::$locale , $lang_path);
		//bindtextdomain( self::$def , $lang_path );
		//bind_textdomain_codeset( self::$def , ARMConfig::TRANSLATION_ENCODE);
		//textdomain(self::$def);
		
	}
	
	
	
	
 }
