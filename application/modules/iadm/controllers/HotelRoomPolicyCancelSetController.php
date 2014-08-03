<?php

class Iadm_HotelRoomPolicyCancelSetController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;
	protected $Hotel;
	protected $Room;
	protected $PolicyCancel;
	protected $FieldSearch = array('StatusSearch', 'HotelSearch');

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelRoomPolicyCancelSet();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
		$this->PolicyCancel = new Iadm_Model_HotelRoomPolicyCancel();
		$this->Hotel = new Iadm_Model_Hotel();
		$this->Room = new Iadm_Model_HotelRoom();

		foreach ($this->FieldSearch as $v) {
			$this->Search[$v] = null;
		}
		$Params = $this->getRequest()->getParams();
		if (!isset($Params['page']) && $Params['action'] != 'edit' && $Params['action'] != 'delete') {
			unset($this->SessionPage->SearchInfo);
		}

		if (isset($this->SessionPage->SearchInfo)) {
			foreach ($this->FieldSearch as $v) {
				$this->Search[$v] = $this->SessionPage->SearchInfo[$v];
			}
		}
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('typeAction') && $this->getRequest()->getParam('typeAction') == 'search') {
			$paramsPost = $this->getRequest()->getParams();
			foreach ($this->FieldSearch as $v) {
				$this->Search[$v] = $this->SessionPage->SearchInfo[$v] = $paramsPost[$v];
			}
		}

		$PolicyCancelSetAll = $this->Model->getListPolicyCancelSet(array('task' => 'getnumAll', 'search' => $this->Search));
		$countPolicyCancelSetAll = $PolicyCancelSetAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countPolicyCancelSetAll));

		$listPolicyCancelSet = $this->Model->getListPolicyCancelSet(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK,
			'search' => $this->Search));

		$fromItem = ($countPolicyCancelSetAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countPolicyCancelSetAll > 0) ? ($fromItem + count($listPolicyCancelSet) - 1) : 0;

		$this->view->assign("search", $this->Search);
		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countPolicyCancelSetAll', $countPolicyCancelSetAll);
		$this->view->assign('listPolicyCancelSet', MyZend_Function::filterOutput($listPolicyCancelSet, 'array'));
		$this->view->assign('page', $page);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
	}

	public function addAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			if (count($this->ErrorMsg) == 0) {
				$listRoom = '';
				foreach ($Params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);
				
				$dateFromArr = explode('/', $Params['DateFrom']);
				$dateToArr = explode('/', $Params['DateTo']);
				$input = array();
				$input['PolicyCancelSet_DateFrom'] = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
				$input['PolicyCancelSet_DateTo'] = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];
				$input['PolicyCancel_Id'] = $Params['PolicyCancel'];
				$input['Hotel_Id'] = $Params['Hotel'];
				$input['Room_Id'] = $listRoom;
				$input['PolicyCancelSet_Status'] = $Params['Status'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertPolicyCancelSet($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
		$this->view->assign('PolicyCancel', MyZend_Function::filterOutput($this->PolicyCancel->getListPolicyCancel(array('task' => 'getAllWhereType', 'Type' => 1)), 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$PolicyCancelSetInfo = $this->Model->getListPolicyCancelSet(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			if (count($this->ErrorMsg) == 0) {
				$listRoom = '';
				foreach ($Params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);
				
				$dateFromArr = explode('/', $Params['DateFrom']);
				$dateToArr = explode('/', $Params['DateTo']);
				$input = array();
				$input['PolicyCancelSet_DateFrom'] = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
				$input['PolicyCancelSet_DateTo'] = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];
				$input['PolicyCancel_Id'] = $Params['PolicyCancel'];
				$input['Hotel_Id'] = $Params['Hotel'];
				$input['Room_Id'] = $listRoom;
				$input['PolicyCancelSet_Status'] = $Params['Status'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updatePolicyCancelSet($input, $id);
				$this->forward('index', 'hotel-room-policy-cancel-set', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
		$this->view->assign('PolicyCancel', MyZend_Function::filterOutput($this->PolicyCancel->getListPolicyCancel(array('task' => 'getAllWhereType', 'Type' => 1)), 'array'));
		$this->view->assign('Room', MyZend_Function::filterOutput($this->Room->getListHotelRoom(array('task' => 'getWhereHotel', 'hotelId' => $PolicyCancelSetInfo['Hotel_Id'])), 'array'));
		$this->view->assign('PolicyCancelSet', MyZend_Function::filterOutput($PolicyCancelSetInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {

		//Khong cho xoa
		$this->forward('index', 'hotel-room-policy-cancel-set', 'iadm', array(
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
			$this->Model->deletePolicyCancelSet($v);
		}
		$this->forward('index', 'hotel-room-policy-cancel-set', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

}

?>
