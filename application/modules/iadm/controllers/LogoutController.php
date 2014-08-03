<?php

class Iadm_LogoutController extends Zend_Controller_Action {

	public function indexAction() {
		$User = new Zend_Session_Namespace('userLog');
		$User->unsetAll();
		$this->_redirect('/iadm/login');
	}

}

?>
