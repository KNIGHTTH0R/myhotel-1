<?php

class MyZend_Model_DbHpartner extends Zend_Db_Table_Abstract {

    protected $Log;
	protected $Db;
	
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->Log = Zend_Registry::get('Log');
		$this->Db = Zend_Registry::get('connectDB');
    }

}

?>
