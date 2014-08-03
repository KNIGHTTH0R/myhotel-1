<?php

class MyZend_Model_Db extends Zend_Db_Table_Abstract {

	protected $Log;

	public function __construct($config = array()) {
		parent::__construct($config);
		$this->Log = Zend_Registry::get('Log');
	}

	public function updateStatus($id, $table, $statusName = null, $idName = null) {
		try {
			$db = Zend_Registry::get('connectDB');
			$idName = ($idName != null) ? $idName : "Id";
			$statusName = ($statusName != null) ? $statusName : "Status";

			$checkNowStatus = $db->select()->from($table)->where($idName . ' =?', $id);
			$result = $db->fetchRow($checkNowStatus);
			$db2 = Zend_Registry::get('connectDB');
			$where = $idName . '=' . $id;
			if ($result[$statusName] == '1') {
				$data = array($statusName => 0);
				$statusCurrent = 0;
			} else {
				$data = array($statusName => 1);
				$statusCurrent = 1;
			}
			$db2->update($table, $data, $where);
			return $statusCurrent;
		} catch (Exception $e) {
			$this->Log->log($e->getMessage(), Zend_Log::ERR);
		}
	}

}

?>
