<?php

class Iadm_Model_HotelOrderDetail extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_order_detail';
		$this->_primary = 'OrderDetail_Id';
	}

	public function getListOrderDetail($options) {

		if ($options['task'] == 'getWhereOrder') {
			$select = $this->select()->where('HotelOrder_Id =?', $options['OrderId']);
			$select->order('OrderDetail_Date ASC');
			$result = $this->fetchAll($select)->toArray();
		}
		return $result;
	}
}

?>
