<?php
/**
 * 
 * @author renatomiawaki e alanlucian
 *
 */
interface ARMEntityRelationshipInterface{
	/**
	 * deve carregar os dados da relação baseado no filtro
	 * 
	 * @param unknow $fk_parent_value é o valor do id no parent
	 * @return ARMReturnResultVO
	 */
	function load($fk_parent_value);
	/**
	 * Popupla a relacao com os dados enviados no array podendo ser bidimensional quando 1N ou NN
	 *
	 * @param array $array_data
	 * @param string $alias  //alias da relação na Entity onde ela se encontra.
	 */
	function fetchArray( $array_data, $alias = "" );
	/**
	 * 
	 * @param object or array of object $object_or_array_of
	 */
	function fetchObject( $object_or_array_of );
	/**
	 *
	 * @return Ambigous <multitype:object, multitype:array >
	 */
	function getData();
	
	/**
	 *
	 * @param unknow $fk_parent_value é o valor do id no parent
	 * @param boolean $validate
	 * @return ARMReturnResultVO
	 */
	function commit($fk_parent_value, $validate = FALSE);
	
	/**
	 * Gera um objeto com FormFieldInfoVO para cada campo da VO de relação
	 * @param string $alias
	 */
	function getFields( $alias = "" );
}