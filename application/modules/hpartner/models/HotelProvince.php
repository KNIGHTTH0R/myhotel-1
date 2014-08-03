<?php

class Hpartner_Model_HotelProvince extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_province';
		$this->_primary = 'Province_Id';
	}

	public function getListProvince($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select();
				$select->where('Province_Status =?', 1);
				$select->order('Province_Id DESC');
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
