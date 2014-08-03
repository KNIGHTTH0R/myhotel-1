<?php

class Iadm_HotelOrderController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;
	protected $FieldSearch = array('HotelSearch', 'RoomSearch', 'DateFromSearch', 'DateToSearch', 'StatusSearch');
	protected $Hotel;
	protected $Room;
	protected $RoomSet;
	protected $OrderDetail;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelOrder();
		$this->OrderDetail = new Iadm_Model_HotelOrderDetail();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');

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

		$this->Hotel = new Iadm_Model_Hotel();
		$this->Room = new Iadm_Model_HotelRoom();
		$this->RoomSet = new Iadm_Model_HotelRoomSet();

		$this->Search['DateFromSearch'] = date('d/m/Y');
		$this->Search['DateToSearch'] = date('d/m/Y', strtotime('+7 day'));
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
			$this->view->assign('Room', $this->Room->getListHotelRoom(array('task' => 'getWhereHotel', 'hotelId' => $paramsPost['HotelSearch'])));
		}
		$OrderAll = $this->Model->getListOrder(array('task' => 'getnumAll', 'search' => $this->Search));
		$countOrderAll = $OrderAll->count;
		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$listOrder = $this->Model->getListOrder(array(
			'task' => 'getList',
			'search' => $this->Search,));
		$fromItem = ($countOrderAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countOrderAll > 0) ? ($fromItem + count($listOrder) - 1) : 0;

		$this->view->assign("search", $this->Search);
		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countOrderAll', $countOrderAll);
		$this->view->assign('listOrder', MyZend_Function::filterOutput($listOrder, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			if (count($this->ErrorMsg) == 0) {
				$this->Model->updateOrder($Params, $id);
				$this->SuccessMsg[] = $this->Msg['SuccessUpdate'];
			}
		}
		$OrderInfo = $this->Model->getListOrder(array('task' => 'getEdit', 'Id' => $id));
		$this->view->assign('HotelOrder', MyZend_Function::filterOutput($OrderInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

}

?>
