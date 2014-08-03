<?php

class Iadm_Model_HotelRoomPromotion extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;
	protected $_db;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_promotion';
		$this->_primary = 'RoomPromotion_Id';
		$this->_db = Zend_Registry::get('connectDB');
	}

	public function getListPromotion($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->_db->select()->from(array('p' => $this->_name));
			if (isset($options['search'])) {
				if ($options['search']['StatusSearch'] != null) {
					$select->where("p.RoomPromotion_Status =?", $options['search']['StatusSearch']);
				}
				if ($options['search']['HotelSearch'] != null) {
					$select->where("p.Hotel_Id =?", $options['search']['HotelSearch']);
				}
			}
			$select->joinLeft(array('h' => _PREFIX . 'hotel'), "p.Hotel_Id = h.Hotel_Id");
			$select->joinLeft(array('po' => _PREFIX . 'hotel_room_policy_cancel'), "p.PolicyCancel_Id = po.PolicyCancel_Id");
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('RoomPromotion_Id DESC');
			$result = $this->_db->fetchAll($select);
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('RoomPromotion_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			if (isset($options['search'])) {
				if ($options['search']['StatusSearch'] != null) {
					$select->where("RoomPromotion_Status =?", $options['search']['StatusSearch']);
				}
				if ($options['search']['HotelSearch'] != null) {
					$select->where("Hotel_Id =?", $options['search']['HotelSearch']);
				}
			}
			$select->order('RoomPromotion_Id DESC');
			$result = $this->fetchRow($select);
		}

		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('RoomPromotion_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertPromotion($params) {

		$this->insert($params);
	}

	public function updatePromotion($data, $id) {

		$where = 'RoomPromotion_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deletePromotion($id) {

		$where = 'RoomPromotion_Id=' . $id;
		$this->delete($where);
	}

}

?>
