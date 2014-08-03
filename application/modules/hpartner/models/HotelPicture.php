<?php

class Hpartner_Model_HotelPicture extends MyZend_Model_DbHpartner {

    protected $_name;
    protected $_primary;

    public function init() {

        parent::init();
        $this->_name = _PREFIX . 'hotel_picture';
        $this->_primary = 'HotelPicture_Id';
    }

    public function getListHotelPicture($options) {
        try {
            if ($options['task'] == 'getWhereToken') {
                $select = $this->select()->where("HotelPicture_Token =?", $options['token']);
                $select->order('HotelPicture_Position ASC');
                $data = $this->fetchAll($select);
                $result = $data->toArray();
            }
        } catch (Zend_Exception $e) {
            Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
            throw new Exception;
        }
        return $result;
    }

    public function insertHotelPicture($params) {
        try {
            $this->insert($params);
        } catch (Zend_Exception $e) {
            Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
            return false;
        }
        return true;
    }

    public function updateHotelPicture($data, $id, $token = null) {
        try {
            $where = 'HotelPicture_Id =' . $id . ' AND HotelPicture_Token = "' . $token . '"';
            $db = $this->fetchRow($where);
            foreach ($data as $k => $v) {
                $db->$k = $v;
            }
            $db->save();
        } catch (Zend_Exception $e) {
            Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
            return false;
        }
        return true;
    }

    public function deleteHotelPicture($id, $token = null) {
        try {
            $where = 'HotelPicture_Id = ' . $id . ' AND HotelPicture_Token = "' . $token . '"';
            $this->delete($where);
        } catch (Zend_Exception $e) {
            Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
            return false;
        }
        return true;
    }

}

?>
