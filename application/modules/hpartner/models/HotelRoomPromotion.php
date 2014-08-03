<?php

class Hpartner_Model_HotelRoomPromotion extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_promotion';
		$this->_primary = 'RoomPromotion_Id';
	}

	public function getListPromotion($options) {
		try {
			if ($options['task'] == 'getInfo') {
				$select = $this->select();
				if ($options['select']) {
					$select->from(_PREFIX . 'hotel_room_promotion', $options['select']);
				} else {
					$select->from(_PREFIX . 'hotel_room_promotion');
				}
				$select->where('RoomPromotion_Status =?', 1);
				$select->where('Hotel_Id =?', $options['Hotel_Id']);
				//get edit
				if (isset($options['RoomPromotion_Id'])) {
					$select->where('RoomPromotion_Id =?', $options['RoomPromotion_Id']);
					$result = $this->fetchRow($select);
					//get list	
				} else {
					$result = $this->fetchAll($select)->toArray();
				}
			}

			if ($options['task'] == 'getPage') {
				$select = $this->select();
				if ($options['select']) {
					$select->from(_PREFIX . 'hotel_room_promotion', $options['select']);
				} else {
					$select->from(_PREFIX . 'hotel_room_promotion');
				}
				$select->where('Hotel_Id =?', $options['Hotel_Id']);
				$select->where('RoomPromotion_Status =?', 1);
				$select->limitPage($options['pageCurrent'], $options['offset']);
				$select->order('RoomPromotion_Id DESC');
				$result = $this->fetchAll($select)->toArray();
			}
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
		return $result;
	}

	public function updatePromotion($data, $id, $hotel) {
		try {
			$where = 'RoomPromotion_Id =' . $id . ' AND Hotel_Id = ' . $hotel;
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

	public function insertPromotion($params) {
		try {
			$this->insert($params);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

}

?>
