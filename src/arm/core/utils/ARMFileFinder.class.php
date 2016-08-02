<?php

/**
 * Search recursively for the first occurence of an inside a folder tree structure
 * @author alanlucian & renato miawaki
 *
 */
class ARMFileFinder {

	const DEBUG_VAR = "file_finder";

	/**
	 * Search recursively for the first occurence of $fileName inside a folder tree structure ( $arrayFolderTree ) ;
	 * @param unknown $startFolder
	 * @param unknown $arrayFolderTree
	 * @param unknown $fileName
	 * @return boolean|string <boolean, string>
	 */
	public static function seach( $startFolder , $arrayFolderTree ,  $fileName ){

			$filePath = FALSE ;

			while ( !$filePath ){

				// merge all info to build a file path
				$filePath  = $startFolder . "/" . implode( "/" , array_merge( $arrayFolderTree , array( $fileName ) ) ) ;

				// test file existence
				$filePath = file_exists($filePath ) ? $filePath  : FALSE ;

				//check if the folder tree is empty and if the file doesn't exists on $startFolder
				if( count( $arrayFolderTree )  == 0  && !$filePath )
					return false;

				//remove the last folder from the structure for a new file path
				array_pop( $arrayFolderTree ) ;

			}

			return ARMDataHandler::removeDoubleBars( $filePath ) ;

	}
	/**
	 *
	 * (pt-br) Procura um arquivo com o nome enviado no fileName dentro da array de pastas, ou algum arquivo com o nome de uma das pastas
	 *
	 * @param string $startFolder
	 * @param array $arrayFolderTree
	 * @param string $fileName
	 * @param string $extensionToFind
	 * @return boolean|mixed
	 */
	public static function searchByFolder( $startFolder, $arrayFolderTree, $fileName = "index.php" , $extensionToFind = "php", $camelCaseFileName = FALSE ){
		$filePath = FALSE ;
		$extensionToFind = preg_replace( "/^\./", "", $extensionToFind );
		while ( !$filePath ){


			ARMDebug::ifLi( "Searching ON - BASE DIR: "  . getcwd() , ARMFileFinder::DEBUG_VAR) ;
			// merge all info to build a file path
			if(!$fileName == NULL) {
				$filePath  			= ARMDataHandler::removeDoubleBars( $startFolder . "/" . implode( "/" , array_merge( $arrayFolderTree , array( $fileName ) ) )  );
			}
			$fileFolderPath  	= ARMDataHandler::removeDoubleBars(  $startFolder . "/" . implode( "/" , $arrayFolderTree ).".".$extensionToFind  );
			// test file existence



			if( $camelCaseFileName ){
				$fileName = basename ( $filePath, "." . $extensionToFind );
				$filePath = str_replace( $fileName, ARMDataHandler::urlFolderNameToClassName( $fileName ) , $filePath ) ;

				$fileName = basename ( $fileFolderPath, "." . $extensionToFind );
				$fileFolderPath = str_replace( $fileName, ARMDataHandler::urlFolderNameToClassName( $fileName ) , $fileFolderPath ) ;
			}

			ARMDebug::ifLi( "Searching by folder . filePath: "  . $filePath  , ARMFileFinder::DEBUG_VAR) ;
			$filePath = file_exists($filePath) ? $filePath  : FALSE ;

			ARMDebug::ifLi( "Searching by folder . fileFolderPath: "  . $fileFolderPath  , ARMFileFinder::DEBUG_VAR ) ;

			if( ! $filePath ){
				$filePath = file_exists( $fileFolderPath ) ? $fileFolderPath  : FALSE ;
			}
			//check if the folder tree is empty and if the file doesn't exists on $startFolder
			if( count( $arrayFolderTree )  == 0  && !$filePath )
				return false;

			//remove the last folder from the structure for a new file path
			array_pop( $arrayFolderTree ) ;

		}
		ARMDebug::ifLi( "Searching by folder . ENCONTRADO: "  . $filePath  , ARMFileFinder::DEBUG_VAR ) ;


		return ARMDataHandler::removeDoubleBars( $filePath ) ;
	}

	/**
	 * Searchs for $folder_name inside an $arrayFolderTree structure recursively
	 * @param string $startFolder
	 * @param string $folder_name
	 * @param string $arrayFolderTree
	 * @return array
	 */
	public static function searchFoldersRecursively( $startFolder, $folder_name , $arrayFolderTree  ) {

		$folder_list = array();
		$currentDir = ARMDataHandler::removeDoubleBars(  $startFolder  . implode( "/" , $arrayFolderTree ) );

		$folder_count = count( $arrayFolderTree ) ;
		for( $i = $folder_count  ; $i >=0  ; $i-- ){

			$currentDir = ARMDataHandler::removeDoubleBars( $currentDir . "/" . $folder_name ) ;

			if( is_dir( $currentDir ) ) {
				$folder_list[] = $currentDir ;
			}

			array_pop( $arrayFolderTree ) ;

			$currentDir = ARMDataHandler::removeDoubleBars(  $startFolder  . implode( "/" , $arrayFolderTree ) );
		}

		return $folder_list ;
	}
}