<?php

class Iadm_Model_HotelRoomView extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_view';
		$this->_primary = 'RoomView_Id';
	}

	public function getListRoomView($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('RoomView_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('RoomView_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('RoomView_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('RoomView_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertRoomView($params) {

		$this->insert($params);
	}

	public function updateRoomView($data, $id) {

		$where = 'RoomView_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteRoomView($id) {

		$where = 'RoomView_Id=' . $id;
		$this->delete($where);
	}

}

?>
