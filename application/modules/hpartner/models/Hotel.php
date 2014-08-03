<?php

class Hpartner_Model_Hotel extends MyZend_Model_DbHpartner {

	public function init() {
		parent::init();
		$this->_name = _PREFIX . 'hotel';
		$this->_primary = 'Hotel_Id';
	}

	public function getHotel($options) {
		try {
			$result = array();
			switch ($options['task']) {
				case 'getInfo':
					$select = $this->Db->select();
					if ($options['select']) {
						$select->from(_PREFIX . 'hotel', $options['select']);
					} else {
						$select->from(_PREFIX . 'hotel');
					}
					$select->where('User_Id =?', $options['User_Id']);
					$select->where('Hotel_Status =?', 1);
					$result = $this->Db->fetchRow($select);
					break;
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function updateHotel($input, $Hotel_Id, $Partner_Id) {
		try {
			$where = 'Hotel_Id = ' . $Hotel_Id . ' AND User_Id = ' . $Partner_Id;
			$this->Db->update(_PREFIX . 'hotel', $input, $where);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

	public function insertHotel($params) {
		try {
			$this->insert($params);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

}

?>
