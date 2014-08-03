<?php

class Hpartner_Model_HotelRoomFacilities extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_room_facilities';
		$this->_primary = 'Facilities_Id';
	}

	public function getListRoomFacilities($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select()->where('Facilities_Status =?', 1);
				$select->order('Facilities_Id DESC');
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
