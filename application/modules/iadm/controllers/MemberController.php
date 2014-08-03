<?php

class Iadm_MemberController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;
	protected $FieldSearch = array('EmailSearch', 'StatusSearch');

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_Member();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
		foreach ($this->FieldSearch as $v) {
			$this->Search[$v] = null;
		}
		$Params = $this->getRequest()->getParams();
		if (!isset($Params['page']) && $Params['action'] != 'edit' && $Params['action'] != 'delete') {
			unset($this->SessionPage->SearchInfo);
		}

		if (isset($this->SessionPage->SearchInfo)) {
			foreach ($this->FieldSearch as $v) {
				$this->Search[$v] = $this->SessionPage->SearchInfo[$v];
			}
		}
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('typeAction') && $this->getRequest()->getParam('typeAction') == 'search') {
			$paramsPost = $this->getRequest()->getParams();
			foreach ($this->FieldSearch as $v) {
				$this->Search[$v] = $this->SessionPage->SearchInfo[$v] = $paramsPost[$v];
			}
		}

		$MemberAll = $this->Model->getListMember(array('task' => 'getnumAll', 'search' => $this->Search));
		$countMemberAll = $MemberAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countMemberAll));

		$listMember = $this->Model->getListMember(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK,
			'search' => $this->Search));

		$fromItem = ($countMemberAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countMemberAll > 0) ? ($fromItem + count($listMember) - 1) : 0;

		$this->view->assign("search", $this->Search);
		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countMemberAll', $countMemberAll);
		$this->view->assign('listMember', MyZend_Function::filterOutput($listMember, 'array'));
		$this->view->assign('page', $page);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function addAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$checkEmail = $this->Model->getListMember(array('task' => 'checkEmail', 'email' => $Params['Email']));
			if ($checkEmail->count == 1) {
				$this->ErrorMsg[] = $this->Msg['EmailExist'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['User_Name'] = $Params['Name'];
				$input['User_Status'] = $Params['Status'];
				$input['User_Email'] = $Params['Email'];
				$input['User_Password'] = MyZend_Function::hasPassword($Params['Password']);
				$input['User_DateCreate'] = date('Y-m-d H:i:s');
				$input['User_Phone'] = $Params['Phone'];
				$input['User_Address'] = $Params['Address'];
				$input['User_Birthday'] = MyZend_Function::formatDateDMY_YMD($Params['Birthday'], '/');
				
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertMember($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$MemberInfo = $this->Model->getListMember(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$checkEmail = $this->Model->getListMember(array('task' => 'checkEmail', 'email' => $Params['Email']));
			if ($Params['Email'] != $MemberInfo['User_Email'] && $checkEmail->count == 1) {
				$this->ErrorMsg[] = $this->Msg['EmailExist'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['User_Name'] = $Params['Name'];
				$input['User_Status'] = $Params['Status'];
				$input['User_Email'] = $Params['Email'];
				$input['User_Phone'] = $Params['Phone'];
				$input['User_Address'] = $Params['Address'];
				$input['User_Birthday'] = MyZend_Function::formatDateDMY_YMD($Params['Birthday'], '/');
				if ($Params['Password'] != "") {
					$input['User_Password'] = MyZend_Function::hasPassword($Params['Password']);
				}
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateMember($input, $id);
				$this->forward('index', 'member', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));

				$this->ErrorMsg[] = $this->Msg['ErrorSystem'];
			}
		}

		$this->view->assign('Member', MyZend_Function::filterOutput($MemberInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {
		//Khong cho xoa
		$this->forward('index', 'member', 'iadm', array(
			'page' => $this->SessionPage->currentPageNumber,
			'ErrorMsg' => 'Dữ liệu không thể xóa.'));
		return;


		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('typeAction') && $this->getRequest()->getParam('typeAction') == 'index') {
			$Params = $this->getRequest()->getParam('checkboxDel');
		} else {
			$Id = $this->getRequest()->getParam('id');
			$Params[0] = $Id;
		}

		foreach ($Params as $v) {
			$this->Model->deleteMember($v);
		}
		$this->forward('index', 'member', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

	public function checkEmailAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$result = $this->Model->getListMember(array('task' => 'checkEmail', 'email' => $params['email']));
		echo $result->count;
	}

}

?>
