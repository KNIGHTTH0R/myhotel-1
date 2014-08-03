<?php

class Iadm_Model_HotelSights extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;
	protected $_db;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_sights';
		$this->_primary = 'Sights_Id';
		$this->_db = Zend_Registry::get('connectDB');
	}

	public function getListSights($options) {

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
			$select->order('Sights_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('Sights_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Sights_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertSights($params) {

		$this->insert($params);
	}

	public function updateSights($data, $id) {

		$where = 'Sights_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteSights($id) {

		$where = 'Sights_Id=' . $id;
		$this->delete($where);
	}

}

?>
