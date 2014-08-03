<?php

class Iadm_EmailSystemController extends MyZend_Controller_AdminAction {

	protected $Model;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_EmailSystem();
	}

	public function indexAction() {

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getParams();
			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['HostSMTP'] = $params['HostSMTP'];
				$input['UserSMTP'] = $params['UserSMTP'];
				if (isset($params['PassSMTP']) && $params['PassSMTP'] != "**********" && $params['PassSMTP'] != "") {
					$input['PassSMTP'] = $params['PassSMTP'];
				}
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateEmailSystem($input);
				$this->SuccessMsg[] = $this->Msg['SuccessUpdate'];
			}
		}
		$data = $this->Model->getEmailSystem(array('task' => 'getEdit'));
		$this->view->assign('EmailSystem', MyZend_Function::filterOutput($data, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

}

?>
