<?php

class Iadm_Model_HotelDistrict extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;
	protected $_db;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_district';
		$this->_primary = 'District_Id';
		$this->_db = Zend_Registry::get('connectDB');
	}

	public function getListDistrict($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->_db->select()
					->from(array('d' => $this->_name))
					->joinLeft(array('p' => _PREFIX . 'hotel_province'), 'd.Province_Id = p.Province_Id');
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('d.Province_Id DESC');
			$result = $this->_db->fetchAll($select);
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
			$select = $this->select()->where('District_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}

		if ($options['task'] == 'getWhereProvince') {
			$select = $this->_db->select()
					->from(array('d' => $this->_name))
					->joinLeft(array('p' => _PREFIX . 'hotel_province'), 'd.Province_Id = p.Province_Id')
					->where('p.Province_Id =?', $options['provinceId']);
			$select->order('d.Province_Id DESC');
			$result = $this->_db->fetchAll($select);
		}

		return $result;
	}

	public function insertDistrict($params) {

		$this->insert($params);
	}

	public function updateDistrict($data, $id) {

		$where = 'District_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteDistrict($id) {

		$where = 'District_Id=' . $id;
		$this->delete($where);
	}

}

?>
