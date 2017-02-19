<?php

/**
 * This class has the necessary data to build a complete form
 * @author alanlucian
 *
 */
class ARMHtmlFormVO {
	
	/**
	 * tha URL to the form be sent
	 * @var string
	 */
	protected $action = "#" ;
	
	
	public function getAction() {
		return $this->action;
	}
	
	public function setAction( $action) {
		$this->action = $action;
		return $this;
	}
	
########################################################################################################################################################

	/**
	 * GET or POST if not set the user agent should render the response as GET 
	 * @var string
	 */
	protected $method = "post" ;
	
	
	public function getMethod() {
		return $this->method;
	}
	
	public function setMethod( $method) {
		$this->method = $method;
		return $this;
	}
	
	
########################################################################################################################################################	
	
	/**
	 * Contains an list of all elements of the form
	 * @var ARMHtmlFormElementInterface[]
	 */
	protected $form_data_set;
	
	public function addDataSet( ARMHtmlFormElementInterface $element ){
		if( isset( $this->form_data_set ) ){
			$this->form_data_set = array() ;
		}
		$this->form_data_set[ $element->getId() ] = $element ;
	}
	
	public function getDataSetById( $id ){
		if( isset( $this->form_data_set ) && isset( $this->form_data_set[ $id ] ) ){
			return $this->form_data_set[ $id ] ;
		}
		return NULL;
	}

	public function getFormDataSet() {
		return $this->form_data_set ;
	}
	

	
	
########################################################################################################################################################
	
}