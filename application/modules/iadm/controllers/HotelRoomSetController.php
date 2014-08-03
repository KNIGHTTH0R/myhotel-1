<?php

class Iadm_HotelRoomSetController extends MyZend_Controller_AdminAction {

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
			$Date = $params['Date'];
			//Hotel Info
			$HotelInfo = $this->Hotel->getListHotel(array('task' => 'getEdit', 'Id' => $params['Hotel']));
			
			foreach($Date as $k => $v) {
				$input = array();
				$input['RoomSet_Date'] = $params['Date'][$k];
				$input['RoomSet_SingleNetRate'] = $params['SingleNetRate'][$k];
				$input['RoomSet_DoubleNetRate'] = $params['DoubleNetRate'][$k];
				$input['RoomSet_ExtraBed'] = $params['ExtraBed'][$k];
				$input['RoomSet_Allotment'] = $params['Allotment'][$k];
				$input['RoomSet_Breakfast'] = $params['CheckchkBFIncludedCheckBox'][$k];
				$input['RoomSet_Status'] = 1;
				$input['RoomSet_ExistedPolicy'] = ($params['isSetExistedPolicy'] == 1) ? 1 : 0;
				$input['Hotel_Id'] = $params['Hotel'];
				$input['Room_Id'] = $params['Room'];
				$input['RoomSet_Tax'] = $HotelInfo['Hotel_Tax'];
				$input = MyZend_Function::filterInput($input, 'array');
				//check exist row
				$checkExistRow = $this->RoomSet->getListRoomSet(array("task" => 'getEditWhereDateHotelRoom', 'Date' => $input['RoomSet_Date'], 'Hotel' => $input['Hotel_Id'], 'Room' => $input['Room_Id']));
				if (count($checkExistRow) <= 0) {
					$this->RoomSet->insertRoomSet($input);
				} else {
					$this->RoomSet->updateRoomSetWhereDate($input, $input['RoomSet_Date'], $input['Hotel_Id'], $input['Room_Id']);
				}
			}
			$this->SuccessMsg[] = $this->Msg['AddSuccess'];
		}
		 $this->view->assign('SuccessMsg', $this->SuccessMsg);
	}

	public function setRoomPreviewAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getPost();
		$params = MyZend_Function::filterInput($params, 'array');
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
		echo json_encode($data);
	}

}

?>
