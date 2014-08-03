<?php
class MyZend_Captcha extends Zend_Captcha_Image {

	protected $_expiration = 300;
	protected $_wordlen = 5;
	protected $_width = 100;
	protected $_height = 40;
	protected $_fsize = 20;
	protected $_font = 'public/FrontEnd/FontCaptcha/arial.ttf';
	protected $_imgDir = 'public/FrontEnd/cacheImages/';
	protected $_dotNoiseLevel = 5;
	
	public function getIterator($IdCaptcha) {
		$captchaSession = new Zend_Session_Namespace('Zend_Form_Captcha_' . $IdCaptcha);
		return $captchaSession->getIterator();
	}

}

?>
