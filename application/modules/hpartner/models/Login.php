<?php

class Hpartner_Model_Login extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;
	protected $_result;

	public function init() {
		parent::init();
		$this->_name = _PREFIX . 'user';
		$this->_primary = 'User_Id';
	}

	public function getAdUser($options) {
		try {
			if ($options['task'] == 'checkLogin') {
				$select = $this->select()
						->where('User_Email =?', $options['Email'])
						->where('User_Password =?', $options['Password'])
						->where('User_Status =?', 1)
						->where('User_Type =?', 1); //Partner
			}
			if ($options['task'] == 'checkEmailExist') {
				$select = $this->select()
						->where('User_Email =?', $options['Email'])
						->where('User_Status =?', 1)
						->where('User_Type =?', 1); //Partner
			}
			$result = $this->fetchRow($select);
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function updatePass($options) {
		try {
			$where = 'User_Email = "' . $options['Email'] . '" AND User_Type = 1 AND User_Status = 1';
			$db = $this->fetchRow($where);
			$db->User_Password = $options['Password'];
			$db->save();
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

}

?>
