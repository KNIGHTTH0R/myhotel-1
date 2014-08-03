<?php

class Hpartner_RequestController extends Zend_Controller_Action {

	protected $SessionPage;
	protected $Captcha;

	public function init() {
		parent::init();
		//Session page
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
		//Captcha
		$this->Captcha = Zend_Registry::get('captcha');
		//Set layout
		MyZend_Function::setLayout(_HPARTNER_PATH, 'login');
	}

	public function refreshCaptchaAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$params = MyZend_Function::filterInputPartner($params, 'array');

		$CaptchaGenerate = $this->Captcha->generate();
		$this->SessionPage->Captcha = $this->Captcha->getId();

		echo $CaptchaGenerate;
	}

}

?>
