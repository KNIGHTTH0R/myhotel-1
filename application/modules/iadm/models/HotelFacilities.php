<?php

class Iadm_Model_HotelFacilities extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_facilities';
		$this->_primary = 'Facilities_Id';
	}

	public function getListFacilities($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('Facilities_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('Facilities_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('Facilities_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Facilities_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertFacilities($params) {

		$this->insert($params);
	}

	public function updateFacilities($data, $id) {

		$where = 'Facilities_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteFacilities($id) {

		$where = 'Facilities_Id=' . $id;
		$this->delete($where);
	}

	public function updatePicture($id) {

		$where = 'Facilities_Id =' . $id;
		$db = $this->fetchRow($where);
		$db->Facilities_Picture = '';
		$db->save();
	}

}

?>
