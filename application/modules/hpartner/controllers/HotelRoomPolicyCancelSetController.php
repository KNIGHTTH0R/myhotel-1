<?php

class Hpartner_HotelRoomPolicyCancelSetController extends MyZend_Controller_AdminHpartnerAction {

	protected $Validate;
	protected $ModelPolicyCancelSet;
	protected $ModelPolicyCancel;
	protected $ModelRoom;

	public function init() {
		parent::init();
		$this->ModelPolicyCancel = new Hpartner_Model_HotelRoomPolicyCancel();
		$this->ModelPolicyCancelSet = new Hpartner_Model_HotelRoomPolicyCancelSet();
		$this->ModelRoom = new Hpartner_Model_HotelRoom();
		$this->Validate = new MyZend_Validate();
	}

	public function indexAction() {
		$PolicyCancelSet = $this->ModelPolicyCancelSet->getListPolicyCancelSet(array(
			'task' => 'getInfo',
			'select' => array('PolicyCancelSet_Id'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		//Paginator
		$countPolicyCancelSetAll = count($PolicyCancelSet);
		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countPolicyCancelSetAll));

		//Get list Room with paginator
		$listPolicyCancelSet = $this->ModelPolicyCancelSet->getListPolicyCancelSet(array(
			'task' => 'getPage',
			'select' => array('p.PolicyCancel_Name', 'ps.PolicyCancelSet_DateFrom', 'ps.PolicyCancelSet_DateTo', 'ps.PolicyCancelSet_Id', 'ps.PolicyCancelSet_Active', 'ps.Room_Id'),
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK,
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id'],
		));
		$listPolicyCancelSet = MyZend_Function::filterOutputPartner($listPolicyCancelSet, 'array');

		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id', 'Room_Name'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');
		
		$this->view->assign('PolicyCancelSet', $listPolicyCancelSet);
		$this->view->assign('Room', $Room);
		$this->view->assign('page', $page);
	}

	public function addAction() {
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
			if (!$this->Validate->Validate->Null->isValid($params['PolicyCancel'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Chính sách hủy phòng.';
			}
			if (count($this->ErrorMsg) <= 0) {
				$listRoom = '';
				foreach ($params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);

				$dateFromArr = explode('/', $params['DateFrom']);
				$dateToArr = explode('/', $params['DateTo']);
				$input = array();
				$input['PolicyCancelSet_DateFrom'] = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
				$input['PolicyCancelSet_DateTo'] = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];
				$input['PolicyCancel_Id'] = $params['PolicyCancel'];
				$input['Room_Id'] = $listRoom;
				$input['Hotel_Id'] = $this->UserPartner->HotelInfo['Hotel_Id'];
				$input['PolicyCancelSet_Active'] = $params['Status'];
				$input['PolicyCancelSet_Status'] = 1;
				$result = $this->ModelPolicyCancelSet->insertPolicyCancelSet($input);
				if ($result) {
					$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
				} else {
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}

		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel', 
			'select' => array('Room_Id', 'Room_Name'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		$PolicyCancel = $this->ModelPolicyCancel->getListPolicyCancel(array(
			'task' => 'getAllWhereType', 
			'select' => array('PolicyCancel_Id', 'PolicyCancel_Name', 'PolicyCancel_Body'),
			'Type' => 1));
		$PolicyCancel = MyZend_Function::filterOutputPartner($PolicyCancel, 'array');

		$this->view->assign('PolicyCancel', $PolicyCancel);
		$this->view->assign('Room', $Room);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {
		$id = MyZend_Function::filterInputPartner($this->getRequest()->getParam('id'));

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
			if (!$this->Validate->Validate->Null->isValid($params['PolicyCancel'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Chính sách hủy phòng.';
			}
			if (count($this->ErrorMsg) <= 0) {
				$listRoom = '';
				foreach ($params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);
				$dateFromArr = explode('/', $params['DateFrom']);
				$dateToArr = explode('/', $params['DateTo']);
				$input = array();
				$input['PolicyCancelSet_DateFrom'] = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
				$input['PolicyCancelSet_DateTo'] = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];
				$input['PolicyCancel_Id'] = $params['PolicyCancel'];
				$input['Room_Id'] = $listRoom;
				$input['PolicyCancelSet_Active'] = $params['Status'];
				$result = $this->ModelPolicyCancelSet->updatePolicyCancelSet($input, $id);

				if ($result) {
					$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
				} else {
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}
		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id', 'Room_Name'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		$PolicyCancel = $this->ModelPolicyCancel->getListPolicyCancel(array(
			'task' => 'getAllWhereType',
			'select' => array('PolicyCancel_Id', 'PolicyCancel_Name', 'PolicyCancel_Body'),
			'Type' => 1));
		$PolicyCancel = MyZend_Function::filterOutputPartner($PolicyCancel, 'array');

		$PolicyCancelSet = $this->ModelPolicyCancelSet->getListPolicyCancelSet(array(
			'task' => 'getInfo',
			'PolicyCancelSet_Id' => $id,
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));

		$this->view->assign('PolicyCancel', $PolicyCancel);
		$this->view->assign('PolicyCancelSet', $PolicyCancelSet);
		$this->view->assign('Room', $Room);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

}

?>
