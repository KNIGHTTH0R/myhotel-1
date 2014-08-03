<?php

class Iadm_Model_HotelRoomPolicyCancelSet extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;
	protected $_db;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_policy_cancel_set';
		$this->_primary = 'PolicyCancelSet_Id';
		$this->_db = Zend_Registry::get('connectDB');
	}

	public function getListPolicyCancelSet($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->_db->select()->from(array('ps' => $this->_name));
			if (isset($options['search'])) {
				if ($options['search']['StatusSearch'] != null) {
					$select->where("ps.PolicyCancelSet_Status =?", $options['search']['StatusSearch']);
				}
				if ($options['search']['HotelSearch'] != null) {
					$select->where("ps.Hotel_Id =?", $options['search']['HotelSearch']);
				}
			}
			$select->joinLeft(array('p' => _PREFIX . 'hotel_room_policy_cancel'), "ps.PolicyCancel_Id = p.PolicyCancel_Id");
			$select->joinLeft(array('h' => _PREFIX . 'hotel'), "ps.Hotel_Id = h.Hotel_Id");
			$select->joinLeft(array('r' => _PREFIX . 'hotel_room'), "ps.Room_Id = r.Room_Id");
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('ps.PolicyCancelSet_Id DESC');
			$result = $this->_db->fetchAll($select);
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('PolicyCancelSet_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			if (isset($options['search'])) {
				if ($options['search']['StatusSearch'] != null) {
					$select->where("PolicyCancelSet_Status =?", $options['search']['StatusSearch']);
				}
				if ($options['search']['HotelSearch'] != null) {
					$select->where("Hotel_Id =?", $options['search']['HotelSearch']);
				}
			}
			$select->order('PolicyCancelSet_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('PolicyCancelSet_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertPolicyCancelSet($params) {

		$this->insert($params);
	}

	public function updatePolicyCancelSet($data, $id) {

		$where = 'PolicyCancelSet_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deletePolicyCancelSet($id) {

		$where = 'PolicyCancelSet_Id=' . $id;
		$this->delete($where);
	}

}

?>
