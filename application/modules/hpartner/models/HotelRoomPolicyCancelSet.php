<?php

class Hpartner_Model_HotelRoomPolicyCancelSet extends MyZend_Model_DbHpartner {

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
		try {
			if ($options['task'] == 'getPage') {
				$select = $this->_db->select();
				if ($options['select']) {
					$select->from(array('ps' => $this->_name), $options['select']);
					$select->joinLeft(array('p' => _PREFIX . 'hotel_room_policy_cancel'), "ps.PolicyCancel_Id = p.PolicyCancel_Id", array());
				} else {
					$select->from(array('ps' => $this->_name));
					$select->joinLeft(array('p' => _PREFIX . 'hotel_room_policy_cancel'), "ps.PolicyCancel_Id = p.PolicyCancel_Id");
				}
				$select->where('ps.Hotel_Id =?', $options['Hotel_Id']);
				$select->where('ps.PolicyCancelSet_Status =?', 1);
				$select->limitPage($options['pageCurrent'], $options['offset']);
				$select->order('ps.PolicyCancelSet_Id DESC');
				$result = $this->_db->fetchAll($select);
			}
			if ($options['task'] == 'getInfo') {
				$select = $this->select();
				if ($options['select']) {
					$select->from(_PREFIX . 'hotel_room_policy_cancel_set', $options['select']);
				} else {
					$select->from(_PREFIX . 'hotel_room_policy_cancel_set');
				}
				$select->where('Hotel_Id =?', $options['Hotel_Id']);
				$select->where('PolicyCancelSet_Status =?', 1);
				//get edit
				if (isset($options['PolicyCancelSet_Id'])) {
					$select->where('PolicyCancelSet_Id =?', $options['PolicyCancelSet_Id']);
					$result = $this->fetchRow($select);
					//get list	
				} else {
					$result = $this->fetchAll($select)->toArray();
				}
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function insertPolicyCancelSet($params) {
		try {
			$this->insert($params);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

	public function updatePolicyCancelSet($data, $id) {
		try {
			$where = 'PolicyCancelSet_Id =' . $id;
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
