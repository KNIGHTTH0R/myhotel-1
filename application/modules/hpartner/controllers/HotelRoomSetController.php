<?php

class Hpartner_HotelRoomSetController extends MyZend_Controller_AdminHpartnerAction {

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
			'select' => array('Hotel_Id', 'Hotel_Tax', 'Hotel_Name'),
			'User_Id' => $this->UserPartner->UserInfo['User_Id'])
		);
		//Filter output
		$HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getPost();
			//Check input valid
			$field = array('Date', 'SingleNetRate', 'DoubleNetRate', 'ExtraBed', 'Allotment', 'CheckchkBFIncludedCheckBox');
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
			$Date = $params['Date'];
			if (count($this->ErrorMsg) <= 0) {
				foreach ($Date as $k => $v) {
					$input = array();
					$input['RoomSet_Date'] = $params['Date'][$k];
					$input['RoomSet_SingleNetRate'] = $params['SingleNetRate'][$k];
					$input['RoomSet_DoubleNetRate'] = $params['DoubleNetRate'][$k];
					$input['RoomSet_ExtraBed'] = $params['ExtraBed'][$k];
					$input['RoomSet_Allotment'] = $params['Allotment'][$k];
					$input['RoomSet_Breakfast'] = $params['CheckchkBFIncludedCheckBox'][$k];
					$input['RoomSet_Status'] = 1;
					$input['RoomSet_ExistedPolicy'] = ($params['isSetExistedPolicy'] == 1) ? 1 : 0;
					$input['Hotel_Id'] = $this->UserPartner->HotelInfo['Hotel_Id'];
					$input['Room_Id'] = $params['Room'];
					$input['RoomSet_Tax'] = $HotelInfo['Hotel_Tax'];
					//Check Room exist
					$checkRoom = $this->ModelRoom->getHotelRoom(array(
						'task' => 'getInfoOneRoom',
						'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id'],
						'Room_Id' => $input['Room_Id']));
					if (isset($checkRoom['Room_Id']) && isset($checkRoom['Room_Id']) != '') {
						//Check exist room set
						$checkExistRow = $this->ModelRoomSet->getListRoomSet(array("task" => 'getEditWhereDateHotelRoom', 'Date' => $input['RoomSet_Date'], 'Hotel' => $input['Hotel_Id'], 'Room' => $input['Room_Id']));
						if (count($checkExistRow) <= 0) {
							$this->ModelRoomSet->insertRoomSet($input);
						} else {
							$this->ModelRoomSet->updateRoomSetWhereDate($input, $input['RoomSet_Date'], $input['Hotel_Id'], $input['Room_Id']);
						}
					}
				}
				$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
			}
		}
		//Get room info
		$Room = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id', 'Room_Name', 'Room_Price_SingleNetRate', 'Room_Price_DoubleNetRate', 'Room_Price_ExtraBed', 'Room_Allotment'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		//Filter output
		$Room = MyZend_Function::filterOutputPartner($Room, 'array');

		$this->view->assign('HotelInfo', $HotelInfo);
		$this->view->assign('Room', $Room);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function setRoomPreviewAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$params = $this->getRequest()->getPost();
		$params = MyZend_Function::filterInputPartner($params, 'array');
		
		$dateFrom = $params['dateFrom'];
		$dateTo = $params['dateTo'];
		$dateFromArr = explode('/', $dateFrom);
		$dateToArr = explode('/', $dateTo);
		$SingleNetRate = $params['SingleNetRate'];
		$DoubleNetRate = $params['DoubleNetRate'];
		$ExtraBed = $params['ExtraBed'];
		$Allotment = $params['Allotment'];
		$chkBFIncludedCheckBox = $params['chkBFIncludedCheckBox'];

		$dateFStrtoTime = strtotime($dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0]);
		$dateTStrtoTime = strtotime($dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0]);
		$dateLasStrtoTime = floor(($dateTStrtoTime - $dateFStrtoTime) / (60 * 60 * 24));

		$data = array();
		for ($i = 0; $i <= $dateLasStrtoTime; $i++) {
			$data[$i]['Date'] = date('d-m-Y', strtotime('+' . $i . ' day', $dateFStrtoTime));
			$data[$i]['DateFormat'] = date('Y-m-d', strtotime('+' . $i . ' day', $dateFStrtoTime));
			$data[$i]['Day'] = date('w', strtotime($data[$i]['Date']));
			$data[$i]['SingleNetRate'] = str_replace('.', '', $SingleNetRate);
			$data[$i]['DoubleNetRate'] = str_replace('.', '', $DoubleNetRate);
			$data[$i]['ExtraBed'] = str_replace('.', '', $ExtraBed);
			$data[$i]['chkBFIncludedCheckBox'] = $chkBFIncludedCheckBox;
			$data[$i]['Allotment'] = $Allotment;
			$data[$i]['Stt'] = $i;
		}
		$data = MyZend_Function::filterOutputPartner($data, 'array');
		echo json_encode($data);
	}

}

?>
