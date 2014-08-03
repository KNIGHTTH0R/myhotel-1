<?php

class Hpartner_Model_HotelRoomPolicyCancel extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_policy_cancel';
		$this->_primary = 'PolicyCancel_Id';
	}

	public function getListPolicyCancel($options) {
		try {
			if ($options['task'] == 'getAllWhereType') {
				$select = $this->select();
				if ($options['select']) {
					$select->from(_PREFIX . 'hotel_room_policy_cancel', $options['select']);
				} else {
					$select->from(_PREFIX . 'hotel_room_policy_cancel');
				}
				$select->where('PolicyCancel_Type =?', $options['Type']);
				$select->order('PolicyCancel_Id DESC');
				$result = $this->fetchAll($select)->toArray();
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

}

?>
