<?php

class Iadm_AdminAccessController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $Msg;

	public function init() {
		try {
			parent::init();
			$this->Model = new Iadm_Model_AdminAccess();
		} catch (Exception $e) {
			$this->Log->log($e->getMessage(), Zend_Log::ERR);
		}
	}

	public function indexAction() {

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getParams();
			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['Username'] = $params['Username'];
				$input['Name'] = $params['Name'];
				$input['Email'] = $params['Email'];
				if (isset($params['Password']) && $params['Password'] != "**********" && $params['Password'] != "") {
					$input['Password'] = MyZend_Function::hasPassword($params['Password']);
				}
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateAdminAccess($input);
				$this->SuccessMsg[] = $this->Msg['SuccessUpdate'];
			}
		}
		$data = $this->Model->getAdminAccess(array('task' => 'getEdit'));
		$this->view->assign('AdminAccess', MyZend_Function::filterOutput($data, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

}

?>