<?php

/**
 * Search recursively for the first occurence of an folder a folder tree structure 
 * @author alanlucian
 *
 */
class ARMFolderFinder {
	
	/**
	 * @param string $startFolder
	 * @param array $arrayFolderTree
	 * @return boolean|string <boolean, string>
	 */
	public static function seach( $startFolder , array $arrayFolderTree  ){

			$filePath = FALSE ; 

			while ( !$filePath ){
				
				// merge all info to build a file path
				$dirPath  = $startFolder . "/" . implode( "/" , $arrayFolderTree  ) ;

				// test file existence
				$dirPath  = is_dir( $dirPath ) ? $dirPath : FALSE ;
				
				//check if the folder tree is empty and if the folder doesn't exists on $startFolder
				if( count( $arrayFolderTree )  == 0  && !$dirPath  )
					return false;
				
				//remove the last folder from the structure for a new folder path
				array_pop( $arrayFolderTree ) ;
				
			}
		
			return ARMDataHandler::removeDoubleBars( $dirPath  ) ;
		
	}
	
}