<?php

/**
 * z1Core PHP Framework class
 * GitHub: https://github.com/z1FFy/z1web
 *
 * Developer: Denis Kuschenko
 * Site: z1web.ru
 * Mail: ziffyweb@gmail.com
 *
 * 2016(c)
 */

class z1Core
{
	#defining variables
	public
		$config,
		$server,
		$title,
		$indexModuleName,
		$modulePath,
		$templateName, $templatePath,
		$contentName, $contentPath,
		$page, $action,
		$modActSep,
		$siteUrl, $domain, $requestUri,
		$customHead,$headStrArr,
		$data,
		$isUtil = false;


	/**
	 * Main function to run the application
	 *
	 * @param $cnf
	 */
	function run($cnf) {
		$this->config = $cnf;
		$this->server = (DEV_SERVER==TRUE) ? 'DEV' : 'PRODUCTION';
		$this->modActSep = $cnf['GENERAL']['module-actionSeparator']; // Separator between module and action names in url
		$this->domain =  $cnf[$this->server]['domain'];
		$this->siteUrl = $cnf[$this->server]['protocol'] . '://' . $cnf[$this->server]['domain']; // Setting site url
		$this->requestUri = $_SERVER['REQUEST_URI'];
		$this->templateName = $cnf[$this->server]['template']; // Default template name (main)
		$this->indexModuleName = $cnf[$this->server]['indexModule']; // Module which load on index page (index)

		$this->parseUri();
		$this->runModule();
		$this->render();
		//$this->connectToDB();
	}

	/**
	 * Parsing module name, action..
	 */
	function parseUri() {
		$module = $this->indexModuleName;
		$action = 'index';

		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		    if ($uri != '/' && $_SERVER['HTTP_HOST'].$uri != $this->domain.'/') {
				try {
					$uri = parse_url($uri, PHP_URL_PATH);
					$sep = $this->modActSep;

					$uriParts = explode('/', trim($uri, ' /'));
					$module = array_values($uriParts);

					if (count($uriParts)<1) $offset = null;
					else
						$offset = count($uriParts)-1;
					if ($offset>-1) {
						$actionExp = explode($sep, $uriParts[$offset]);
						if (count($actionExp) > 1) {
							$action = $actionExp[1];
							$module[count($module) - 1] = str_replace($sep . $action, '', $module[count($module) - 1]);
						}
						if('/'.$module[0].'/index.php' == $_SERVER['PHP_SELF'])
							array_splice($module,0,1);
					}

				 } catch (Exception $e) {
					dbg($e);
					$module = '404';
					$action = 'main';
				}
			}

		} else {
			$this->isUtil = true;
		}

		$this->module = $module;
		$this->action = $action;
	}

	/**
	 * Running needed module
	 */
	function runModule() {
		if (!$this->isUtil) {
			$mod = null;
			$module = $this->module;
			if (is_array($module) && !empty($module)){
				$lastUriPart = $module[count($module)-1];
				$middlePath = '';
				foreach ($module as $key => $uriPart) {
					if ($key != 0) {
						$middlePath .= '/modules/' . $uriPart;
					}
				}
				$filename = '/' . $lastUriPart . 'Module.php';
				$modulePath = 'modules/' . $module[0] . $middlePath;
				$moduleFilePath = $modulePath . $filename;
				$module = $lastUriPart;
			} else {
				$module = $this->indexModuleName;
				$filename = $module . 'Module.php';
				$modulePath = 'modules/' . $module . '/';
				$moduleFilePath = $modulePath . $filename;
			}

			if (!file_exists($moduleFilePath)) {
				$module = $this->indexModuleName;
				$filename = $module . 'Module.php';
				$modulePath = 'modules/' . $module . '/';
				$moduleFilePath = $modulePath . $filename;
			}

			$this->modulePath = $modulePath;

			require_once($moduleFilePath);

			$this->setHeadStr($this->headStrArr);
			$this->setTemplate($this->templateName);

			eval('$mod = new ' . $module . 'Module($this);');

		}
	}

	/**
	 * Append to customHead string values from array
	 *
	 * @param $arr
	 */
	function setHeadStr($arr){
		foreach ($arr as $str) {
			$this->customHead.= $str . '
			';
		}
	}

	/**
	 * Setting template path at name
	 *
	 * @param $templateName
	 */
	function setTemplate($templateName) {
		$this->templatePath = 'templates/' . $templateName . 'Template.php';
	}

	/**
	 * Rendering page view and template
	 */
	function render() {
		$customHead = $this->customHead;
		$data = $this->data;
		$title = $this->title;
		$siteUrl = $this->siteUrl;
		ob_start();
		if (empty($this->contentName)) {
			die;
		}

		if (file_exists($this->contentPath)) {
			require($this->contentPath);
		} else {
			if (!empty($this->view)) {
				header("HTTP/1.0 404 Not Found");
				require_once('templates/404.php');
				die;
			} else {
				dbg('View path dont exists');
			}
		}
		$content = ob_get_clean();

		if (file_exists($this->templatePath))
			require_once($this->templatePath);
		else
			require($this->contentPath);
	}

	function includeHead($str) {
		$this->headStrArr[]=$str;
	}

	/**
	 * Set title value in template
	 *
	 * @param $title
	 */
	function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Set data param
	 *
	 * @param $data
	 */
	function setData($data){
		$this->data = $data;
	}

	/**
	 * Setting content name and path
	 *
	 * @param $contentName
	 */
	function setView($contentName) {
		$this->contentName = $contentName;
		$this->contentPath = $this->modulePath . '/views/' . $contentName . 'View.php';
	}

	function connectToDB() {
		$db = new z1mySqlCore();
		$db->connect();
	}

}


/**
 * Class z1mySqlCore
 *
 * Work with database MySQL
 */
class z1mySqlCore
{
	private $link;

	public function __construct()
	{
	}

	/**
	 * Connect to the server
	 *
	 * @return bool
	 */
	public function connect()
	{
		/* Подключение к серверу MySQL */
		$this->link = mysqli_connect(
			DB_HOST,  /* Хост, к которому мы подключаемся */
			DB_USER,       /* Имя пользователя */
			DB_PASS,           /* Используемый пароль  */
			DB_DATABASE);     /* База данных для запросов по умолчанию */

		if (!$this->link) {
			echo 'Невозможно подключиться к базе данных. Код ошибки: ' . mysqli_connect_error();
			return false;
		} else {
			$this->link->set_charset('utf8');
			return true;
		}
	}

	/**
	 * Performance query and returning result
	 *
	 * @param $query
	 * @param bool $fetch
	 * @return array|bool|mysqli_result
	 */
	function query($query,$fetch = true)
	{
		if ($this->link) {
			$result = mysqli_query($this->link, $query);
			if ($fetch && $result){
				while($rows[] = mysqli_fetch_assoc($result));
				array_pop($rows);  // pop the last row off, which is an empty row
			} else{
				$rows = $result;
			}
			mysql_error();
		} else {
			$rows = false;
		}
		return $rows;
	}
}


register_shutdown_function(function () {
	$error = error_get_last();
	if ($error && ($error['type'] == E_ERROR || $error['type'] == E_PARSE || $error['type'] == E_COMPILE_ERROR)) {
		if (strpos($error['message'], 'Allowed memory size') === 0) { // если кончилась память
			ini_set('memory_limit', (intval(ini_get('memory_limit'))+64)."M"); // выделяем немножко, что бы доработать корректно
			dbg("PHP Fatal: not enough memory in ".$error['file'].":".$error['line']);
		} else {
			dbg("PHP Fatal: ".$error['message']." in ".$error['file'].":".$error['line']);
		}
		// ... завершаемся корректно ....
	}
});



/**
 * Class Module
 *
 * Using in their module controller by extends
 */
class Module extends z1Core {
	/**
	 * @var $page z1Core
	 */
	public $page,$db;

	function init($self)
	{
		$this->page = $self;
	}
}
