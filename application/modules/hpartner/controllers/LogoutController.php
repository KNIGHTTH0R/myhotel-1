<?php

class Hpartner_LogoutController extends Zend_Controller_Action {

	public function indexAction() {
		$User = new Zend_Session_Namespace('userLogPartner');
		$User->unsetAll();
		$this->_redirect('/hpartner/login');
	}
}

?>
