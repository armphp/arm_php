<?php

/**
 * Interface para todo tipo de classe que pretende tratar dados, seja do banco, api ou outro tipo de tecnica
 * 
 * @author renatomiawaki
 *
 */
interface ARMDataInterface extends ARMSingletonInterface{
	
	/**
	 * faz o update ou insert conforme necessario
	 * @param object $VO
	 */
	function commitVO( &$VO );
	
	/**
	 *
	 * Caso envie o objeto com mais parametros, ele dá erro.
	 * Se precisar tratar isso, sobreescreva o metodo transformando o objeto recebido no objeto esperado
	 *
	 *
	 * @param object $VO (apenas com as colunas que a tabela contem, exatamente igual)
	 * @throws ErrorException
	 * @throws Exception
	 * @return ARMReturnDataVO
	 */
	function insertVO( &$VO );
	
	/**
	 *
	 * Caso envie o objeto com mais parametros, ele dá erro.
	 * Se precisar tratar isso, sobreescreva o metodo transformando o objeto recebido no objeto esperado
	 * Envie apenas a VO simples da tabela
	 *
	 * @param object $VO (apenas com as colunas que a tabela contem, exatamente igual)
	 * @throws ErrorException
	 * @throws Exception
	 * @return ARMReturnDataVO
	 */
	function updateVO( $VO );
	/**
	 * @param  int $id
	 * @return ARMReturnDataVO
	 */
	function active($id);
	/**
	 * @param  int $id
	 * @return ARMReturnDataVO
	 */
	function deactive($id, $field_name = "id");
	/**
	 * deleta mesmo
	 * @param  int $id
	 * @return ARMReturnDataVO
	 */
	function delete($id, $field_name = "id");
	/**
	 * deleta utilizando TODAS as propriedades não nulas da VO enviada como WHERE
	 *
	 * @param object $VO
	 * @param number $limit limite de registros afetados
	 * @return ARMReturnDataVO
	 */
	function deleteByVO($VO, $limit = NULL);
	/**
	 * @param $id number
	 * @return class ARMReturnDataVO
	 */
	function selectById($id);
	
	/**
	 *
	 * @param object $VO
	 * @param int $limit
	 * @param int $offset
	 * @throws ErrorException
	 * @throws Exception
	 * @return ARMReturnDataVO
	 */
	function selectByVO( $VO, $limit = NULL, $offset = NULL);
}