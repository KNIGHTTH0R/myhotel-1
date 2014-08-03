<?php

class Hpartner_Model_HotelDistrict extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;
	protected $_db;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_district';
		$this->_primary = 'District_Id';
		$this->_db = Zend_Registry::get('connectDB');
	}

	public function getListDistrict($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select();
				$select->where('District_Status =?', 1);
				$select->order('Province_Id DESC');
				$result = $this->fetchAll($select)->toArray();
			}
			if ($options['task'] == 'getWhereProvince') {
				$select = $this->_db->select()
						->from(array('d' => $this->_name))
						->joinLeft(array('p' => _PREFIX . 'hotel_province'), 'd.Province_Id = p.Province_Id')
						->where('p.Province_Id =?', $options['provinceId']);
				$select->where('d.District_Status =?', 1);
				$select->order('d.Province_Id DESC');
				$result = $this->_db->fetchAll($select);
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

}

?>
