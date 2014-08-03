<?php

class Hpartner_HotelRoomManagementController extends MyZend_Controller_AdminHpartnerAction {

	protected $ModelRoom;
	protected $ModelHotel;
	protected $Validate;
	protected $ModelRoomSet;

	public function init() {
		parent::init();
		$this->ModelRoom = new Hpartner_Model_HotelRoom();
		$this->ModelHotel = new Hpartner_Model_Hotel();
		$this->ModelRoomSet = new Hpartner_Model_HotelRoomSet();
		$this->Validate = new MyZend_Validate();
	}

	public function indexAction() {
		//Get hotel info
		$HotelInfo = $this->ModelHotel->getHotel(array(
			'task' => 'getInfo',
			'select' => array('Hotel_Name', 'Hotel_Id'),
			'User_Id' => $this->UserPartner->UserInfo['User_Id'])
		);
		//Filter output
		$HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			//Check input valid
			$field = array('Id', 'Allotment', 'Open');
			foreach ($field as $f) {
				foreach ($params[$f] as $v) {
					if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
						$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
						break;
					}
				}
			}
			//Check input valid
			foreach ($params as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Filter input
			$params = MyZend_Function::filterInputPartner($params, 'array');
			$Id = $params['Id'];
			if (count($this->ErrorMsg) <= 0) {
				foreach ($Id as $k => $v) {
					$input = array();
					$input['RoomSet_Allotment'] = $params['Allotment'][$k];
					$input['RoomSet_Open'] = (isset($params['Open'][$k]) && $params['Open'][$k] == 0) ? 0 : 1;
					$this->ModelRoomSet->updateRoomSet($input, $v, $this->UserPartner->HotelInfo['Hotel_Id']);
				}
				$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
			}
		}
		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id', 'Room_Name'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		//Filter output
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		$this->view->assign('HotelInfo', $HotelInfo);
		$this->view->assign('Room', $Room);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function searchRoomPreviewAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$params = $this->getRequest()->getPost();
		$params = MyZend_Function::filterInputPartner($params, 'array');

		$dateFrom = $params['dateFrom'];
		$dateTo = $params['dateTo'];
		$dateFromArr = explode('/', $dateFrom);
		$dateToArr = explode('/', $dateTo);
		$hotel = $params['hotel'];
		$room = $params['room'];

		$dateF = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
		$dateT = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];

		$result = $this->ModelRoomSet->getListRoomSet(array(
			'task' => 'getEditWhereBetweenDateHotelRoom',
			'select' => array('RoomSet_Date', 'RoomSet_Allotment', 'RoomSet_AllotmentUsed', 'RoomSet_Open', 'RoomSet_Id'),
			'DateFrom' => $dateF, 
			'DateTo' => $dateT, 
			'Hotel' => $this->UserPartner->HotelInfo['Hotel_Id'], 
			'Room' => $room));
		$result = MyZend_Function::filterOutputPartner($result, 'array');
		$data = array();
		for ($i = 0; $i < count($result); $i++) {
			$data[$i]['Date'] = date('d-m-Y', strtotime($result[$i]['RoomSet_Date']));
			$data[$i]['Day'] = date('w', strtotime($result[$i]['RoomSet_Date']));
			$data[$i]['Allotment'] = $result[$i]['RoomSet_Allotment'];
			$data[$i]['AllotmentUsed'] = $result[$i]['RoomSet_AllotmentUsed'];
			$data[$i]['Open'] = $result[$i]['RoomSet_Open'];
			$data[$i]['Id'] = $result[$i]['RoomSet_Id'];
		}
		echo json_encode($data);
	}

}

?>
