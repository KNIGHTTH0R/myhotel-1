<?php

class Iadm_Model_HotelPictureView extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'hotel_picture_view';
		$this->_primary = 'PictureView_Id';
	}

	public function getListPictureView($options) {

		if ($options['task'] == 'getPage') {
			$select = $this->select();
			$select->limitPage($options['pageCurrent'], $options['offset']);
			$select->order('PictureView_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$select->order('PictureView_Id DESC');
			$result = $this->fetchAll($select)->toArray();
		}

		if ($options['task'] == 'getnumAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$select->order('PictureView_Id DESC');
			$result = $this->fetchRow($select);
		}


		if ($options['task'] == 'getEdit') {
			$select = $this->select()->where('PictureView_Id =?', $options['Id']);
			$result = $this->fetchRow($select);
		}
		return $result;
	}

	public function insertPictureView($params) {

		$this->insert($params);
	}

	public function updatePictureView($data, $id) {

		$where = 'PictureView_Id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deletePictureView($id) {

		$where = 'PictureView_Id=' . $id;
		$this->delete($where);
	}

}

?>
