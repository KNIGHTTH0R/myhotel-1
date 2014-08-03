<?php

/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';

/** Zend_Log_Formatter_Simple */
require_once 'Zend/Log/Formatter/Simple.php';

class MyZend_Log_Writter extends Zend_Log_Writer_Stream {

	public function __construct($streamOrUrl, $mode = null, $maxFileSize) {
		///If the parameter is the path to file
		if (!is_resource($streamOrUrl)) {
			///If the file size is larger than maximum allowed file size
			if ($maxFileSize > 1 && $maxFileSize < filesize($streamOrUrl)) {
				$PathInfo = pathinfo($streamOrUrl);
				///Rename the file
				rename($streamOrUrl, $PathInfo['dirname'] . '/' . $PathInfo['filename'] . '-' . date('YmdHis') . '.txt');
			}
		}
		parent::__construct($streamOrUrl, $mode);
	}

}

?>
