<?php

class Hpartner_Model_OrderDetail extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_order_detail';
		$this->_primary = 'OrderDetail_Id';
	}

	public function getListOrderDetail($options) {
		try {
			if ($options['task'] == 'getWhereOrder') {
				$select = $this->select()->where('HotelOrder_Id =?', $options['OrderId']);
				$select->order('OrderDetail_Date ASC');
				$result = $this->fetchAll($select)->toArray();
			}
			return $result;
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
		return $result;
	}

}

?>
