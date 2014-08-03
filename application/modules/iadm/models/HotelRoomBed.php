<?php

class Iadm_Model_HotelRoomBed extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_bed';
		$this->_primary = 'Room_Bed_Id';
	}

	public function getListRoomBed($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('Room_Bed_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('Room_Bed_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('Room_Bed_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('Room_Bed_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertRoomBed($params) {

		$this->insert($params);
	}

	public function updateRoomBed($data, $id) {

		$where = 'Room_Bed_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteRoomBed($id) {

		$where = 'Room_Bed_Id=' . $id;
		$this->delete($where);
	}

}

?>
