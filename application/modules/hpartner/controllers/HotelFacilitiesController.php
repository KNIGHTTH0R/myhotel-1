<?php

class Hpartner_HotelFacilitiesController extends MyZend_Controller_AdminHpartnerAction {

	protected $ModelHotel;
	protected $Validate;
	protected $ModelFacilities;

	public function init() {
		parent::init();
		$this->ModelHotel = new Hpartner_Model_Hotel();
		$this->ModelFacilities = new Hpartner_Model_Facilities();
		$this->Validate = new MyZend_Validate();
	}

	public function indexAction() {

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			//Check input valid
			foreach ($params['Facilities'] as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Filter input
			$params = MyZend_Function::filterInputPartner($params, 'array');
			
			if (count($this->ErrorMsg) <= 0) {
				 $Facilities = '';
				foreach ($params['Facilities'] as $v) {
					$Facilities .= ',' . $v;
				}
				$Facilities = substr($Facilities, 1);
				$input = array();
				$input['Hotel_Facilities'] = $Facilities;
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
			'select' => 'Hotel_Facilities',
			'User_Id' => $this->UserPartner->UserInfo['User_Id'])
		);
		//Get facilities info
		$Facilities = $this->ModelFacilities->getFacilities(array('task' => 'getAll'));
		//Filter output
		$HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');
		$Facilities = MyZend_Function::filterOutputPartner($Facilities, 'array');
		
		$this->view->assign('HotelInfo', $HotelInfo);
		$this->view->assign('Facilities', $Facilities);
	}

}

?>
