<?php

class Iadm_IndexController extends MyZend_Controller_AdminAction {

    public function indexAction() {
        $partner = new Iadm_Model_HotelPartner();
		$hotel = new Iadm_Model_Hotel();
        $this->view->assign('partner', $partner->getListPartner(array('task' => 'getcountAll')));
		$this->view->assign('hotel', $hotel->getListHotel(array('task' => 'getcountAll')));
    }

}

?>
