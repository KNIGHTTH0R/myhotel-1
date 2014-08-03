<?php

class Iadm_Model_HotelOrder extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;
	protected $_db;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_order';
		$this->_primary = 'HotelOrder_Id';
		$this->_db = Zend_Registry::get('connectDB');
	}

	public function getListOrder($options) {

		if ($options['task'] == 'getList') {
			$select = $this->_db->select()->from(array('o' => $this->_name));
			$select->joinLeft(array('h' => _PREFIX . 'hotel'), "o.Hotel_Id = h.Hotel_Id");
			if (isset($options['search'])) {
				if ($options['search']['HotelSearch'] != null) {
					$select->where("o.Hotel_Id =?", $options['search']['HotelSearch']);
				}
				if ($options['search']['StatusSearch'] != null) {
					$select->where("o.HotelOrder_Status =?", $options['search']['StatusSearch']);
				}
				if ($options['search']['RoomSearch'] != null) {
					$select->where("o.Room_Id =?", $options['search']['RoomSearch']);
				}
				if ($options['search']['DateFromSearch'] != null) {
					$select->where("o.HotelOrder_BookDate  >= '" . MyZend_Function::formatDateDMY_YMD($options['search']['DateFromSearch'], '/') . "'");
				}
				if ($options['search']['DateToSearch'] != null) {
					$select->where("o.HotelOrder_BookDate  <= '" . MyZend_Function::formatDateDMY_YMD($options['search']['DateToSearch'], '/') . "'");
				}
			}
			$select->order('o.HotelOrder_Id DESC');
			$result = $this->_db->fetchAll($select);
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('HotelOrder_Id DESC');
			$result = $this->fetchAll($select);
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			if (isset($options['search'])) {
				if ($options['search']['HotelSearch'] != null) {
					$select->where("Hotel_Id =?", $options['search']['HotelSearch']);
				}
				if ($options['search']['RoomSearch'] != null) {
					$select->where("Room_Id =?", $options['search']['RoomSearch']);
				}
				if ($options['search']['DateFromSearch'] != null) {
					$select->where("HotelOrder_BookDate  >= '" . MyZend_Function::formatDateDMY_YMD($options['search']['DateFromSearch'], '/') . "'");
				}
				if ($options['search']['DateToSearch'] != null) {
					$select->where("HotelOrder_BookDate  <= '" . MyZend_Function::formatDateDMY_YMD($options['search']['DateToSearch'], '/') . "'");
				}
			}
			$select->order('HotelOrder_Id DESC');
			$result = $this->fetchRow($select);
		}
		if ($options['task'] == 'getEdit') {
			$select = $this->_db->select()->from(array('o' => $this->_name));
			$select->joinLeft(array('h' => _PREFIX . 'hotel'), "o.Hotel_Id = h.Hotel_Id");
			$select->joinLeft(array('r' => _PREFIX . 'hotel_room'), "o.Room_Id = r.Room_Id");
			$select->joinLeft(array('pro' => _PREFIX . 'hotel_room_promotion'), "o.RoomPromotion_Id = pro.RoomPromotion_Id");
			$select->joinLeft(array('po' => _PREFIX . 'hotel_room_policy_cancel'), "o.PolicyCancel_Id = po.PolicyCancel_Id");
			$select->where('HotelOrder_Id =?', $options['Id']);
			$result = $this->_db->fetchRow($select);
		}
		return $result;
	}

	public function updateOrder($data, $id) {
		$this->_db->beginTransaction();
		try {
			$where = 'HotelOrder_Id =' . $id;
			$input = array();
			$input['HotelOrder_Status'] = $data['Status'];
			$this->_db->update(_PREFIX . 'hotel_order', $input, $where);

			if ($data['Status'] == '-1') {
				$OrderInfo = $this->getListOrder(array(
					'task' => 'getEdit',
					'Id' => $id));
				$RoomSet = new Iadm_Model_HotelRoomSet();
				$RoomSetInfo = $RoomSet->getListRoomSet(array(
					'task' => 'getWhereInRoomSetId',
					'RoomSetId' => $OrderInfo['RoomSet_Id']));
				foreach ($RoomSetInfo as $roomset) {
					$inputUpdateNumRoom = array();
					$inputUpdateNumRoom['RoomSet_AllotmentUsed'] = $roomset['RoomSet_AllotmentUsed'] - $OrderInfo['HotelOrder_Room'];
					$where = 'RoomSet_Id = ' . $roomset['RoomSet_Id'];
					$this->_db->update(_PREFIX . 'hotel_room_set', $inputUpdateNumRoom, $where);
				}
			}
			$this->_db->commit();
		} catch (Zend_Exception $e) {
			$this->_db->rollback();
		}
	}

}

?>
