<?php

class Iadm_Model_HotelProvince extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_province';
		$this->_primary = 'Province_Id';
	}

	public function getListProvince($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('Province_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('Province_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('Province_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Province_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertProvince($params) {

		$this->insert($params);
	}

	public function updateProvince($data, $id) {

		$where = 'Province_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteProvince($id) {

		$where = 'Province_Id=' . $id;
		$this->delete($where);
	}

	public function updatePicture($id) {

		$where = 'Province_Id =' . $id;
		$db = $this->fetchRow($where);
		$db->Province_Picture = '';
		$db->save();
	}

}

?>
