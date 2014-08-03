<?php

class Iadm_Model_HotelRoomPolicyCancel extends MyZend_Model_Db {

    protected $_name;
    protected $_primary;

    public function init() {

        parent::init();
        $this->_name = _PREFIX . 'hotel_room_policy_cancel';
        $this->_primary = 'PolicyCancel_Id';
    }

    public function getListPolicyCancel($options) {

        if ($options['task'] == 'getPage') {
            $select = $this->select();
            $select->limitPage($options['pageCurrent'], $options['offset']);
            $select->order('PolicyCancel_Id DESC');
            $result = $this->fetchAll($select)->toArray();
        }

        if ($options['task'] == 'getAll') {
            $select = $this->select();
            $select->order('PolicyCancel_Id DESC');
            $result = $this->fetchAll($select)->toArray();
        }
        
        if ($options['task'] == 'getAllWhereType') {
            $select = $this->select()->where('PolicyCancel_Type =?', $options['Type']);
            $select->order('PolicyCancel_Id DESC');
            $result = $this->fetchAll($select)->toArray();
        }

        if ($options['task'] == 'getnumAll') {
            $select = $this->select()->from($this->_name, "COUNT(*) as count");
            $select->order('PolicyCancel_Id DESC');
            $result = $this->fetchRow($select);
        }


        if ($options['task'] == 'getEdit') {
            $select = $this->select()->where('PolicyCancel_Id =?', $options['Id']);
            $result = $this->fetchRow($select);
        }
        return $result;
    }

    public function insertPolicyCancel($params) {

        $this->insert($params);
    }

    public function updatePolicyCancel($data, $id) {

        $where = 'PolicyCancel_Id =' . $id;
        $db = $this->fetchRow($where);
        foreach ($data as $k => $v) {
            $db->$k = $v;
        }
        $db->save();
    }

    public function deletePolicyCancel($id) {

        $where = 'PolicyCancel_Id=' . $id;
        $this->delete($where);
    }

}

?>
