<?php

class Iadm_HotelRoomManagementController extends MyZend_Controller_AdminAction {

	protected $Hotel;
	protected $Room;
	protected $RoomSet;

	public function init() {
		parent::init();
		$this->Hotel = new Iadm_Model_Hotel();
		$this->Room = new Iadm_Model_HotelRoom();
		$this->RoomSet = new Iadm_Model_HotelRoomSet();
		$this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
	}

	public function indexAction() {
		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			$Id = $params['Id'];

			foreach ($Id as $k => $v) {
				$input = array();
				$input['RoomSet_Allotment'] = $params['Allotment'][$k];
				$input['RoomSet_Open'] = (isset($params['Open'][$k]) && $params['Open'][$k] == 0) ? 0 : 1;
				$input = MyZend_Function::filterInput($input, 'array');
				$this->RoomSet->updateRoomSet($input, $v);
			}
			$this->SuccessMsg[] = $this->Msg['AddSuccess'];
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
	}

	public function searchRoomPreviewAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getPost();

		$dateFrom = $params['dateFrom'];
		$dateTo = $params['dateTo'];
		$dateFromArr = explode('/', $dateFrom);
		$dateToArr = explode('/', $dateTo);
		$hotel = $params['hotel'];
		$room = $params['room'];

		$dateF = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
		$dateT = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];

		$result =  MyZend_Function::filterOutput($this->RoomSet->getListRoomSet(array("task" => 'getEditWhereBetweenDateHotelRoom', 'DateFrom' => $dateF, 'DateTo' => $dateT, 'Hotel' => $hotel, 'Room' => $room)), 'array');

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
