<?php

class Iadm_LoginController extends Zend_Controller_Action {

	protected $ErrorMess = array();
	protected $Username;
	protected $Password;
	protected $Email;
	protected $RememberLogin;
	protected $User;
	protected $UserModel;
	protected $PasswordRand;
	protected $Message = array();

	public function init() {
		parent::init();

		$this->User = new Zend_Session_Namespace('userLog');

		if (!empty($this->User->UserInfo)) {
			$this->_redirect('/iadm/');
		}

		MyZend_Function::setLayout(_BACKEND_PATH, 'login');
		$this->UserModel = new Iadm_Model_Login();
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('Message')) {
			$this->Message[] = $this->getRequest()->getParam('Message');
			$this->view->assign('message', $this->Message);
			return;
		}

		if ($this->_request->isPost()) {
			$params = $this->getRequest()->getParams();
			$params = MyZend_Function::filterInput($params, 'array');
			$this->Username = trim($params['username']);
			$this->Password = trim($params['password']);
			$this->RememberLogin = (isset($params['rememberLogin'])) ? $params['rememberLogin'] : "";

			$ResultAdUser = $this->UserModel->getAdUser(array(
				'task' => 'checkLogin',
				'Username' => $this->Username,
				'Password' => MyZend_Function::hasPassword($this->Password)));

			if (count($ResultAdUser) <= 0) {
				$this->ErrorMess[] = 'Tài khoản hoặc Mật khẩu không đúng.';
				$this->view->assign('errorMess', $this->ErrorMess);
			} else {
				$ResultAdUser = MyZend_Function::filterOutput($ResultAdUser->toArray(), 'array');
				$ResultAdUser['Password'] = "";

				$this->User->UserInfo = $ResultAdUser;
				if ($this->RememberLogin == '1') {
					$userCookie = setcookie('userName', $params['username'], time() + _COOKIE_TIME);
					$passCookie = setcookie('passWord', $params['password'], time() + _COOKIE_TIME);
					$checkRememberCookie = setcookie('checkRemember', $this->RememberLogin, time() + _COOKIE_TIME);
				} else {
					setcookie('userName', $params['username'], time() - _COOKIE_TIME);
					setcookie('passWord', $params['password'], time() - _COOKIE_TIME);
					setcookie('checkRemember', $this->RememberLogin, time() - _COOKIE_TIME);
				}
				$this->_redirect('/iadm/');
			}
		}

		if (isset($_COOKIE['userName'])) {
			$this->view->Username = $_COOKIE['userName'];
		}
		if (isset($_COOKIE['passWord'])) {
			$this->view->Password = $_COOKIE['passWord'];
		}
		if (isset($_COOKIE['checkRemember'])) {
			$this->view->RememberLogin = $_COOKIE['checkRemember'];
		}
	}

	public function forgotAction() {
		if ($this->_request->isPost()) {
			$params = $this->getRequest()->getParams();
			$params = MyZend_Function::filterInput($params, 'array');
			$this->Email = trim($params['email']);
			$this->Username = trim($params['username']);
			$Result = $this->UserModel->getAdUser(array(
				'task' => 'checkEmailExist',
				'Username' => $this->Username,
				'Email' => $this->Email,
			));

			if (count($Result) <= 0) {
				$this->ErrorMess[] = 'Tài khoản hoặc Email không tồn tại.';
			} else {
				$this->PasswordRand = MyZend_Function::getRandPassword();
				try {
					$EmailSystem = new Iadm_Model_EmailSystem();
					$EmailAdmin = new Iadm_Model_AdminAccess();
					$ResultEmailSystem = $EmailSystem->getEmailSystem(array('task' => 'edit', 'id' => 1));
					$ResultEmailAdmin = $EmailAdmin->getAdminAccess(array('task' => 'getEdit'));
					$optionsMail = array(
						'Content' => 'Mật khẩu mới của bạn là: ' . $this->PasswordRand,
						'From' => $ResultEmailAdmin['Email'],
						'To' => $this->Email,
						'Subject' => 'Lấy lại mật khẩu.',
						'HostSMTP' => $ResultEmailSystem['HostSMTP'],
						'UserSMTP' => $ResultEmailSystem['UserSMTP'],
						'PassSMTP' => $ResultEmailSystem['PassSMTP'],
						'Auth' => 'login',
					);
					$Mail = new MyZend_SendMail($optionsMail);
					$Mail->send();
					$this->UserModel->updatePass(array(
						'Username' => $this->Username,
						'Password' => MyZend_Function::hasPassword($this->PasswordRand)));
					$this->Message = 'Hệ thống đã gửi email chứa thông tin mật khẩu cho bạn. Vui lòng kiểm tra và đăng nhập.';
					$this->forward('index', 'login', 'iadm', array('Message' => $this->Message));
				} catch (Exception $e) {
					$this->ErrorMess[] = 'Có lỗi trong quá trình gửi mail.';
					Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
				}
			}
		}
		$this->view->assign('errorMess', $this->ErrorMess);
	}

}

?>
