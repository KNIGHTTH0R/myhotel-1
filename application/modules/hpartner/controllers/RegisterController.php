<?php

class Hpartner_RegisterController extends Zend_Controller_Action {

	protected $Province;
	protected $District;
	protected $HotelType;
	protected $Validate;
	protected $Hotel;
	protected $Partner;
	protected $PageProcess = 1;
	protected $Msg;
	protected $SessionPage;
	protected $Captcha;
	protected $Register;

	public function init() {
		parent::init();

		$this->SessionPage = new Zend_Session_Namespace('SessionPage');

		//Session user partner
		$this->UserPartner = new Zend_Session_Namespace('userLogPartner');
		//Captcha
		$this->Captcha = Zend_Registry::get('captcha');

		//Check exist login
		if (!empty($this->UserPartner->UserInfo)) {
			$this->_redirect('/hpartner/hotel-profile');
		}
		//Set layout
		MyZend_Function::setLayout(_HPARTNER_PATH, 'login');

		$this->Province = new Hpartner_Model_HotelProvince();
		$this->District = new Hpartner_Model_HotelDistrict();
		$this->HotelType = new Hpartner_Model_HotelType();
		$this->Hotel = new Hpartner_Model_Hotel();
		$this->Partner = new Hpartner_Model_User();
		$this->Register = new Hpartner_Model_Register();
		$this->Validate = new MyZend_Validate();
		$this->Msg = new MyZend_Message();
	}

	public function indexAction() {

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			//Check input valid
			foreach ($params as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Filter input
			$params = MyZend_Function::filterInputPartner($params, 'array');
			//Check Captcha
			$captchaIterator = $this->Captcha->getIterator($this->SessionPage->Captcha);
			if ($params['Captcha'] != $captchaIterator['word']) {
				$this->ErrorMsg[] = 'Mã xác thực không đúng. Vui lòng nhập lại.';
			}
			//Check exist email
			$checkEmail = $this->Partner->getListUser(array('task' => 'checkEmail', 'email' => $params['Email']));
			if ($checkEmail->count > 0) {
				$this->ErrorMsg[] = $this->Msg['EmailExist'];
			}
			//Validate not null
			if (!$this->Validate->Validate->Null->isValid($params['Hotel_Name'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Tên khách sạn.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Room']) || $params['Room'] == 0) {
				$this->ErrorMsg[] = 'Vui lòng nhập Số phòng.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Province'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Tỉnh/Thành.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['District'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Quận/Huyện.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['HotelType'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Loại khách sạn.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Address'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Địa chỉ.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Hotel_Phone'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Số điện thoại chính.';
			}
			if (!$this->Validate->Validate->Digit->isValid($params['Hotel_Phone'])) {
				$this->ErrorMsg[] = 'Số điện thoại chính không hợp lệ. Vui lòng nhập lại.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Name'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Tên liên lạc chính.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Phone'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Số điện thoại liên hệ.';
			}
			if (!$this->Validate->Validate->Digit->isValid($params['Phone'])) {
				$this->ErrorMsg[] = 'Số điện thoại liên hệ không hợp lệ. Vui lòng nhập lại.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['Email'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Email.';
			}
			if (!$this->Validate->Validate->Email->isValid($params['Email'])) {
				$this->ErrorMsg[] = 'Email không hợp lệ. Vui lòng nhập lại.';
			}

			if (count($this->ErrorMsg) <= 0) {
				$result = $this->Register->regsiter($params);
				//Send mail
				if ($result) {
					$EmailSystem = new Iadm_Model_EmailSystem();
					$ResultEmailSystem = MyZend_Function::filterOutputPartner($EmailSystem->getEmailSystem(array('task' => 'edit', 'id' => 1)), 'array');
					//Get admin access info
					$EmailAdmin = new Iadm_Model_AdminAccess();
					$ResultEmailAdmin = MyZend_Function::filterOutputPartner($EmailAdmin->getAdminAccess(array('task' => 'getEdit')), 'array');
					//Send mail SMTP
					$TemplateMail = new MyZend_TemplateMail();
					//Get template mail register
					$BodyEmail = $TemplateMail->getTemplateRegisPartner($params);
					$optionsMail = array(
						'Content' => $BodyEmail,
						'From' => $ResultEmailAdmin['Email'],
						'To' => $params['Email'],
						'Subject' => 'Thông tin đăng ký đối tác',
						'HostSMTP' => $ResultEmailSystem['HostSMTP'],
						'UserSMTP' => $ResultEmailSystem['UserSMTP'],
						'PassSMTP' => $ResultEmailSystem['PassSMTP'],
					);
					try {
						$Mail = new MyZend_SendMail($optionsMail);
						$Mail->send();
					} catch (Exception $e) {
						Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
					}
					$this->SuccessMsg[] = $this->Msg->getMessage('register-success');
					$this->PageProcess = 2;
				}
			}
		}

		$Province = $this->Province->getListProvince(array('task' => 'getAll'));
		$Province = MyZend_Function::filterOutputPartner($Province, 'array');

		$HotelType = $this->HotelType->getListHotelType(array('task' => 'getAll'));
		$HotelType = MyZend_Function::filterOutputPartner($HotelType, 'array');

		$CaptchaGenerate = $this->Captcha->generate();
		$this->SessionPage->Captcha = $this->Captcha->getId();

		$this->view->assign('CaptchaGenerate', $CaptchaGenerate);
		$this->view->assign('Province', $Province);
		$this->view->assign('HotelType', $HotelType);
		$this->view->assign('errorMess', $this->ErrorMsg);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('PageProcess', $this->PageProcess);
		$this->view->assign('Params', $params);
		$this->view->assign('BodyEmail', $BodyEmail);
	}

	public function mappingProvinceAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$params = $this->getRequest()->getParams();
		$params = MyZend_Function::filterInputPartner($params, 'array');
		$result = $this->District->getListDistrict(array('task' => 'getWhereProvince', 'provinceId' => $params['provinceId']), 'array');
		echo json_encode($result);
	}

	public function checkExistEmailAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$params = MyZend_Function::filterInputPartner($params, 'array');

		$result = $this->Partner->getListUser(array('task' => 'checkEmail', 'email' => $params['Email']));
		echo $result->count;
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
