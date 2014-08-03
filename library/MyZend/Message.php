<?php

class MyZend_Message {
    
    public static function getMessage($name) {
        $Message = new Zend_Config(include APPLICATION_PATH . '/../config/config.message.php');
        if (isset($Message->$name) && $Message->$name != '') {
            return $Message->$name;
        } else {
            return 'Not Message';
        }
    } 
}
?>
