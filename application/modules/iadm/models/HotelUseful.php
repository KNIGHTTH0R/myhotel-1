<?php

class Iadm_Model_HotelUseful extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_useful';
		$this->_primary = 'Useful_Id';
	}

	public function getListUseful($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('Useful_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('Useful_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('Useful_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Useful_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertUseful($params) {

		$this->insert($params);
	}

	public function updateUseful($data, $id) {

		$where = 'Useful_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteUseful($id) {

		$where = 'Useful_Id=' . $id;
		$this->delete($where);
	}

}

?>
