<?php

class Iadm_Model_SeoKeyword extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'seo_keyword';
		$this->_primary = 'Id';
	}

	public function getListSeoKeyword($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('Id DESC');
			$result = $this->fetchAll($select);
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('Id DESC');
			$result = $this->fetchRow($select);
		}

		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertSeoKeyword($params) {

		$this->insert($params);
	}

	public function updateSeoKeyword($data, $id) {

		$where = 'Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteSeoKeyword($id) {

		$where = 'Id=' . $id;
		$this->delete($where);
	}

}

?>
