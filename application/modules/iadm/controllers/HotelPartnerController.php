<?php

class Iadm_HotelPartnerController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;
	protected $FieldSearch = array('EmailSearch', 'PhoneSearch', 'StatusSearch');

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelPartner();
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

		$PartnerAll = $this->Model->getListPartner(array('task' => 'getnumAll', 'search' => $this->Search));
		$countPartnerAll = $PartnerAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countPartnerAll));

		$listPartner = $this->Model->getListPartner(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK,
			'search' => $this->Search));

		$fromItem = ($countPartnerAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countPartnerAll > 0) ? ($fromItem + count($listPartner) - 1) : 0;

		$this->view->assign("search", $this->Search);
		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countPartnerAll', $countPartnerAll);
		$this->view->assign('listPartner', MyZend_Function::filterOutput($listPartner, 'array'));
		$this->view->assign('page', $page);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function addAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}

			$checkEmail = $this->Model->getListPartner(array('task' => 'checkEmail', 'email' => $Params['Email']));
			if ($checkEmail->count == 1) {
				$this->ErrorMsg[] = $this->Msg['EmailExist'];
			}

			if (count($this->ErrorMsg) == 0) {
				$input = array();
				$input['User_Name'] = $Params['Name'];
				$input['User_Status'] = $Params['Status'];
				$input['User_Phone'] = $Params['Phone'];
				$input['User_Email'] = $Params['Email'];
				$input['User_Password'] = MyZend_Function::hasPassword($Params['Password']);
				$input['User_DateCreate'] = date('Y-m-d H:i:s');
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertPartner($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$PartnerInfo = $this->Model->getListPartner(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}

			$checkEmail = $this->Model->getListPartner(array('task' => 'checkEmail', 'email' => $Params['Email']));
			if ($Params['Email'] != $PartnerInfo['User_Email'] && $checkEmail->count == 1) {
				$this->ErrorMsg[] = $this->Msg['EmailExist'];
			}
			
			if ($Params['Status'] == 2) {
				$this->ErrorMsg[] = 'Đối tác này có trạng thái "Chờ duyệt". Vui lòng thay đổi trạng thái khác.';
			}
			
			if (count($this->ErrorMsg) == 0) {
				$input = array();
				$input['User_Name'] = $Params['Name'];
				$input['User_Status'] = $Params['Status'];
				$input['User_Phone'] = $Params['Phone'];
				$input['User_Email'] = $Params['Email'];
				if ($Params['Password'] != "") {
					$input['User_Password'] = $Params['Password'] = MyZend_Function::hasPassword($Params['Password']);
				}
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updatePartner($input, $id);
				
				//Send mail khi chon checkbox va co thay doi password
				if ($Params['Sendmail'] && $Params['Sendmail'] == 1 && $Params['Password'] != "") {
					$Params['User_id'] = $id;
					$this->sendMail($Params);
				}
				
				$this->forward('index', 'hotel-partner', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
				
			}
		}

		$this->view->assign('Partner', MyZend_Function::filterOutput($PartnerInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {
		//Khong cho xoa
		$this->forward('index', 'hotel-partner', 'iadm', array(
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
			$this->Model->deletePartner($v);
		}
		$this->forward('index', 'hotel-partner', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

	public function checkEmailAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$result = $this->Model->getListPartner(array('task' => 'checkEmail', 'email' => $params['email']));
		echo $result->count;
	}

	public function sendMail($Params) {
		try {
			//Hotel Info
			$Hotel = new Iadm_Model_Hotel();
			$HotelInfo = $Hotel->getListHotel(array('task' => 'whereUser', 'User_Id' => $Params['User_id']));
			
			$paramsSendMail = array();
			$paramsSendMail['Hotel_Name'] = $HotelInfo['Hotel_Name'];
			$paramsSendMail['Address'] = $HotelInfo['Hotel_Address'];
			$paramsSendMail['Room'] = $HotelInfo['Hotel_Room'];
			$paramsSendMail['Star'] = $HotelInfo['Hotel_Star'];
			$paramsSendMail['Email'] = $Params['Email'];
			$paramsSendMail['Phone'] = $Params['Phone'];

			//Get template mail register
			$TemplateMail = new MyZend_TemplateMail();
			$BodyEmail = $TemplateMail->getTemplateConfirmRegisPartnerSuccess($paramsSendMail);

			//Get email system info
			$EmailSystem = new Iadm_Model_EmailSystem();
			$ResultEmailSystem = MyZend_Function::filterOutputPartner($EmailSystem->getEmailSystem(array('task' => 'edit', 'id' => 1)), 'array');
			//Get admin access info
			$EmailAdmin = new Iadm_Model_AdminAccess();
			$ResultEmailAdmin = MyZend_Function::filterOutputPartner($EmailAdmin->getAdminAccess(array('task' => 'getEdit')), 'array');
			
			$optionsMail = array(
				'Content' => $BodyEmail,
				'From' => $ResultEmailAdmin['Email'],
				'To' => $Params['Email'],
				'Subject' => 'Xác nhận đăng ký quản lý khách sạn',
				'HostSMTP' => $ResultEmailSystem['HostSMTP'],
				'UserSMTP' => $ResultEmailSystem['UserSMTP'],
				'PassSMTP' => $ResultEmailSystem['PassSMTP'],
			);

			$Mail = new MyZend_SendMail($optionsMail);
			$Mail->send();
			return true;
		} catch (Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
	}

}

?>
