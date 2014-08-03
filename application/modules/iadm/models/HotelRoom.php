<?php

class Iadm_Model_HotelRoom extends MyZend_Model_Db {

    protected $_name;
    protected $_primary;
    protected $_db;

    public function init() {

        parent::init();
        $this->_name = _PREFIX . 'hotel_room';
        $this->_primary = 'Room_Id';
        $this->_db = Zend_Registry::get('connectDB');
    }

    public function getListHotelRoom($options) {

        if ($options['task'] == 'getPage') {
            $select = $this->_db->select()->from(array('h' => $this->_name));
            $select->joinLeft(array('p' => _PREFIX . 'hotel'), "h.Hotel_Id = p.Hotel_Id");
            if (isset($options['search'])) {
                if ($options['search']['StatusSearch'] != null) {
                    $select->where("h.Room_Status =?", $options['search']['StatusSearch']);
                }
                if ($options['search']['HotelSearch'] != null) {
                    $select->where("h.Hotel_Id =?", $options['search']['HotelSearch']);
                }
            }
            $select->limitPage($options['pageCurrent'], $options['offset']);
            $select->order('Room_Id DESC');
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
                    $select->where("Room_Status =?", $options['search']['StatusSearch']);
                }
                if ($options['search']['HotelSearch'] != null) {
                    $select->where("Hotel_Id =?", $options['search']['HotelSearch']);
                }
            }
            $select->order('Room_Id DESC');
            $result = $this->fetchRow($select);
        }

        if ($options['task'] == 'getEdit') {
            $select = $this->select()->where('Room_Id =?', $options['Id']);
            $result = $this->fetchRow($select);
        }
        if ($options['task'] == 'getWhereHotel') {
            $select = $this->select()->where('Hotel_Id =?', $options['hotelId']);
            $result = $this->fetchAll($select)->toArray();
        }
		if ($options['task'] == 'getWhereListRoomId') {
            $select = $this->select()->where('Room_Id IN (?)', $options['listId']);
            $result = $this->fetchAll($select)->toArray();
        }
        return $result;
    }

    public function insertHotelRoom($params) {

        $this->insert($params);
    }

    public function updateHotelRoom($data, $id) {

        $where = 'Room_Id =' . $id;
        $db = $this->fetchRow($where);
        foreach ($data as $k => $v) {
            $db->$k = $v;
        }
        $db->save();
    }

    public function deleteHotelRoom($id) {

        $where = 'Room_Id=' . $id;
        $this->delete($where);
    }

}

?>
