<?php
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

include APPLICATION_PATH . '/../config/config.php';

set_include_path(implode(PATH_SEPARATOR, array('library',get_include_path())));

require 'Zend/Application.php';

$environment = 'product';
$config = APPLICATION_PATH . '/../config/application.php';
$application = new Zend_Application($environment,$config);
$application->bootstrap()->run();