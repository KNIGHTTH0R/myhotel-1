<?php

class Hpartner_Model_Facilities extends MyZend_Model_DbHpartner {

	public function init() {
		parent::init();
	}

	public function getFacilities($options) {
		try {
			$result = array();
			switch ($options['task']) {
				case 'getAll':
					$select = $this->Db->select()->from(_PREFIX . 'hotel_facilities');
					$select->where('Facilities_Status =?', 1);
					$select->order('Facilities_Name ASC');
					$result = $this->Db->fetchAll($select);
					break;
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}
}

?>
