<?php

/**
 *
 * Classe que resolve a view procurando primeiro um PHP dedicado ao projeto, e depois um 'global'.
 * Utiliza o sistema de busca inversa
 *
 * @author Leal
 *
 */
class ARMCommonPHPResult extends ARMBaseModuleAbstract implements ARMViewResolverInterface
{


	/**
	 * exibe as mensagens do ARMCommonPHPResult com estilo
	 * @param $result
	 * @param array $arrayPathFolder é a array da requisição para busca lógica
	 * @throws ErrorException
	 */
	public function show($result, $arrayPathFolder)
	{
		$COMMON_FOLDER_VIEW = $this->getCommonFolderView();

		$COMMON_ASSET_PATH = ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->common_asset_path);

		$ASSET_PATH = ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->asset_path);

		$APP_URL = ARMNavigation::getAppUrl();

		$FOLDER_VIEW = $this->getFolderView();

		$CURRENT_CONTROLLER_URL = ARMNavigation::getCurrentControllerURL();

		$includeFront = null;

		try {
			$includeFront = $this->doShow($arrayPathFolder, $FOLDER_VIEW);
		} catch (ErrorException $e) {
			$FOLDER_VIEW = $this->getCommonFolderView();
			$ASSET_PATH = ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->common_asset_path);
			$includeFront = $this->doShow($arrayPathFolder, $FOLDER_VIEW);
		}
		ARMClassIncludeManager::loadByFile($includeFront, $includeFile = FALSE);

		include $includeFront;

	}

	/***
	 * @param $arrayPathFolder
	 * @param $FOLDER_VIEW
	 * @return bool|mixed
	 * @throws ErrorException
	 */
	public function doShow($arrayPathFolder, $FOLDER_VIEW)
	{
//        d($this->getConfig());
//        d([$arrayPathFolder, $FOLDER_VIEW]);
		$searchFileResult = ARMFileFinder::searchByFolder($FOLDER_VIEW, $arrayPathFolder);

		if (!$searchFileResult) {
			//página não encontrada...
			throw new ErrorException("View Not Found ! ");
			die;
		}
		return $searchFileResult;
	}


	/**
	 * @param string $relative_path
	 * @return string
	 */
	public function getFolderView($relative_path = "")
	{
		$viewFolder = ARMDataHandler::removeDoubleBars($this->getConfig()->view_folder . "/" . $relative_path);
		return $viewFolder;
    }

	/**
	 * @return ARMCommonPHPResultConfigVO
	 */
	protected function getConfig()
	{
		return $this->_config;
	}

	/**
	 *
	 * @return ARMCommonPHPResultConfigVO
	 */
	public function getParsedConfigData($configResult)
	{
		$config = new ARMCommonPHPResultConfigVO();
		$config->parseObject($configResult);

		return $config;
	}

	public static function getPageResult($project_folder)
	{
		echo file_get_contents(ARMConfig::getDefaultInstance()->getAppUrl() . $project_folder);
		return;


		//abaixo outra maneira otimizada de fazer
		$armConfigVO = ARMConfig::getDefaultInstance()->getCurrentConfigVO();
		$vo = new ARMConfigVO();
		$vo->parseObject($armConfigVO);
		new ARMHttpRequestController($vo, $project_folder);
	}

	public static function getFullUrlResult($full_project_folder)
	{

		$opts = array('http' => array('method' => "GET",
			'header' =>
				"Cookie: " . session_name() . "=" . session_id() . "\r\n"));

		$context = stream_context_create($opts);
		session_write_close();   // this is the key

		echo file_get_contents($full_project_folder, FALSE, $context);
	}

	/**
	 * @return string
	 */
	public function getAppUrl()
	{
		return ARMConfig::getDefaultInstance()->getAppUrl();
	}


	/**
	 * @return string
	 */
	public function getAssetPath()
	{
		return ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->asset_path);
	}


	/**
	 * @return string
	 */
	public function getCurrentControllerUrl()
	{
		return ARMNavigation::getCurrentControllerURL();
	}

	/**
	 * @return string
	 */
	public function getCommonAssetPath()
	{
		return ARMConfig::getDefaultInstance()->getRootUrl($this->getConfig()->common_asset_path);
	}


	public function getCommonFolderView($relative_path = "")
	{
		return ARMDataHandler::removeDoubleBars($this->getConfig()->common_view_folder . "/" . $relative_path);
	}

	public function view($path)
	{
		$paths = [
			$this->getFolderView($path),
			$this->getCommonFolderView($path),
		];

		foreach ($paths as $file) {
			if (file_exists($file)) {
				return $file;
			}
		}

		error_log('ARM: View not found: ' . $path . ' in folders: ' . implode(" | ", $paths));

		return false;
	}
}

if (!function_exists('view')) {
	function view($path)
	{
		$common = ARMCommonPHPResult::getInstance();
		$file = $common->view($path);
		if ($file !== false) {
			return $file;
		}
		return $path;
	}
}
