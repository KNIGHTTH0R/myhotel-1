<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	public function _initAutoload() {
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
			'module' => 'error',
			'controller' => 'error',
			'action' => 'error'
		)));
	}

	public function _initConfig() {
		$config = $this->getOptions();
		Zend_Registry::set('Config', $config);
	}

	public function _initLogWriter() {
		$Writer = new MyZend_Log_Writter(_LOG_PATH . '/log.txt', 'a', _LOG_FILE_SIZE);
		$Format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
		$Formatter = new Zend_Log_Formatter_Simple($Format);
		$Writer->setFormatter($Formatter);
		$Log = new Zend_Log($Writer);
		Zend_Registry::set('Log', $Log);
	}

	public function _initDb() {
		$dbOption = array();
		$dbOption['adapter'] = 'PDO_MYSQL';
		$dbOption['params']['host'] = _HOST;
		$dbOption['params']['username'] = _USER_HOST;
		$dbOption['params']['password'] = _PASS_HOST;
		$dbOption['params']['dbname'] = _DBNAME;

		$adapter = $dbOption['adapter'];
		$config = $dbOption['params'];

		$db = Zend_Db::factory($adapter, $config);
		$db->setFetchMode(Zend_Db::FETCH_ASSOC);
		$db->query("SET NAMES 'utf8'");
		Zend_Registry::set('connectDB', $db);

		Zend_Db_Table::setDefaultAdapter($db);

		return $db;
	}

	protected function _initNavigation() {
		$path = APPLICATION_PATH . '/../config/Breadcrumb.xml';
		$pages = new Zend_Config_Xml($path, 'nav');
		$resource = new Zend_Application_Resource_Navigation(array(
			'pages' => $pages,
		));
		$resource->setBootstrap($this);
		return $resource->init();
	}
	
	protected function _initCaptcha() {
		$captcha = new MyZend_Captcha();
		Zend_Registry::set('captcha', $captcha);
	}

}