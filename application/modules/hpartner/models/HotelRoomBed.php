<?php

class Hpartner_Model_HotelRoomBed extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_bed';
		$this->_primary = 'Room_Bed_Id';
	}

	public function getListRoomBed($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select()->where('Room_Bed_Status =?', 1);
				$select->order('Room_Bed_Id DESC');
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
