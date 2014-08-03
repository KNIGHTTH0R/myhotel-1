<?php

class Hpartner_HotelProfileController extends MyZend_Controller_AdminHpartnerAction {

    protected $ModelHotel;
    protected $Validate;

    public function init() {
        parent::init();
        $this->ModelHotel = new Hpartner_Model_Hotel();
        $this->Validate = new MyZend_Validate();
    }

    public function indexAction() {

        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost();
			//Check input valid
            foreach ($params as $v) {
                if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
                    $this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
                    break;
                }
            }
            //Filter input
            $params = MyZend_Function::filterInputPartner($params, 'array');
            //Validate not null
            if (!$this->Validate->Validate->Null->isValid($params['Hotel_Name'])) {
                $this->ErrorMsg[] = 'Vui lòng nhập Tên khách sạn.';
            }
            if (!$this->Validate->Validate->Null->isValid($params['Hotel_Address'])) {
                $this->ErrorMsg[] = 'Vui lòng nhập Địa chỉ khách sạn.';
            }
            if (!$this->Validate->Validate->Null->isValid($params['Hotel_Phone'])) {
                $this->ErrorMsg[] = 'Vui lòng nhập Số điện thoại.';
            }
            if (!$this->Validate->Validate->Digit->isValid($params['Hotel_Phone'])) {
                $this->ErrorMsg[] = 'Số điện thoại không hợp lệ.';
            }
            
            if (count($this->ErrorMsg) <= 0) {
                $input = array();
                $input['Hotel_Name'] = $params['Hotel_Name'];
                $input['Hotel_Address'] = $params['Hotel_Address'];
                $input['Hotel_Phone'] = $params['Hotel_Phone'];
                $result = $this->ModelHotel->updateHotel($input, $this->UserPartner->HotelInfo['Hotel_Id'], $this->UserPartner->UserInfo['User_Id']);
                if ($result) {
                    $this->SuccessMsg[] = $this->Msg->getMessage('update-success');
                } else {
                    $this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
                }
            }
            $this->view->assign('ErrorMsg', $this->ErrorMsg);
            $this->view->assign('SuccessMsg', $this->SuccessMsg);
        }
        //Get hotel info
        $HotelInfo = $this->ModelHotel->getHotel(array(
            'task' => 'getInfo',
			'select' => array('Hotel_Name', 'Hotel_Star', 'Hotel_Room', 'Hotel_Address', 'Hotel_Phone'),
            'User_Id' => $this->UserPartner->UserInfo['User_Id'])
        );
        //Filter output
        $HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');
		
        $this->view->assign('HotelInfo', $HotelInfo);
    }

}

?>
