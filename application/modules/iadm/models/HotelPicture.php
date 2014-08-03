<?php

class Iadm_Model_HotelPicture extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_picture';
		$this->_primary = 'HotelPicture_Id';
	}

	public function getListHotelPicture($options) {

		if ($options['task'] == 'getWhereToken') {
			$select = $this->select()->where("HotelPicture_Token =?", $options['token']);
			$select->order('HotelPicture_Position ASC');
			$data = $this->fetchAll($select);
			$result = $data->toArray();
		}
		return $result;
	}

	public function insertHotelPicture($params) {

		$this->insert($params);
	}

	public function updateHotelPicture($data, $id) {

		$where = 'HotelPicture_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteHotelPicture($id) {

		$where = 'HotelPicture_Id=' . $id;
		$this->delete($where);
	}

}

?>
