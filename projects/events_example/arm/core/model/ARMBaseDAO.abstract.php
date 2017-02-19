<?php

/**
 * @author        : Renato Miawaki
 * @data        : 1/12/2012
 * @version        : 1.1
 * @description    : contem o básico de toda DAO, só o que é possível implementar de modo automático
 */
abstract class ARMBaseDAOAbstract extends ARMMysqliModule implements ARMDataInterface
{

	protected $PRIMARY_KEY = "id";
	protected $TABLE_NAME = "";

	const FIELD_GROUP_LABEL_STRING = "STRING";
	const FIELD_GROUP_LABEL_NUMBER = "NUMBER";
	const FIELD_GROUP_LABEL_DATE = "DATE";

	protected function getTableFieldInfo()
	{

		$Result = $this->query("DESC " . $this->TABLE_NAME);
		$Result->fetchAll();
		if (mysql_errno()) {
			//ERRO
		} else {
			foreach ($Result->result as $row) {
				$attributes[] = $row;
			}
		}
		return $attributes;
	}

	/**
	 * Pega todos os campos da tabela, e normaliza os nomes filtrados
	 * indexado pelo tipo ( STRING / NUMBER / DATE )
	 * @return multitype:multitype:
	 */
	protected function getTableFieldsGroupType()
	{

		$fields = $this->getTableFieldInfo();

		$group_types_regexp = array(
			// CHAR, VARCHAR, BINARY, VARBINARY, TINYBLOB, BLOB, MEDIUMBLOB, LONGBLOB, TINYTEXT, TEXT, MEDIUMTEXT, LONGTEXT, ENUM, and SET
			self::FIELD_GROUP_LABEL_STRING => array(
				"/(char)/i", // CHAR, VARCHAR,
				"/(binary)/i", // BINARY, VARBINARY
				"/(blob)/i", // TINYBLOB, BLOB, MEDIUMBLOB, and LONGBLOB
				"/(text)/i", // TINYTEXT, TEXT, MEDIUMTEXT, and LONGTEXT.
				"/(enum|set)/i", // ENUM SET
			),
			// INTEGER, INT, SMALLINT, TINYINT, MEDIUMINT, BIGINT, DECIMAL, NUMERIC,  FLOAT, DOUBLE, BIT
			self::FIELD_GROUP_LABEL_NUMBER => array(
				"/(int\()/i", // INT, SMALLINT, TINYINT, MEDIUMINT, BIGINT,
				"/(integer|decimal|numeric|float|double|bit)/i", // INTEGER, DECIMAL, NUMERIC,  FLOAT, DOUBLE, BIT
			),
			//DATE, DATETIME, TIMESTAMP , YEAR, TIME
			self::FIELD_GROUP_LABEL_DATE => array(
				"/^(date)/i", // DATE, DATETIME
				"/(timestamp|year|time)/i", // TIMESTAMP , YEAR, TIME
			),
		);

		$types = array(
			self::FIELD_GROUP_LABEL_STRING => array(),
			self::FIELD_GROUP_LABEL_NUMBER => array(),
			self::FIELD_GROUP_LABEL_DATE => array(),
		);

		foreach ($fields as $field) {
			foreach ($group_types_regexp as $type => $regexpList) {
				foreach ($regexpList as $pattern) {
					if (preg_match($pattern, $field->Type)) {
						$types[$type][] = $field->Field;
					}
				}
			}
		}

		return $types;

	}

	/**
	 * @return array
	 */
	public function getTableTextFields()
	{
		$fields = $this->getTableFieldsGroupType();
		return $fields[self::FIELD_GROUP_LABEL_STRING];
	}

	/**
	 * @return array
	 */
	public function getTableDateFields()
	{
		$fields = $this->getTableFieldsGroupType();
		return $fields[self::FIELD_GROUP_LABEL_DATE];
	}

	/**
	 * @return array
	 */
	public function getTableNumFields()
	{
		$fields = $this->getTableFieldsGroupType();
		return $fields[self::FIELD_GROUP_LABEL_NUMBER];
	}

	/**
	 *
	 * @param $fields array of FIELD_GROUP_... OR item FIELD_GROUP_...
	 * @param $terms array of values to search ou value to search, relative of $fields
	 * @param $filterVO filter do add in filter, like selectByVO do not repeat field->value and $vo filter value
	 * @param null $limit
	 * @param null $offset
	 * @return ARMReturnDataVO
	 * @throws ErrorException
	 * @throws Exception
	 */
	public function search($fields, $terms, $filterVO, $limit = NULL, $offset = NULL)
	{
		$query_filter = $this->getQueryFilteredByVO($filterVO);

		if (!is_array($fields)) {
			switch ($fields) {
				case self::FIELD_GROUP_LABEL_DATE:
					$fields = $this->getTableDateFields();
					break;
				case self::FIELD_GROUP_LABEL_NUMBER:
					$fields = $this->getTableNumFields();
					break;
				default:
					$fields = $this->getTableTextFields();
					break;
			}
		}


		$where = implode(" LIKE (?) OR ", $fields) . " LIKE (?) ";

		$query = $query_filter[0] . " AND ( {$where} ) ";
		$query .= $this->getQueryLimit($limit, $offset);

		$params = $query_filter[1];

		$params = array_merge(array_fill(0, count($fields), $terms), $params);

		return $this->select($query, $params);
	}

	/**
	 *
	 * @param array $arr array de chave valor
	 * @return multitype:unknown |unknown
	 */
	protected function refValues($arr)
	{
		if (strnatcmp(phpversion(), '5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = array();
			foreach ($arr as $key => $value)
				$refs[$key] = &$arr[$key];
			return $refs;
		}
		return $arr;
	}

	/**
	 * Retorna a string dentro do padrão de statement para fazer o bind dos parametros
	 * @param unknown $value of variable
	 * @return string
	 */
	protected function getStatementTypeByValue($value)
	{
		switch (TRUE) {
			case is_string($value):
				return "s";
				break;
			case is_int($value):
				$stringTypes = "i";
				break;
			case is_numeric($value):
				$stringTypes = "d";
				break;
			default:
				return "s";
				break;
		}
		return $stringTypes;
	}

	public function commitVO(&$VO)
	{

		if (isset($this->TABLE_NAME)) {
			$primary = $this->PRIMARY_KEY;
			if (!property_exists($VO, $this->PRIMARY_KEY) || $VO->$primary == NULL) {
				return $this->insertVO($VO);
			} else {
				return $this->updateVO($VO);
			}
		} else {
			throw new Exception(" ARMBaseDAO . commitVO : precisa de protected \$TABLE_NAME definida ");
		}
	}

	public function getPrimaryKey()
	{
		return $this->PRIMARY_KEY;
	}

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
	public function insertVO(&$VO)
	{
		$ReturnDataVO = new ARMReturnDataVO();
		if (isset($this->TABLE_NAME)) {

			//agora pega as propriedades do objeto
			$arrayProperties = get_object_vars($VO);
			$arrayProperties = array_filter($arrayProperties, function ($value, $key) {
				return $value !== null;
			}, ARRAY_FILTER_USE_BOTH);

			$array_keys = array_keys($arrayProperties);
			$array_keys = array_filter($array_keys, 'ARMDataHandler::isNotNull');

			//substituindo o now padrão do sistema pela data atual
			foreach ($array_keys as $key) {
				if ($arrayProperties[$key] === ARMMysqliModule::DATA_NOW) {
					$arrayProperties[$key] = date("Y-m-d h:i:s");
				}

				if ($arrayProperties[$key] === ARMMysqliModule::DATA_NULL) {
					$arrayProperties[$key] = NULL;
				}
			}

			$str_vars = implode("`,`", $array_keys);
			$str_vars = "`" . $str_vars . "`";

			//error_log(json_encode($VO));
			//{"id":null,"date_in":"2017-01-26 03:46:00","user_id":5,"ref_id":5,"ref_alias":"Sr Governan\\u00e7a","action":"quick","action_label":"Success","data_resolver_class":"","data":"{\\"success\\":true,\\"result\\":{\\"id\\":5,\\"name\\":\\"Sr Governan\\\\u00e7a\\",\\"login\\":\\"governanca\\",\\"cpf\\":\\"11111111111\\",\\"user_type_id\\":3,\\"user_type_alias\\":\\"admin_cliente\\",\\"help_categories\\":null,\\"vaga_id\\":5,\\"vaga_nome\\":\\"Vaga do Sr Governan\\\\u00e7a-\\",\\"cargo_id\\":1,\\"cargo_nome\\":\\"Governan\\\\u00e7a\\",\\"empresa_id\\":1,\\"empresa_nome\\":\\"ITA\\\\u00da\\",\\"operacoes_id\\":\\"[{\\\\\\"id\\\\\\":2,\\\\\\"nome\\\\\\":\\\\\\"Contra\\\\\\"}]\\"},\\"array_messages\\":[]}"}

			//cria os simbolos de troca ? para valores de bind
			$slotsSimbol = implode(",", array_fill(0, count($array_keys), "?"));

			//query basica
			$query = "INSERT INTO `" . $this->TABLE_NAME . "` ($str_vars) VALUES ($slotsSimbol)";
			$link = ARMDBManager::getConn($this->_config->alias);


			$stmt = $link->prepare($query);
			if (!$stmt) {
				throw new ErrorException(" ARMBaseDAO > " . $link->error, $link->errno);
			}
			$stringTypes = "";
			$arrayStmtBind = array(&$stringTypes);
			foreach ($arrayProperties as $key => $value) {
				$stringTypes .= $this->getStatementTypeByValue($value);
				$arrayStmtBind[] = $value;
			}

			$returnOfBind = call_user_func_array(array($stmt, "bind_param"), $this->refValues($arrayStmtBind));
			if (!$returnOfBind) {
				throw new ErrorException(" ARMBaseDAO Binding parameters failed > <br />\n<br />\n" . $link->error, $link->errno);
			}
			try {
				$ReturnDataVO->result = $stmt->execute();
			} catch (Error $e) {
				throw new ErrorException(" ARMBaseDAO execute failed > \n\n" . $e->getMessage());
			}
			$ReturnDataVO->success = TRUE;
			if (!$ReturnDataVO->result) {
				$ReturnDataVO->code_return = $link->errno;
				$ReturnDataVO->error_message = $link->error;
				$ReturnDataVO->success = FALSE;
			}
			//insert_id?
			$primary = $this->PRIMARY_KEY;
			//caso o ID da primary key já tenha sido enviado, ele volta pra VO que veio
			$insert_id = (isset($VO->$primary) && $VO->$primary > 0) ? $VO->$primary : $link->insert_id;

			$stmt->close();
			$VO->$primary = $insert_id;

			$ReturnDataVO->setReturnId($insert_id);

			return $ReturnDataVO;
		} else {
			throw new Exception(" ARMBaseDAO . active : precisa de protected \$TABLE_NAME definida");
		}
		return $ReturnDataVO;
	}

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
	public function updateVO($VO)
	{
		$ReturnDataVO = new ARMReturnDataVO();
		$primary_key_colunm = ARMDataHandler::removeSpecialCharacters($this->PRIMARY_KEY);
		if (isset($this->TABLE_NAME) && property_exists($VO, $primary_key_colunm)) {
			//agora pega as propriedades do objeto
			$arrayProperties = get_object_vars($VO);
			//remove o id (primary key) da array
			unset($arrayProperties[$primary_key_colunm]);
			$arrayProperties = array_filter($arrayProperties, 'ARMDataHandler::isNotNull');
			$array_keys = array_keys($arrayProperties);

			foreach ($array_keys as $key) {
				if ($arrayProperties[$key] === ARMMysqliModule::DATA_NULL) {
					$arrayProperties[$key] = NULL;
				}
				if ($arrayProperties[$key] === ARMMysqliModule::DATA_NOW) {
					$arrayProperties[$key] = date("Y-m-d h:i:s");
				}
			}

			$str_vars = implode("` = ?,`", $array_keys);
			$str_vars = "`" . $str_vars . "` = ? ";

			//cria um monte de ?,?,?,? conforme os dados nas chaves
			$id = $VO->$primary_key_colunm * 1;
			//query basica
			$query = "UPDATE `" . $this->TABLE_NAME . "` SET $str_vars WHERE `$this->TABLE_NAME`.`$primary_key_colunm` = $id LIMIT 1";
			$link = ARMDBManager::getConn($this->_config->alias);
			$stmt = $link->prepare($query);

			if (!$stmt) {
				throw new ErrorException(" ARMBaseDAO > " . $link->error, $link->errno);
			}
			$stringTypes = "";
			$arrayStmtBind = array(&$stringTypes);
			foreach ($arrayProperties as $key => $value) {
				$stringTypes .= $this->getStatementTypeByValue($value);
				$arrayStmtBind[] = $value;
			}
			$returnOfBind = call_user_func_array(array($stmt, "bind_param"), $this->refValues($arrayStmtBind));
			if (!$returnOfBind) {
				throw new ErrorException(" ARMBaseDAO Binding parameters failed > <br />\n<br />\n" . $link->error, $link->errno);
			}
			$ReturnDataVO->result = $stmt->execute();
			$ReturnDataVO->success = TRUE;
			if (!$ReturnDataVO->result) {
				$ReturnDataVO->code_return = $link->errno;
				$ReturnDataVO->error_message = $link->error;
				$ReturnDataVO->success = FALSE;
				//throw new ErrorException(" ARMBaseDAO insert Execute failed > ".$link->error , $link->errno);
			}

			$stmt->close();
			$ReturnDataVO->setReturnId($id);

			return $ReturnDataVO;
		} else {
			throw new Exception(" ARMBaseDAO . active : precisa de protected \$TABLE_NAME definida");
		}
		return $ReturnDataVO;
	}

	/**
	 * @param  int $id
	 * @return ARMReturnDataVO
	 */
	public function active($id, $field_name = "id", $status = 1)
	{
		return $this->updateVO((object)array($field_name => $id, "active" => $status));
	}

	/**
	 * @param  int $id
	 * @return ARMReturnDataVO
	 */
	public function deactive($id, $field_name = "id", $status = 0)
	{
		return $this->updateVO((object)array($field_name => $id, "active" => $status));
	}

	/**
	 * deleta mesmo
	 * @param  int $id
	 * @return ARMReturnDataVO
	 */
	public function delete($id, $field_name = "id")
	{
		$query = "DELETE FROM " . $this->TABLE_NAME . " WHERE $field_name = '" . ARMDataHandler::forceInt($id) . "' LIMIT 1 ";
		$ReturnDataVO = parent::query($query);
		return $ReturnDataVO;
	}

	/**
	 * deleta utilizando TODAS as propriedades não nulas da VO enviada como WHERE
	 *
	 * @param object $VO
	 * @param number $limit limite de registros afetados
	 * @return ARMReturnDataVO
	 */
	public function deleteByVO($VO, $limit = NULL)
	{
		$ReturnData = $this->selectByVO($VO, $limit);

		if ($ReturnData->hasResult()) {
			$ReturnData->success = TRUE;
			foreach ($ReturnData->result as $item) {
				$this->delete($item->{$this->getPrimaryKey()}, $this->getPrimaryKey());
			}

		}
		return $ReturnData;
	}

	/**
	 * @param $id number
	 * @return ARMReturnDataVO
	 */
	public function selectById($id)
	{
		return $this->selectByField(ARMDataHandler::forceInt($id), $this->PRIMARY_KEY);
	}

	/**
	 * @param $value *
	 * @param $key_colunm string / coluna usada para filtro
	 * @return ARMReturnDataVO
	 */
	public function selectByField($value, $key_colunm, $limit = NULL)
	{
		$VO = new stdClass();
		$VO->$key_colunm = $value;
		return $this->selectByVO($VO, $limit);
	}

	/**
	 *
	 * @param string $query
	 * @param array $array_parameters
	 *
	 * @return ARMReturnDataVO
	 */
	public function select($query, $array_parameters = null)
	{
		//contar quantidade de ? enviadaos
		//comparar com count da array de parametros
		//inicia o retorno
		$ReturnDataVO = new ARMReturnDataVO();

		if (isset($this->TABLE_NAME)) {
			$link = ARMDBManager::getConn($this->_config->alias);
			if (!$link) {
				throw new ErrorException("Erro ao acessar banco de dados");
			}
			$stmt = $link->prepare($query);

			if (!$stmt) {
				throw new ErrorException(" ARMBaseDAO > " . $link->error . "  :: $query ::" . print_r($array_parameters, true), $link->errno);
			}
			if ($array_parameters && count($array_parameters) > 0) {
				//===== agora dar bind dos parametros
				$stringTypes = "";
				$arrayStmtBind = array(&$stringTypes);
				foreach ($array_parameters as $value) {
					$stringTypes .= $this->getStatementTypeByValue($value);
					$arrayStmtBind[] = $value;
				}
				$returnOfBind = call_user_func_array(array($stmt, "bind_param"), $this->refValues($arrayStmtBind));
				if (!$returnOfBind) {
					//@TODO: 8 - colocar o erro na ARMReturnDataVO e não criar uma excessao
					throw new ErrorException(" ARMBaseDAO Binding parameters failed > <br />\n<br />\n" . $link->error, $link->errno);
				}
			}
			//executa a query
			try {
				$resultOfExecute = $stmt->execute();
				$stmt->store_result();
			} catch (Exception $e) {
				throw new ErrorException(" ARMBaseDAO Binding parameters failed > \n\n" . $e->getMessage(), $e->getCode());
			}

			//precisa saber o nome dos fields retornados e criar uma array para dar o bind passando parametros
			$resultMetaData = $stmt->result_metadata();

			if (!$resultMetaData) {
				//@TODO: 8 - em vez de dar excessao, nesse caso, retornar como erro na ARMReturnDataVO com o código do erro e etc
				throw new ErrorException(" ARMBaseDAO mysqli_stmt_result_metadata failed > \n\n" . $resultMetaData);
			}
			$stmtRow = array(); //this will be a result row returned from mysqli_stmt_fetch($stmt)
// 				$rowReferences = array();  //this will reference $stmtRow and be passed to mysqli_bind_results
			while ($field = mysqli_fetch_field($resultMetaData)) {
// 					$rowReferences[] = $field->name;
				$stmtRow[$field->name] = NULL;
			}
			mysqli_free_result($resultMetaData);

			$tmpStmt = array();
			foreach ($stmtRow as $key => $value) {
				$tmpStmt[$key] = &$stmtRow[$key];
			}
			$returnOfBind = call_user_func_array(array(
				$stmt,
				"bind_result"
			),
				$tmpStmt);

			//inicia a result para colocar o conteudo
			$ReturnDataVO->result = array();
			while ($result = $stmt->fetch()) {
				$ob = new stdClass();
				foreach ($stmtRow as $k => $v) {
					$ob->$k = $v;
				}
				$ReturnDataVO->result[] = $ob;// (object) $row;
			}
			//for debug
			$ReturnDataVO->query = $query;
			/**
			 * pegando o resultado total de um select
			 * */
			$resultTotal = $this->query("SELECT FOUND_ROWS() as total;");
			$resultTotal->fetchAll();
			$ReturnDataVO->count_total = ARMDataIntHandler::forceInt($resultTotal->result[0]->total);
			$stmt->close();
			$ReturnDataVO->success = TRUE;
			return $ReturnDataVO;
		} else {
			throw new Exception(" ARMBaseDAO . selectByVO :  \$TABLE_NAME definida");
		}
		return $ReturnDataVO;
	}

	/**
	 * Para retornar sem filtros, apenas limites
	 * @param number $limit
	 * @param number $offset
	 * @return ARMReturnDataVO
	 */
	public function selectAll($limit = NULL, $offset = NULL)
	{
		$limits = "";
		if ($limit || $offset) {
			$limit = ARMDataHandler::forceInt($limit);
			$offset = ARMDataHandler::forceInt($offset);
			if (!$offset) {
				$offset = 0;
			}
			if (!$limit) {
				$limit = 100;
			}
			$limits = " LIMIT $offset , $limit ";
		}
		return $this->select("SELECT SQL_CALC_FOUND_ROWS * FROM " . $this->TABLE_NAME . " " . $limits);
	}

	/**
	 * @return
	 */
	public function getAllByEmpresa()
	{
		dd('');
	}

	public function selectRandom($limit = NULL)
	{
		$limits = "";
		if ($limit) {
			$limit = ARMDataHandler::forceInt($limit);
			if (!$limit) {
				$limit = 100;
			}
			$limits = " LIMIT $limit ";
		}
		return $this->select("SELECT SQL_CALC_FOUND_ROWS * FROM " . $this->TABLE_NAME . "  ORDER BY RAND() " . $limits);
	}

	protected function getQueryLimit($limit = NULL, $offset = NULL)
	{
		if ($limit !== NULL) {
			$limit = ARMDataHandler::forceInt($limit);
			$offset = ($offset === NULL) ? 0 : ARMDataHandler::forceInt($offset);
			return " LIMIT $offset, $limit";
		}
		return "";
	}

	/**
	 *
	 * @param object $VO
	 * @param int $limit
	 * @param int $offset
	 * @throws ErrorException
	 * @throws Exception
	 * @return ARMReturnDataVO
	 */
	public function selectByVO($VO, $limit = NULL, $offset = NULL)
	{
		$infoQuery = $this->getQueryFilteredByVO($VO);
		//query basica no indice 0 na array
		$query = $infoQuery[0];
		$array_parameters = $infoQuery[1];
		$query .= $this->getQueryLimit($limit, $offset);
		return $this->select($query, $array_parameters);
	}

	/**
	 *
	 * @param unknown $VO
	 * @return array [0]=>query, [1]=>$array_parameters
	 */
	protected function getQueryFilteredByVO($VO)
	{
		$result = $this->getFilterPropertiesByVO($VO);
		$array_parameters = $result[1];

		$str_vars = $result[0];
		//query basica
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . $this->TABLE_NAME . "` WHERE  $str_vars ";
		return array($query, $array_parameters);
	}

	/**
	 * Pega o filtro e a parte da query com os campos do filtro
	 * no array de indice 0 vem a string
	 * no segundo os parametros
	 *
	 * @param $VO
	 * @return array
	 */
	protected function getFilterPropertiesByVO($VO)
	{
		$array_parameters = array();
		$str_vars = 1;
		$null_vars = "";
		$table = $this->TABLE_NAME;
		if ($VO != NULL && is_object($VO)) {
			$arrayProperties = get_object_vars($VO);
			//remove os attributos que tiverem valor null
			foreach ($arrayProperties as $key => $value) {
				if ($value === NULL) {
					unset($arrayProperties[$key]);
				} else if ($value === ARMMysqliModule::DATA_NULL) {
					$null_vars .= " AND `$table`.$key IS NULL ";
					unset($arrayProperties[$key]);
				} else if ($value === ARMMysqliModule::DATA_NOW) {
					$array_parameters[] = date("Y-m-d h:i:s");
				} else {
					$array_parameters[] = $value;
				}


			}
			//@TODO: 7 - quando vier o self::NULL trocar por IS NULL
			if (count($array_parameters) > 0) {
				$array_keys = array_keys($arrayProperties);
				$str_vars = implode("` = ? AND `$table`.`", $array_keys);
				$str_vars = " `$table`.`" . $str_vars . "` = ? ";
			}
		}
		$str_vars .= $null_vars;
		return [$str_vars, $array_parameters];
	}

	/**
	 * Retorna ordenando pelo que foi passado em order_by_column por padrão ASC
	 * @param object $VO
	 * @param int $limit
	 * @param int $offset
	 * @throws ErrorException
	 * @throws Exception
	 * @return ARMReturnDataVO
	 */
	public function selectByVOWithOrderBy($VO, $order_by_column, $asc = TRUE, $limit = NULL, $offset = NULL)
	{
		$infoQuery = $this->getQueryFilteredByVO($VO);
		//query basica no indice 0 na array
		$query = $infoQuery[0];
		$array_parameters = $infoQuery[1];

		if (is_array($order_by_column)) {
			$order_by_column = "`" . implode("`,`", $order_by_column) . "`";
		} else {
			$order_by_column = "`" . $order_by_column . "`";
		}

		$query .= " ORDER BY $order_by_column";
		$query .= ($asc) ? " ASC " : " DESC ";
		$query .= $this->getQueryLimit($limit, $offset);
		return $this->select($query, $array_parameters);
	}

}
