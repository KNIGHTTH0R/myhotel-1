<?php

class Hpartner_HotelRoomController extends MyZend_Controller_AdminHpartnerAction {

	protected $ModelHotel;
	protected $Validate;
	protected $ModelRoom;
	protected $ModelRoomView;
	protected $ModelRoomBed;
	protected $ModelRoomFacilities;

	public function init() {
		parent::init();
		$this->ModelHotel = new Hpartner_Model_Hotel();
		$this->ModelRoom = new Hpartner_Model_HotelRoom();
		$this->ModelRoomView = new Hpartner_Model_HotelRoomView();
		$this->ModelRoomBed = new Hpartner_Model_HotelRoomBed();
		$this->ModelRoomFacilities = new Hpartner_Model_HotelRoomFacilities();
		$this->Validate = new MyZend_Validate();
	}

	public function indexAction() {
		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		//Filter output
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		//Paginator
		$countHotelRoomAll = count($Room);
		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countHotelRoomAll));

		//Get list Room with paginator
		$listRoom = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getPage',
			'select' => array('h.Room_MaxOccupancy', 'h.Room_Code', 'h.Room_Size', 'h.Room_Name', 'h.Room_Date_create', 'h.Room_Id', 'h.Room_Status', 'p.Hotel_Id'),
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK,
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id'],
		));
		$listRoom = MyZend_Function::filterOutputPartner($listRoom, 'array');

		$this->view->assign('Room', $listRoom);
		$this->view->assign('page', $page);
	}

	public function editAction() {
		$id = MyZend_Function::filterInputPartner($this->getRequest()->getParam('id'));
		//Update Room Info
		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			//Check input valid
			foreach ($params as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Check input Facilities
			foreach ($params['Facilities'] as $v) {
				if ($this->Validate->Validate->InputInvalid->isValid(strtolower($v))) {
					$this->ErrorMsg[] = $this->Msg->getMessage('input-invalid');
					break;
				}
			}
			//Check input Bed
			foreach ($params['Bed'] as $v) {
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
				
				$Bed = '';
				foreach ($params['Bed'] as $v) {
					$Bed .= ',' . $v;
				}
				$Bed = substr($Bed, 1);

				$input = array();
				$input['Room_Size'] = $params['Size'];
				$input['RoomView_Id'] = $params['RoomView'];
				$input['Room_MaxOccupancy'] = $params['MaxOccupancy'];
				$input['Room_Facilities'] = $Facilities;
				$input['Room_Bed'] = $Bed;
				$result = $this->ModelRoom->updateHotelRoom($input, $id, $this->UserPartner->HotelInfo['Hotel_Id']);
				if ($result) {
					$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
				} else {
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}
		//Get room info
		$HotelRoom = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoOneRoom',
			'select' => array('r.Room_Code', 'r.Room_Name', 'r.Room_Description', 'r.Room_Status', 'r.Room_Size', 'r.RoomView_Id', 'r.Room_MaxOccupancy', 'r.Room_Bed', 'r.Room_Facilities'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id'],
			'Room_Id' => $id));
		//Filter output
		$HotelRoom = MyZend_Function::filterOutputPartner($HotelRoom, 'array');
		//Huong phong
		$RoomView = $this->ModelRoomView->getListRoomView(array('task' => 'getAll'));
		$RoomView = MyZend_Function::filterOutputPartner($RoomView, 'array');
		//Loai giuong
		$RoomBed = $this->ModelRoomBed->getListRoomBed(array('task' => 'getAll'));
		$RoomBed = MyZend_Function::filterOutputPartner($RoomBed, 'array');
		//Tien ich
		$RoomFacilities = $this->ModelRoomFacilities->getListRoomFacilities(array('task' => 'getAll'));
		$RoomFacilities = MyZend_Function::filterOutputPartner($RoomFacilities, 'array');

		$this->view->assign('HotelRoom', $HotelRoom);
		$this->view->assign('RoomView', $RoomView);
		$this->view->assign('RoomBed', $RoomBed);
		$this->view->assign('RoomFacilities', $RoomFacilities);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
	}

}

?>
