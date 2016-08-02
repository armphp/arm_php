<?php


/**
 * renders all possibilities of an input tag as HTML
 * text | radio | checkbox | reset | button | submit | file 
 * @author alanlucian
 *
 */
class ARMInputHtmlRender extends ARMBaseSingletonAbstract{
	
	
	public function text( ARMHtmlFormElementInterface $inputVO ){
		
		 
		$html = <<<INPUT
<input type="text" name="input" value="valor" />
INPUT;
	}
	
	
}