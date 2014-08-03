<?php

class Iadm_HotelRoomBedController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelRoomBed();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$RoomBedAll = $this->Model->getListRoomBed(array('task' => 'getnumAll'));
		$countRoomBedAll = $RoomBedAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countRoomBedAll));

		$listRoomBed = $this->Model->getListRoomBed(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countRoomBedAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countRoomBedAll > 0) ? ($fromItem + count($listRoomBed) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countRoomBedAll', $countRoomBedAll);
		$this->view->assign('listRoomBed', MyZend_Function::filterOutput($listRoomBed, 'array'));
		$this->view->assign('page', $page);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function addAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['Room_Bed_Name'] = $Params['Name'];
				$input['Room_Bed_Status'] = $Params['Status'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertRoomBed($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$RoomBedInfo = $this->Model->getListRoomBed(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['Room_Bed_Name'] = $Params['Name'];
				$input['Room_Bed_Status'] = $Params['Status'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateRoomBed($input, $id);
				$this->forward('index', 'hotel-room-bed', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('RoomBed', MyZend_Function::filterOutput($RoomBedInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {

		//Khong cho xoa
		$this->forward('index', 'hotel-room-bed', 'iadm', array(
			'page' => $this->SessionPage->currentPageNumber,
			'ErrorMsg' => 'Dữ liệu không thể xóa.'));
		return;


		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParam('checkboxDel');
		} else {
			$Id = $this->getRequest()->getParam('id');
			$Params[0] = $Id;
		}

		foreach ($Params as $v) {
			$this->Model->deleteRoomBed($v);
		}
		$this->forward('index', 'hotel-room-bed', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

}

?>
