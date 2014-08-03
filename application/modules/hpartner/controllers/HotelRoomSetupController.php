<?php

class Hpartner_HotelRoomSetupController extends MyZend_Controller_AdminHpartnerAction {

	protected $Validate;
	protected $ModelRoom;
	protected $ModelHotel;

	public function init() {
		parent::init();
		$this->ModelRoom = new Hpartner_Model_HotelRoom();
		$this->ModelHotel = new Hpartner_Model_Hotel();
		$this->Validate = new MyZend_Validate();
	}

	public function indexAction() {

		//Get hotel info
		$HotelInfo = $this->ModelHotel->getHotel(array(
			'task' => 'getInfo',
			'User_Id' => $this->UserPartner->UserInfo['User_Id'])
		);

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			//Check input valid
			$field = array('SingleNetRate', 'DoubleNetRate', 'ExtraBed', 'Allotment', 'Breakfast');
			foreach ($field as $f) {
				foreach ($params[$f] as $v) {
					if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
						$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
						break;
					}
				}
			}
			//Filter input
			$params = MyZend_Function::filterInputPartner($params, 'array');

			if (count($this->ErrorMsg) <= 0) {
				foreach ($params['id'] as $v) {
					$input = array();
					$input['Room_Price_SingleNetRate'] = MyZend_Function::formatPriceRemoveCommas($params['SingleNetRate'][$v]);
					$input['Room_Price_DoubleNetRate'] = MyZend_Function::formatPriceRemoveCommas($params['DoubleNetRate'][$v]);
					$input['Room_Price_ExtraBed'] = MyZend_Function::formatPriceRemoveCommas($params['ExtraBed'][$v]);
					$input['Room_Allotment'] = $params['Allotment'][$v];
					$input['Room_Breakfast'] = ($params['Breakfast'][$v] == 1) ? 1 : 0;
					$result = $this->ModelRoom->updateHotelRoom($input, $v, $this->UserPartner->HotelInfo['Hotel_Id']);
					if ($result) {
						$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
					} else {
						$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
					}
				}
			}
		}
		//Get Room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Name', 'Room_Price_SingleNetRate', 'Room_Price_DoubleNetRate', 'Room_Price_ExtraBed', 'Room_Id', 'Room_Breakfast', 'Room_Status', 'Room_Allotment'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		//Filter output
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		$this->view->assign('Room', $Room);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('HotelInfo', $HotelInfo);
	}

}

?>
