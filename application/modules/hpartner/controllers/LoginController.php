<?php

class Hpartner_LoginController extends Zend_Controller_Action {

	protected $ErrorMess = array();
	protected $Username;
	protected $Password;
	protected $Email;
	protected $RememberLogin;
	protected $User;
	protected $UserModel;
	protected $PasswordRand;
	protected $Message = array();
	protected $Hotel;
	protected $Validate;
	protected $Captcha;
	protected $SessionPage;

	public function init() {
		parent::init();

		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
		//Session user partner
		$this->UserPartner = new Zend_Session_Namespace('userLogPartner');
		//Check exist login
		if (!empty($this->UserPartner->UserInfo)) {
			$this->_redirect('/hpartner/hotel-profile');
		}
		//Set layout
		MyZend_Function::setLayout(_HPARTNER_PATH, 'login');
		//Load Model
		$this->UserModel = new Hpartner_Model_Login();
		//Load Model Hotel
		$this->Hotel = new Hpartner_Model_Hotel();
		//Validate
		$this->Validate = new MyZend_Validate();
		//Captcha
		$this->Captcha = Zend_Registry::get('captcha');
	}

	public function indexAction() {
		if ($this->getRequest()->getParam('Message')) {
			$this->Message[] = $this->getRequest()->getParam('Message');
			$this->view->assign('message', $this->Message);
			return;
		}

		//Submit login
		if ($this->_request->isPost()) {
			$params = $this->getRequest()->getParams();
			//Check input valid
			foreach ($params as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMess[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Filter input
			$params = MyZend_Function::filterInputPartner($params, 'array');

			//Validate not null
			if (!$this->Validate->Validate->Null->isValid($params['email'])) {
				$this->ErrorMess[] = 'Vui lòng nhập Email.';
			}
			if (!$this->Validate->Validate->Email->isValid($params['email'])) {
				$this->ErrorMess[] = 'Email không hợp lệ. Vui lòng nhập lại.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['password'])) {
				$this->ErrorMess[] = 'Vui lòng nhập Mật khẩu.';
			}

			if (count($this->ErrorMess) <= 0) {
				$this->Email = MyZend_Function::safeInput($params['email']);
				$this->Password = MyZend_Function::safeInput($params['password']);
				$this->RememberLogin = (isset($params['rememberLogin'])) ? MyZend_Function::safeInput($params['rememberLogin']) : "";

				//Check login
				$ResultAdUser = $this->UserModel->getAdUser(array(
					'task' => 'checkLogin',
					'Email' => $this->Email,
					'Password' => MyZend_Function::hasPassword($this->Password)));
				//Wrong login
				if (count($ResultAdUser) <= 0) {
					$this->ErrorMess[] = 'Thông tin đăng nhập không đúng hoặc tài khoản chưa được kích hoạt.';
					//Right login	
				} else {
					$ResultAdUser = MyZend_Function::filterOutputPartner($ResultAdUser, 'array');
					unset($ResultAdUser['Password']);
					//Get Hotel info of Partner
					$HotelInfo = $this->Hotel->getHotel(array('task' => 'getInfo', 'User_Id' => $ResultAdUser['User_Id']));
					$HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');
					//Check status hotel
					if (!isset($HotelInfo['Hotel_Id'])) {
						$this->ErrorMess[] = 'Khách sạn thuộc tài khoản này của Bạn đang trong trạng chờ duyệt.<Br>Vui lòng liên hệ với Ban quản trị.';
					} else {
						//Set session info
						$this->UserPartner->UserInfo = $ResultAdUser;
						$this->UserPartner->HotelInfo = $HotelInfo;
						//Store in cookie
						if ($this->RememberLogin == '1') {
							$userCookie = setcookie('email', $this->Email, time() + _COOKIE_TIME);
							$passCookie = setcookie('passWord', $this->Password, time() + _COOKIE_TIME);
							$checkRememberCookie = setcookie('checkRemember', $this->RememberLogin, time() + _COOKIE_TIME);
							//Destroy cookie	
						} else {
							setcookie('email', $this->Email, time() - _COOKIE_TIME);
							setcookie('passWord', $this->Password, time() - _COOKIE_TIME);
							setcookie('checkRemember', $this->RememberLogin, time() - _COOKIE_TIME);
						}
						$this->_redirect('/hpartner/hotel-profile');
					}
				}
			}
		}

		if (isset($_COOKIE['email'])) {
			$this->view->Email = $_COOKIE['email'];
		}
		if (isset($_COOKIE['passWord'])) {
			$this->view->Password = $_COOKIE['passWord'];
		}
		if (isset($_COOKIE['checkRemember'])) {
			$this->view->RememberLogin = $_COOKIE['checkRemember'];
		}
		$this->view->assign('errorMess', $this->ErrorMess);
	}

	public function forgotAction() {
		if ($this->_request->isPost()) {
			$params = $this->getRequest()->getParams();
			//Filter input
			$params = MyZend_Function::filterInputPartner($params, 'array');

			//Validate not null
			if (!$this->Validate->Validate->Null->isValid($params['email'])) {
				$this->ErrorMess[] = 'Vui lòng nhập Email.';
			}
			if (!$this->Validate->Validate->Email->isValid($params['email'])) {
				$this->ErrorMess[] = 'Email không hợp lệ. Vui lòng nhập lại.';
			}
			//Check input valid
			foreach ($params as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMess[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Check Captcha
			$captchaIterator = $this->Captcha->getIterator($this->SessionPage->Captcha);
			if ($params['Captcha'] != $captchaIterator['word']) {
				$this->ErrorMess[] = 'Mã xác thực không đúng. Vui lòng nhập lại.';
			}
			if (count($this->ErrorMess) <= 0) {
				$this->Email = MyZend_Function::safeInput($params['email']);
				//Check email
				$Result = $this->UserModel->getAdUser(array(
					'task' => 'checkEmailExist',
					'Email' => $this->Email,
				));
				//Wrong check
				if (count($Result) <= 0) {
					$this->ErrorMess[] = 'Email không tồn tại.';
					//Right check	
				} else {
					//Get new password
					$this->PasswordRand = MyZend_Function::getRandPassword();
					try {
						//Get email system info
						$EmailSystem = new Iadm_Model_EmailSystem();
						$ResultEmailSystem = MyZend_Function::filterOutputPartner($EmailSystem->getEmailSystem(array('task' => 'edit', 'id' => 1)), 'array');
						//Get admin access info
						$EmailAdmin = new Iadm_Model_AdminAccess();
						$ResultEmailAdmin = MyZend_Function::filterOutputPartner($EmailAdmin->getAdminAccess(array('task' => 'getEdit')), 'array');
						$TemplateMail = new MyZend_TemplateMail();
						//Get template mail register
						$BodyEmail = $TemplateMail->getTemplateForgotPassword(array('Email' => $this->Email, 'Password' => $this->PasswordRand));
						//Send mail SMTP
						$optionsMail = array(
							'Content' => $BodyEmail,
							'From' => $ResultEmailAdmin['Email'],
							'To' => $this->Email,
							'Subject' => 'Lấy lại mật khẩu.',
							'HostSMTP' => $ResultEmailSystem['HostSMTP'],
							'UserSMTP' => $ResultEmailSystem['UserSMTP'],
							'PassSMTP' => $ResultEmailSystem['PassSMTP'],
						);
						$Mail = new MyZend_SendMail($optionsMail);
						$Mail->send();
						//Update new password
						$this->UserModel->updatePass(array(
							'Email' => $this->Email,
							'Password' => MyZend_Function::hasPassword($this->PasswordRand)));
						$this->Message = 'Hệ thống đã gửi Email chứa thông tin mật khẩu cho Bạn. Vui lòng kiểm tra và đăng nhập.';
						$this->forward('index', 'login', 'hpartner', array('Message' => $this->Message));
					} catch (Exception $e) {
						$this->ErrorMess[] = 'Có lỗi trong quá trình xử lý';
						Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
					}
				}
			}
		}

		$CaptchaGenerate = $this->Captcha->generate();
		$this->SessionPage->Captcha = $this->Captcha->getId();

		$this->view->assign('CaptchaGenerate', $CaptchaGenerate);
		$this->view->assign('errorMess', $this->ErrorMess);
	}
}

?>
