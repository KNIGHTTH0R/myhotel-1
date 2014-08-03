<?php

class Iadm_Model_UploadImage extends MyZend_Model_Db {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'image_uploads';
		$this->_primary = 'upload_id';
	}

	public function getListUploadImage($options) {

		if ($options['task'] == 'getWhereToken') {
			$select = $this->select()->where("token =?", $options['token']);
			$select->order('upload_id DESC');
			$data = $this->fetchAll($select);
			$result = $data->toArray();
		}
		
		if ($options['task'] == 'getAll') {
			$select = $this->select();
			$data = $this->fetchAll($select);
			$result = $data->toArray();
		}
		return $result;
	}

	public function insertUploadImage($params) {

		$this->insert($params);
	}

	public function updateUploadImage($data, $id) {

		$where = 'upload_id =' . $id;
		$db = $this->fetchRow($where);
		foreach ($data as $k => $v) {
			$db->$k = $v;
		}
		$db->save();
	}

	public function deleteUploadImage($id) {

		$where = 'upload_id =' . $id;
		$this->delete($where);
	}

	public function deleteUploadImageWhereToken($token) {

		$where = 'token ="' . $token . '"';
		$this->delete($where);
	}

	public function deleteUploadImageTemp($time) {

		$where = 'created <' . $time;
		$this->delete($where);
	}

}

?>
