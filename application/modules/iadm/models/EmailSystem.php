<?php

class Iadm_Model_EmailSystem extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'email_system';
		$this->_primary = 'Id';
	}

	public function getEmailSystem($options) {

		if ($options['task'] == 'edit') {
			$select = $this->select()->where('Id =?', $options['id'])->order('Id DESC');
			$result = $this->fetchRow($select);
		}

		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Id =?', 1);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function updateEmailSystem($data) {

		$where = 'Id = 1';
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

}