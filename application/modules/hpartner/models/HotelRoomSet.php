<?php

class Hpartner_Model_HotelRoomSet extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_set';
		$this->_primary = 'RoomSet_Id';
	}

	public function getListRoomSet($options) {
		try {
			if ($options['task'] == 'getEditWhereDateHotelRoom') {
				$select = $this->select();
				$select->where('RoomSet_Date =?', $options['Date']);
				$select->where('Hotel_Id =?', $options['Hotel']);
				$select->where('Room_Id =?', $options['Room']);
				$result = $this->fetchAll($select)->toArray();
			}
			if ($options['task'] == 'getEditWhereBetweenDateHotelRoom') {
				$select = $this->select();
				if ($options['select']) {
					$select->from(_PREFIX . 'hotel_room_set', $options['select']);
				} else {
					$select->from(_PREFIX . 'hotel_room_set');
				}
				$select->where('RoomSet_Date >= "' . $options['DateFrom'] . '"');
				$select->where('RoomSet_Date <= "' . $options['DateTo'] . '"');
				$select->where('Hotel_Id =?', $options['Hotel']);
				$select->where('Room_Id =?', $options['Room']);
				$select->order('RoomSet_Date ASC');
				$result = $this->fetchAll($select)->toArray();
			}
			if ($options['task'] == 'getWhereInRoomSetId') {
				$select = $this->select();
				if ($options['select']) {
					$select->from(_PREFIX . 'hotel_room_set', $options['select']);
				} else {
					$select->from(_PREFIX . 'hotel_room_set');
				}
				$select->where('RoomSet_Id IN (' . $options['RoomSetId'] . ')');
				$select->where('Hotel_Id =?', $options['Hotel']);
				$result = $this->fetchAll($select)->toArray();
			}
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
		return $result;
	}

	public function insertRoomSet($params) {
		try {
			$this->insert($params);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

	public function updateRoomSet($data, $id, $hotel) {
		try {
			$where = 'RoomSet_Id = ' . $id . ' AND Hotel_Id = ' . $hotel;
			$db = $this->fetchRow($where);
			foreach ($data as $k => $v) {
				$db->$k = $v;
			}
			$db->save();
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

	public function updateRoomSetWhereDate($data, $date, $hotel, $room) {
		try {
			$where = 'RoomSet_Date = "' . $date . '" AND Hotel_Id = ' . $hotel . ' AND Room_Id = ' . $room;
			$db = $this->fetchRow($where);
			foreach ($data as $k => $v) {
				$db->$k = $v;
			}
			$db->save();
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

}

?>
