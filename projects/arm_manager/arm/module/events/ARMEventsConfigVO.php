<?php
/**
 * @author Renato Miawaki
 * Date: 20/02/16
 * Time: 22:18
 */

class ARMEventsConfigVO extends ARMAutoParseAbstract{
	/**
	 * se true, o próprio módulo dispara eventos para caso alguém queira ouvir, tipo um log, ótimo para debug
	 * @var bool
	 */
	public $dispatchSelfEvents = false ;
	/**
	 * indica se pode adicionar eventos para o cron disparar no futuro
	 * @var bool
	 */
	public $permitScheduleCronEvent = true ;
	/**
	 * array de informações do listener do tipo arm module
	 * @var ARMListenerArmModuleInfoVO[]
	 */
	public $defaultListenersArmModule ;
	/**
	 * array de informações do listener do tipo string com nome do metodo a ser chamado
	 * @var ARMListenerMethodStringInfoVO[]
	 */
	public $defaultListenersStringMehodName ;
	/**
	 * array de informações do listener classe a ser instanciada ( envie o nome da classe em string )
	 * @var ARMListenerClassToInstanceInfoVO[]
	 */
	public $defaultListenersClassToInstance ;

}