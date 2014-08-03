<?php

class Hpartner_Model_UploadImage extends MyZend_Model_DbHpartner {

	protected $_name;
	protected $_primary;

	public function init() {

		parent::init();
		$this->_name = _PREFIX . 'image_uploads';
		$this->_primary = 'upload_id';
	}

	public function getListUploadImage($options) {
		try {
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
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
	}

	public function insertUploadImage($params) {
		try {
			$this->insert($params);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

	public function deleteUploadImage($id, $token = null) {
		try {
			$where = 'upload_id =' . $id;
			if ($token != null) {
				$where .= ' AND token = "' . $token . '"';
			}
			$this->delete($where);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}

	public function deleteUploadImageWhereToken($token) {
		try {
			$where = 'token ="' . $token . '"';
			$this->delete($where);
		} catch (Zend_Exception $e) {
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			return false;
		}
		return true;
	}
}

?>
