<?php

class ARMHtmlFormMakerModule extends ARMBaseModuleAbstract{

	protected $DOMDocument;
	
	/**
	 * @return ARMHtmlFormMakerModule
	 */
	public static function getInstance( $alias = NULL ) {
		return parent::getInstance( $alias );
	}

	public function buildForm( ARMHtmlFormVO $formVO ) {
		if( !isset( $this->DOMDocument ) )
			$this->DOMDocument = new DOMDocument();
		

		$formEl = $this->DOMDocument->createElement( "form" );
		
		$formEl->setAttribute( "action" , $formVO->getAction() );

		$fields = $formVO->getFormDataSet() ;

		foreach( $fields as $dataSetItem ){
			$formEl->appendChild( $this->buildDataSetItem( $dataSetItem ) );
		}
		
		$this->DOMDocument->appendChild( $formEl ) ;
		
		echo $this->DOMDocument->saveHTML();
		
	} 
	
	protected function buildDataSetItem( ARMHtmlFormElementInterface $dataSetItem){
		return $dataSetItem->build( $this->DOMDocument );
		
	}
}