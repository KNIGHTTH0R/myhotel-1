<?php

class Hpartner_HotelRoomPromotionController extends MyZend_Controller_AdminHpartnerAction {

	protected $ModelPromotion;
	protected $ModelRoom;
	protected $Validate;
	protected $ModelPolicyCancel;

	public function init() {
		parent::init();
		$this->ModelPromotion = new Hpartner_Model_HotelRoomPromotion();
		$this->ModelRoom = new Hpartner_Model_HotelRoom();
		$this->ModelPolicyCancel = new Hpartner_Model_HotelRoomPolicyCancel();
		$this->Validate = new MyZend_Validate();
	}

	public function indexAction() {

		//Get promotion info
		$Promotion = $this->ModelPromotion->getListPromotion(array(
			'task' => 'getInfo',
			'select' => array('RoomPromotion_Id'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));

		//Paginator
		$countPromotionAll = count($Promotion);
		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countPromotionAll));

		//Get list Room with paginator
		$listPromotion = $this->ModelPromotion->getListPromotion(array(
			'task' => 'getPage',
			'select' => array('RoomPromotion_RoomTypes', 'RoomPromotion_Name', 'RoomPromotion_StayDateFrom', 'RoomPromotion_StayDateTo', 'RoomPromotion_DayGetRoom', 'RoomPromotion_Id', 'RoomPromotion_Status'),
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK,
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id'],
		));
		$listPromotion = MyZend_Function::filterOutputPartner($listPromotion, 'array');

		$data = array();
		foreach ($listPromotion as $k => $v) {
			$RoomType = explode(',', $v['RoomPromotion_RoomTypes']);
			$RoomTypeList = $this->ModelRoom->getHotelRoom(array(
				'task' => 'getWhereListRoomId',
				'select' => array('Room_Name'),
				'listId' => $RoomType, 
				'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
			$RoomTypeList = MyZend_Function::filterOutputPartner($RoomTypeList, 'array');
			$data[$k] = $v;
			$data[$k]['RoomType'] = $RoomTypeList;
		}
		$listPromotion = $data;

		$this->view->assign('Promotion', $listPromotion);
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
			//Check input valid
			$field = array('RoomTypes', 'DayBookCheckbox', 'DayGetRoomCheckbox');
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
			//Validate not null
			if (!$this->Validate->Validate->Null->isValid($params['Name'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Tên khuyến mãi.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['RoomTypes'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Loại phòng.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['DiscountValue'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập giá trị giảm giá.';
			}
			
			if (count($this->ErrorMsg) <= 0) {
				$listRoom = '';
				foreach ($params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);

				$DayBook = '';
				foreach ($params['DayBookCheckbox'] as $v) {
					$DayBook .= ',' . $v;
				}
				$DayBook = substr($DayBook, 1);
				
				$DayGetRoom = '';
				foreach ($params['DayGetRoomCheckbox'] as $v) {
					$DayGetRoom .= ',' . $v;
				}
				$DayGetRoom = substr($DayGetRoom, 1);

				$input = array();
				$input['Hotel_Id'] = $this->UserPartner->HotelInfo['Hotel_Id'];
				$input['RoomPromotion_Name'] = $params['Name'];
				$input['RoomPromotion_RoomTypes'] = $listRoom;
				$input['RoomPromotion_DiscountType'] = $params['DiscountType'];
				$input['RoomPromotion_DiscountValue'] = $params['DiscountValue'];
				$input['RoomPromotion_MinRooms'] = $params['MinRooms'];
				$input['RoomPromotion_MinNightsStay'] = $params['MinNightsStay'];
				$input['RoomPromotion_MaxNightsStay'] = $params['MaxNightsStay'];
				$input['RoomPromotion_BookDateFrom'] = MyZend_Function::formatDateDMY_YMD($params['BookDateFrom'], '/');
				$input['RoomPromotion_BookDateTo'] = MyZend_Function::formatDateDMY_YMD($params['BookDateTo'], '/');
				$input['RoomPromotion_StayDateFrom'] = MyZend_Function::formatDateDMY_YMD($params['StayDateFrom'], '/');
				$input['RoomPromotion_StayDateTo'] = MyZend_Function::formatDateDMY_YMD($params['StayDateTo'], '/');
				$input['RoomPromotion_DayBook'] = $DayBook;
				$input['RoomPromotion_DayGetRoom'] = $DayGetRoom;
				$input['RoomPromotion_TimePickerBookTimeFrom'] = $params['TimePickerBookTimeFrom_Hour'] . ':' . $params['TimePickerBookTimeFrom_Minute'];
				$input['RoomPromotion_TimePickerBookTimeTo'] = $params['TimePickerBookTimeTo_Hour'] . ':' . $params['TimePickerBookTimeTo_Minute'];
				$input['PolicyCancel_Id'] = $params['PolicyCancel'];
				$input['RoomPromotion_Active'] = $params['Status'];
				$input['RoomPromotion_Status'] = 1;
				$result = $this->ModelPromotion->insertPromotion($input);
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

		//Get policy cancel
		$PolicyCancel = $this->ModelPolicyCancel->getListPolicyCancel(array(
			'task' => 'getAllWhereType',
			'select' => array('PolicyCancel_Id', 'PolicyCancel_Name', 'PolicyCancel_Body'),
			'Type' => 2));
		$PolicyCancel = MyZend_Function::filterOutputPartner($PolicyCancel, 'array');

		$this->view->assign('Room', $Room);
		$this->view->assign('PolicyCancel', $PolicyCancel);
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
			//Check input valid
			$field = array('RoomTypes', 'DayBookCheckbox', 'DayGetRoomCheckbox');
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

			//Validate not null
			if (!$this->Validate->Validate->Null->isValid($params['Name'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập Tên khuyến mãi.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['RoomTypes'])) {
				$this->ErrorMsg[] = 'Vui lòng chọn Loại phòng.';
			}
			if (!$this->Validate->Validate->Null->isValid($params['DiscountValue'])) {
				$this->ErrorMsg[] = 'Vui lòng nhập giá trị giảm giá.';
			}

			if (count($this->ErrorMsg) <= 0) {
				$listRoom = '';
				foreach ($params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);

				$DayBook = '';
				foreach ($params['DayBookCheckbox'] as $v) {
					$DayBook .= ',' . $v;
				}
				$DayBook = substr($DayBook, 1);
				
				$DayGetRoom = '';
				foreach ($params['DayGetRoomCheckbox'] as $v) {
					$DayGetRoom .= ',' . $v;
				}
				$DayGetRoom = substr($DayGetRoom, 1);

				$input = array();
				$input['RoomPromotion_Name'] = $params['Name'];
				$input['RoomPromotion_RoomTypes'] = $listRoom;
				$input['RoomPromotion_DiscountType'] = $params['DiscountType'];
				$input['RoomPromotion_DiscountValue'] = $params['DiscountValue'];
				$input['RoomPromotion_MinRooms'] = $params['MinRooms'];
				$input['RoomPromotion_MinNightsStay'] = $params['MinNightsStay'];
				$input['RoomPromotion_MaxNightsStay'] = $params['MaxNightsStay'];
				$input['RoomPromotion_BookDateFrom'] = MyZend_Function::formatDateDMY_YMD($params['BookDateFrom'], '/');
				$input['RoomPromotion_BookDateTo'] = MyZend_Function::formatDateDMY_YMD($params['BookDateTo'], '/');
				$input['RoomPromotion_StayDateFrom'] = MyZend_Function::formatDateDMY_YMD($params['StayDateFrom'], '/');
				$input['RoomPromotion_StayDateTo'] = MyZend_Function::formatDateDMY_YMD($params['StayDateTo'], '/');
				$input['RoomPromotion_DayBook'] = $DayBook;
				$input['RoomPromotion_DayGetRoom'] = $DayGetRoom;
				$input['RoomPromotion_TimePickerBookTimeFrom'] = $params['TimePickerBookTimeFrom_Hour'] . ':' . $params['TimePickerBookTimeFrom_Minute'];
				$input['RoomPromotion_TimePickerBookTimeTo'] = $params['TimePickerBookTimeTo_Hour'] . ':' . $params['TimePickerBookTimeTo_Minute'];
				$input['PolicyCancel_Id'] = $params['PolicyCancel'];
				$input['RoomPromotion_Active'] = $params['Status'];
				$result = $this->ModelPromotion->updatePromotion($input, $id, $this->UserPartner->HotelInfo['Hotel_Id']);
				if ($result) {
					$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
				} else {
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}
		//Get promotion info
		$Promotion = $this->ModelPromotion->getListPromotion(array(
			'task' => 'getInfo',
			'RoomPromotion_Id' => $id,
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		$Promotion = MyZend_Function::filterOutputPartner($Promotion, 'array');

		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id', 'Room_Name'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		//Get policy cancel
		$PolicyCancel = $this->ModelPolicyCancel->getListPolicyCancel(array(
			'task' => 'getAllWhereType',
			'select' => array('PolicyCancel_Id', 'PolicyCancel_Name', 'PolicyCancel_Body'),
			'Type' => 2));
		$PolicyCancel = MyZend_Function::filterOutputPartner($PolicyCancel, 'array');

		$this->view->assign('Promotion', $Promotion);
		$this->view->assign('Room', $Room);
		$this->view->assign('PolicyCancel', $PolicyCancel);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

}

?>
