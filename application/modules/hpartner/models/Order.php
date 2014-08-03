<?php

class Hpartner_Model_Order extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_order';
		$this->_primary = 'HotelOrder_Id';
	}

	public function getListOrder($options) {
		try {
			if ($options['task'] == 'getList') {
				$select = $this->Db->select();
				if ($options['select']) {
					$select->from(array('o' => $this->_name), $options['select']);
					$select->joinLeft(array('h' => _PREFIX . 'hotel'), "o.Hotel_Id = h.Hotel_Id", array());
				} else {
					$select->from(array('o' => $this->_name));
					$select->joinLeft(array('h' => _PREFIX . 'hotel'), "o.Hotel_Id = h.Hotel_Id");
				}
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
				$result = $this->Db->fetchAll($select);
			}

			if ($options['task'] == 'getEdit') {
				$select = $this->Db->select()->from(array('o' => $this->_name));
				$select->joinLeft(array('h' => _PREFIX . 'hotel'), "o.Hotel_Id = h.Hotel_Id");
				$select->joinLeft(array('r' => _PREFIX . 'hotel_room'), "o.Room_Id = r.Room_Id");
				$select->joinLeft(array('pro' => _PREFIX . 'hotel_room_promotion'), "o.RoomPromotion_Id = pro.RoomPromotion_Id");
				$select->joinLeft(array('po' => _PREFIX . 'hotel_room_policy_cancel'), "o.PolicyCancel_Id = po.PolicyCancel_Id");
				$select->where('o.HotelOrder_Id =?', $options['Id']);
				$select->where("o.Hotel_Id =?", $options['Hotel']);
				$result = $this->Db->fetchRow($select);
			}
			
			if ($options['task'] == 'getExport') {
				$select = $this->Db->select();
				$select->from(array('o' => $this->_name));
				$select->joinLeft(array('h' => _PREFIX . 'hotel'), "o.Hotel_Id = h.Hotel_Id");
				$select->joinLeft(array('r' => _PREFIX . 'hotel_room'), "o.Room_Id = r.Room_Id");
				$select->joinLeft(array('pro' => _PREFIX . 'hotel_room_promotion'), "o.RoomPromotion_Id = pro.RoomPromotion_Id");
				$select->joinLeft(array('po' => _PREFIX . 'hotel_room_policy_cancel'), "o.PolicyCancel_Id = po.PolicyCancel_Id");
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
				$result = $this->Db->fetchAll($select);
			}
			
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function updateOrder($data, $id, $hotel) {
		$this->Db->beginTransaction();
		try {
			//Cap nhat Order
			$where = 'HotelOrder_Id =' . $id . ' AND Hotel_Id = ' . $hotel;
			$input = array();
			$input['HotelOrder_Status'] = $data['Status'];
			$this->Db->update(_PREFIX . 'hotel_order', $input, $where);
			//Huy don hang, cap nhat lai so luong phong
			if ($data['Status'] == '-1') {
				$OrderInfo = $this->getListOrder(array(
					'task' => 'getEdit', 
					'Id' => $id, 
					'Hotel' => $hotel));
				$ModelRoomSet = new Hpartner_Model_HotelRoomSet();
				$RoomSetInfo = $ModelRoomSet->getListRoomSet(array(
					'task' => 'getWhereInRoomSetId',
					'select' => array('RoomSet_AllotmentUsed', 'RoomSet_Id'),
					'RoomSetId' => $OrderInfo['RoomSet_Id'], 
					'Hotel' => $hotel));
				foreach ($RoomSetInfo as $roomset) {
					$inputUpdateNumRoom = array();
					$inputUpdateNumRoom['RoomSet_AllotmentUsed'] = $roomset['RoomSet_AllotmentUsed'] - $OrderInfo['HotelOrder_Room'];
					$where = 'RoomSet_Id = ' . $roomset['RoomSet_Id'] . ' AND Hotel_Id = ' . $hotel;
					$this->Db->update(_PREFIX . 'hotel_room_set', $inputUpdateNumRoom, $where);
				}
			}
			$this->Db->commit();
		} catch (Zend_Exception $e) {
			$this->Db->rollback();
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}
}

?>
