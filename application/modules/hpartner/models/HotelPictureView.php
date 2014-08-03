<?php

class Hpartner_Model_HotelPictureView extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_picture_view';
		$this->_primary = 'PictureView_Id';
	}

	public function getListPictureView($options) {
		try {
			if ($options['task'] == 'getAll') {
				$select = $this->select()->where('PictureView_Status =?', 1);
				$select->order('PictureView_Id DESC');
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
