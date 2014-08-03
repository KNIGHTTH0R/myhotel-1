<?php

class MyZend_Controller_AdminHpartnerAction extends Zend_Controller_Action {

    protected $UserPartner;
    protected $ErrorMsg = array();
    protected $WarningMsg = array();
    protected $SuccessMsg = array();
    protected $Log;
    protected $Msg;

    public function init() {
        parent::init();

		//Log
        $this->Log = Zend_Registry::get('Log');
		//Message
        $this->Msg = new MyZend_Message();
		//Session User Partner
        $this->UserPartner = new Zend_Session_Namespace('userLogPartner');
		//Check exist login
        if (empty($this->UserPartner->UserInfo)) {
            $this->_redirect('/hpartner/login');
        }
		//Get layout
        MyZend_Function::setLayout(_HPARTNER_PATH, 'index');

        $this->view->assign('UserPartner', $this->UserPartner->UserInfo);
    }

}

?>