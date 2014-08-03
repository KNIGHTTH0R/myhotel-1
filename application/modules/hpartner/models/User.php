<?php

class Hpartner_Model_User extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;
	protected $_result;

	public function init() {
		parent::init();
		$this->_name = _PREFIX . 'user';
		$this->_primary = 'User_Id';
	}

	public function getListUser($options) {
		try {
			if ($options['task'] == 'checkEmail') {
				$select = $this->select()->from($this->_name, "COUNT(*) as count");
				$select->where('User_Email =?', $options['email']);
				$result = $this->fetchRow($select);
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function insertUser($params) {
		try {
			$id = $this->insert($params);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return array('Return' => false);
		}
		return array('User_Id' => $id, 'Return' => true);
	}

}

?>
