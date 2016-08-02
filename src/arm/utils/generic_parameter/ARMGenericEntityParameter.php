<?php
/**
 * User: alanlucian
 * Date: 3/18/14
 * Time: 10:30 AM
 */

class ARMGenericEntityParameter {


	/**
	 * @var ARMBaseEntityAbstract
	 */
	protected $base_entity = NULL;

	/**
	 * data object
	 * @var object
	 */
	protected $data = NULL;

	/**
	 * @var string|int
	 */
	protected $ref_name = NULL ;


	/**
	 * @var string|int
	 */

	protected $ref_id = NULL;


	CONST PARAMETER_NAME = "ENTITY_PARAMETER" ;


	/**
	 * @var array
	 */
	protected $data_to_add = array();

	/**
	 * @var array
	 */
	protected $data_to_set = array();

	/**
	 *
	 *
	 *  Recebe uma entity e usa os dados dela pra gerar
	 * @param $baseEntity ARMBaseEntityAbstract
	 */
	public function __construct( $baseEntity ){

		if( !ARMClassHandler::classExtends( $baseEntity, "ARMBaseEntityAbstract" )){
			throw new ErrorException(  " BaseEntity must be an instance of ARMBaseEntityAbstract" ) ;
		}



		$this->base_entity = $baseEntity  ;

		$this->ref_name = get_class( $baseEntity ) ;

		$this->loadData();

		//		ARMDebug::dump(ARMGenericParameterModule::getInstance()->getParameter( $ref_name  , $ref_id  , "index") );

	}

	/**
	 *  Carrega os dados dessa entidade e cacheia no objeto p/ acessar assim q precisar ;D
	 * se a entidade estiver vivaa
	 */
	protected function loadData(){


		$this->ref_id = $this->base_entity->getVO()->id ;

		if( ! is_null($this->ref_id)){

//			ARMDebug::dump($this->ref_name, $this->ref_id , $this::PARAMETER_NAME  );
			$dbData = ARMGenericParameterModule::getInstance()->getParameter( $this->ref_name, $this->ref_id , $this::PARAMETER_NAME  );




			if( $dbData->hasResult() ){
//				ARMDebug::dump($dbData);
				$dbData  = json_decode( $dbData->result[0]->data ) ;

//				die;

				$this->mergeEntityDataWithDbData( $dbData );
			}

		}



	}

	/**
	 * coloca os dados de dbData dentro do objeto da entity
	 * se tiver setado um dado que tem
	 * @param $dbData
	 */
	protected function mergeEntityDataWithDbData( $dbData ){
//		tem que resolver quem vai e quem fica! hahahah
		foreach( $dbData as $key=>$value ){
			if( in_array( $key, $this->data_to_set ) ){
				// se tá no set é pq é pra persistir o dado da mémória e ignorar o banco
				continue;
			}
			$this->addParam( $key, $value) ;
		}
	}

	public function commit( $validate = FALSE ){

		$ReturnResultVO = $this->base_entity->commit( $validate );

		if( $ReturnResultVO->success ){
			$ReturnResultVO = $this->commitParameters();
		}

		return $ReturnResultVO;
	}

	protected function commitParameters(){
		$result = ARMGenericParameterModule::getInstance()->setParameter( $this->ref_name, $this->ref_id, json_encode($this->data), $this::PARAMETER_NAME );
		return $result;
	}


	public function setParam( $name, $value ){
		$this->data->{$name} = $value ;

		$this->data_to_set[] = $name ;

		return $this->data->{$name}     ;
	}

	public function addParam( $name, $value ){
		// se nao existe cria
		if(!isset($this->data->{$name}) ){
			$this->data->{$name} =  $value  ;
			return $this->data->{$name} ;
		}


		//se for um array
		if( !is_array( $this->data->{$name} ) ){

			$old_data = $this->data->{$name} ;
//			ARMDebug::dump($old_data);
			$this->data->{$name} = array();
			$this->data->{$name}[] = $old_data ;
		}

		$this->data->{$name}[] = $value ;

		$this->data_to_add[] = $name ;


		return $this->data->{$name} ;
	}


	public function fetchArray( $array ){
		return $this->base_entity->fetchArray( $array );
	}

	public function setId( $id ){

		$this->base_entity->setId( $id );

		$this->loadData();
	}

	public function toStdClass(){
		$stdObj = $this->base_entity->toStdClass();

		$stdObj->generic_parameters = $this->data;
		return $stdObj;
	}


	public function removeParam( $name ){
		if(isset( $this->data->{$name} ) ){
			unset( $this->data->{$name} ) ;
		}
	}

	/**
	 * @param $name
	 */
	public function getParam( $name ){

		if(isset( $this->data->{$name} ) ){
			return $this->data->{$name} ;
		}

		return NULL;
	}


	/**
	 * @return ARMBaseEntityAbstract
	 */
	public function getBaseEntity(){
		return $this->base_entity;
	}

}