<?php

class Iadm_Model_Hotel extends MyZend_Model_Db {

    protected $_name;
    protected $_primary;
    protected $_db;

    public function init() {

        parent::init();
        $this->_name = _PREFIX . 'hotel';
        $this->_primary = 'Hotel_Id';
        $this->_db = Zend_Registry::get('connectDB');
    }

    public function getListHotel($options) {

        if ($options['task'] == 'getPage') {
            $select = $this->_db->select()->from(array('h' => $this->_name));
            if (isset($options['search'])) {
                if ($options['search']['StatusSearch'] != null) {
                    $select->where("h.Hotel_Status =?", $options['search']['StatusSearch']);
                }
                if ($options['search']['StarSearch'] != null) {
                    $select->where("h.Hotel_Star =?", $options['search']['StarSearch']);
                }
                if ($options['search']['ProvinceSearch'] != null) {
                    $select->where("h.Province_Id =?", $options['search']['ProvinceSearch']);
                }
                if ($options['search']['PartnerSearch'] != null) {
                    $select->where("h.User_Id =?", $options['search']['PartnerSearch']);
                }
            }
            $select->joinLeft(array('p' => _PREFIX . 'user'), "h.User_Id = p.User_Id");
            $select->limitPage($options['pageCurrent'], $options['offset']);
            $select->order('Hotel_Id DESC');
            $result = $this->_db->fetchAll($select);
        }

        if ($options['task'] == 'getAll') {
            $select = $this->select();
            $select->order('Hotel_Id DESC');
            $result = $this->fetchAll($select)->toArray();
        }

        if ($options['task'] == 'getnumAll') {
            $select = $this->select()->from($this->_name, "COUNT(*) as count");
            if (isset($options['search'])) {
                if ($options['search']['StatusSearch'] != null) {
                    $select->where("Hotel_Status =?", $options['search']['StatusSearch']);
                }
                if ($options['search']['StarSearch'] != null) {
                    $select->where("Hotel_Star =?", $options['search']['StarSearch']);
                }
                if ($options['search']['ProvinceSearch'] != null) {
                    $select->where("Province_Id =?", $options['search']['ProvinceSearch']);
                }
                if ($options['search']['PartnerSearch'] != null) {
                    $select->where("User_Id =?", $options['search']['PartnerSearch']);
                }
            }
            $select->order('Hotel_Id DESC');
            $result = $this->fetchRow($select);
        }

        if ($options['task'] == 'checkEmail') {
            $select = $this->select()->from($this->_name, "COUNT(*) as count");
            $select->where('Hotel_Email =?', $options['email']);
            $result = $this->fetchRow($select);
        }
		
		if ($options['task'] == 'getcountAll') {
			$select = $this->select()->from($this->_name, "COUNT(*) as count");
			$result = $this->fetchRow($select);
		}

        if ($options['task'] == 'getEdit') {
            $select = $this->select()->where('Hotel_Id =?', $options['Id']);
            $result = $this->fetchRow($select);
        }
		
		if ($options['task'] == 'whereUser') {
            $select = $this->select()->where('User_Id =?', $options['User_Id']);
            $result = $this->fetchRow($select);
        }
        return $result;
    }

    public function insertHotel($params) {

        $this->insert($params);
    }

    public function updateHotel($data, $id) {

        $where = 'Hotel_Id =' . $id;
        $db = $this->fetchRow($where);
        foreach ($data as $k => $v) {
            $db->$k = $v;
        }
        $db->save();
    }

    public function deleteHotel($id) {

        $where = 'Hotel_Id=' . $id;
        $this->delete($where);
    }

}

?>
