<?php

class Iadm_Model_WebInfo extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'web_info';
		$this->_primary = 'Id';
	}

	public function getWebInfo($options) {

		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Id =?', 1);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function updateWebInfo($data) {

		$where = 'Id = 1';
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function updatePicture() {

		$where = 'Id = 1';
		$db = $this->fetchRow($where);
		$db->Picture = '';
		$db->save();
	}

}

?>
