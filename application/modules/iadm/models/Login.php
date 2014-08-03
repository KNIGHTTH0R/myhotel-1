<?php
class Iadm_Model_Login extends MyZend_Model_Db {
    protected $_name;
    protected $_primary;
    protected $_result;

    public function init() {
        parent::init();
        $this->_name = _PREFIX . 'admin_access';
        $this->_primary = 'Id';
    }
    
    public function getAdUser($options) {
        if($options['task'] == 'checkLogin') {
            $select = $this->select()
                    ->where('Username =?', $options['Username'])
                    ->where('Password =?', $options['Password'])
                    ->where('Status =?', 1);
        }
        if($options['task'] == 'checkEmailExist') {
            $select = $this->select()
                    ->where('Username =?', $options['Username'])
                    ->where('Email =?', $options['Email'])
                    ->where('Status =?', 1);
        }
        
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function updatePass($options) {
        $where = 'Username = "' . $options['Username'] . '"';
        $db = $this->fetchRow($where);
        $db->Password = $options['Password'];
        $db->save();
    }
}
?>
