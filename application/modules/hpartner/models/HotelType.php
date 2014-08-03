<?php

class Hpartner_Model_HotelType extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_type';
		$this->_primary = 'HotelType_Id';
	}

	public function getListHotelType($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select();
				$select->where('HotelType_Status =?', 1);
				$select->order('HotelType_Id DESC');
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
