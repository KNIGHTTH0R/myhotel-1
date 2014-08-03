<?php

class Hpartner_Model_HotelRoom extends MyZend_Model_DbHpartner {

	public function init() {
		parent::init();
	}

	public function getHotelRoom($options) {
		try {
			$result = array();
			switch ($options['task']) {
				case 'getInfoWhereHotel':
					$select = $this->Db->select();
					if ($options['select']) {
						$select->from(_PREFIX . 'hotel_room', $options['select']);
					} else {
						$select->from(_PREFIX . 'hotel_room');
					}
					$select->where('Hotel_Id =?', $options['Hotel_Id']);
					$select->where('Room_Status =?', 1);
					$result = $this->Db->fetchAll($select);
					break;
				case 'getInfoOneRoom':
					$select = $this->Db->select();
					if ($options['select']) {
						$select->from(array('r' => _PREFIX . 'hotel_room'), $options['select']);
					} else {
						$select->from(array('r' => _PREFIX . 'hotel_room'));
					}
					$select->joinLeft(array('vi' => _PREFIX . 'hotel_room_view'), 'r.RoomView_Id = vi.RoomView_Id');
					$select->where('r.Hotel_Id =?', $options['Hotel_Id']);
					$select->where('r.Room_Id =?', $options['Room_Id']);
					$select->where('r.Room_Status =?', 1);
					$result = $this->Db->fetchRow($select);
					break;
				case 'getPage':
					$select = $this->Db->select();
					if ($options['select']) {
						$select->from(array('h' => _PREFIX . 'hotel_room'), $options['select']);
						$select->joinLeft(array('p' => _PREFIX . 'hotel'), "h.Hotel_Id = p.Hotel_Id", array());
					} else {
						$select->from(array('h' => _PREFIX . 'hotel_room'));
						$select->joinLeft(array('p' => _PREFIX . 'hotel'), "h.Hotel_Id = p.Hotel_Id");
					}
					
					$select->limitPage($options['pageCurrent'], $options['offset']);
					$select->where('h.Hotel_Id =?', $options['Hotel_Id']);
					$select->where('h.Room_Status =?', 1);
					$select->order('h.Room_Id DESC');
					$result = $this->Db->fetchAll($select);
					break;
				case 'getWhereListRoomId':
					$select = $this->Db->select();
					if ($options['select']) {
						$select->from(_PREFIX . 'hotel_room', $options['select']);
					} else {
						$select->from(_PREFIX . 'hotel_room');
					}
					$select->where('Room_Id IN (?)', $options['listId']);
					$select->where('Hotel_Id =?',  $options['Hotel_Id']);
					$select->where('Room_Status =?', 1);
					$result = $this->Db->fetchAll($select);
					break;
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function updateHotelRoom($input, $Room_Id, $Hotel_Id) {
		try {
			$where = "Room_Id = " . $Room_Id . " AND Hotel_Id = " . $Hotel_Id;
			$this->Db->update(_PREFIX . 'hotel_room', $input, $where);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

}



?>
