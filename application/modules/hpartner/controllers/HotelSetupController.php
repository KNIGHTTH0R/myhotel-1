<?php

class Hpartner_HotelSetupController extends MyZend_Controller_AdminHpartnerAction {

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
            
            if (count($this->ErrorMsg) <= 0) {
                $input = array();
                $input['Hotel_InfantAge'] = $params['InfantAge'];
                $input['Hotel_ChildAgeTo'] = $params['ChildAgeTo'];
                $input['Hotel_MinGuestAge'] = $params['MinGuestAge'];
                $input['Hotel_MaxChildAge'] = $params['MaxChildAge'];
                $input['Hotel_ChildStayFree'] = $params['ChildStayFree'];
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
			'select' => array('Hotel_InfantAge', 'Hotel_ChildAgeTo', 'Hotel_MinGuestAge', 'Hotel_MaxChildAge', 'Hotel_ChildStayFree'),
            'User_Id' => $this->UserPartner->UserInfo['User_Id'])
        );
        //Filter output
        $HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');

        $this->view->assign('HotelInfo', $HotelInfo);
    }

}

?>
