<?php

class Hpartner_Model_HotelRoomView extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_view';
		$this->_primary = 'RoomView_Id';
	}

	public function getListRoomView($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select()->where('RoomView_Status =?', 1);
				$select->order('RoomView_Id DESC');
				$result = $this->fetchAll($select)->toArray();
			}
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
		return $result;
	}

}

?>
