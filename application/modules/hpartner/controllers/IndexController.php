<?php
class Hpartner_IndexController extends MyZend_Controller_AdminHpartnerAction {
    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        $this->_redirect('/hpartner/hotel-profile');
    }
}
?>
