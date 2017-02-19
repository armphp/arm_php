<?php
/**
 * Classe para organizar um pool de ViewManagers
 * Na verdade é um pool de qualquer coisa, mas está sendo usado no ARM para o pool de nome de classes
 * que atendem a resolver a view 
 * 
 * @author renatomiawaki
 *
 */
class ARMViewManager extends ARMBasePoolManager {
	/**
	 *
	 * @param  ARMViewModuleInterface $item
	 * @return number (int) simbolizando o index do item adicionado no pull
	 */
	public static function add( $item , $alias = ""){
		if( ! $item ){
			throw new ErrorException("Item must need to be sent") ;
		}
		parent::add( $item, $alias );
	}
	/**
	 * @param string $alias
	 * @return ARMViewResolverInterface
	 */
	public static function getByAlias( $alias = ""){
		$item = parent::getByAlias( $alias );
		if(! $alias && ! $item ){
			//se quer pegar pelo alias default (sem nada) e não achou por esse alias, tenta pegar o primeiro setado
			$item = parent::getByIndex( 0 ) ;
		}
		return $item ;
	}
	/**
	 *
	 * @return ARMViewResolverInterface
	 */
	public static function getDefault(){
		return parent::getDefault();
	}
}