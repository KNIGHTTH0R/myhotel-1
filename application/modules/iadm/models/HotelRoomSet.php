<?php

class Iadm_Model_HotelRoomSet extends MyZend_Model_Db {

    protected $_name;
    protected $_primary;

    public function init() {

        parent::init();
        $this->_name = _PREFIX . 'hotel_room_set';
        $this->_primary = 'RoomSet_Id';
    }

    public function getListRoomSet($options) {

        if ($options['task'] == 'getPage') {
            $select = $this->select();
            $select->limitPage($options['pageCurrent'], $options['offset']);
            $select->order('RoomSet_Date DESC');
            $result = $this->fetchAll($select)->toArray();
        }

        if ($options['task'] == 'getAll') {
            $select = $this->select();
            $select->order('RoomSet_Date DESC');
            $result = $this->fetchAll($select)->toArray();
        }

        if ($options['task'] == 'getnumAll') {
            $select = $this->select()->from($this->_name, "COUNT(*) as count");
            $select->order('RoomSet_Date DESC');
            $result = $this->fetchRow($select);
        }

        if ($options['task'] == 'getEdit') {
            $select = $this->select()->where('RoomSet_Id =?', $options['Id']);
            $result = $this->fetchRow($select);
        }
        if ($options['task'] == 'getEditWhereDateHotelRoom') {
            $select = $this->select();
            $select->where('RoomSet_Date =?', $options['Date']);
            $select->where('Hotel_Id =?', $options['Hotel']);
            $select->where('Room_Id =?', $options['Room']);
            $result = $this->fetchAll($select)->toArray();
        }
        if ($options['task'] == 'getEditWhereBetweenDateHotelRoom') {
            $select = $this->select();
            $select->where('RoomSet_Date >= "' . $options['DateFrom'] . '"');
            $select->where('RoomSet_Date <= "' . $options['DateTo'] . '"');
            $select->where('Hotel_Id =?', $options['Hotel']);
            $select->where('Room_Id =?', $options['Room']);
            $select->order('RoomSet_Date ASC');
            $result = $this->fetchAll($select)->toArray();
        }

        if ($options['task'] == 'getWhereInRoomSetId') {
            $select = $this->select();
            $select->where('RoomSet_Id IN (' . $options['RoomSetId'] . ')');
            $result = $this->fetchAll($select)->toArray();
        }

        return $result;
    }

    public function insertRoomSet($params) {

        $this->insert($params);
    }

    public function updateRoomSet($data, $id) {
        $where = 'RoomSet_Id = ' . $id;
        $db = $this->fetchRow($where);
        foreach ($data as $k => $v) {
            $db->$k = $v;
        }
        $db->save();
    }
    
    public function updateNumRoomSet($num, $id) {
        
    }

    public function updateRoomSetWhereDate($data, $date, $hotel, $room) {
        $where = 'RoomSet_Date = "' . $date . '" AND Hotel_id = ' . $hotel . ' AND Room_Id = ' . $room;
        $db = $this->fetchRow($where);
        foreach ($data as $k => $v) {
            $db->$k = $v;
        }
        $db->save();
    }

    public function removeRoomSetWhereDateHotelRoom($date, $hotel, $room) {
        $where = 'RoomSet_Date = "' . $date . '" AND Hotel_Id = ' . $hotel . ' AND Room_Id = ' . $room;
        $this->delete($where);
    }

    public function deleteRoomSet($id) {

        $where = 'RoomSet_Id=' . $id;
        $this->delete($where);
    }

}

?>
