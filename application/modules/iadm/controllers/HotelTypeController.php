<?php

class Iadm_HotelTypeController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelType();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$HotelTypeAll = $this->Model->getListHotelType(array('task' => 'getnumAll'));
		$countHotelTypeAll = $HotelTypeAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countHotelTypeAll));

		$listHotelType = $this->Model->getListHotelType(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countHotelTypeAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countHotelTypeAll > 0) ? ($fromItem + count($listHotelType) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countHotelTypeAll', $countHotelTypeAll);
		$this->view->assign('listHotelType', MyZend_Function::filterOutput($listHotelType, 'array'));
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
				$input['HotelType_Name'] = $Params['Name'];
				$input['HotelType_Status'] = $Params['Status'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertHotelType($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$HotelTypeInfo = $this->Model->getListHotelType(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}
			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['HotelType_Name'] = $Params['Name'];
				$input['HotelType_Status'] = $Params['Status'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateHotelType($input, $id);
				$this->forward('index', 'hotel-type', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('HotelType', MyZend_Function::filterOutput($HotelTypeInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {

		//Khong cho xoa
		$this->forward('index', 'hotel-type', 'iadm', array(
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
			$this->Model->deleteHotelType($v);
		}
		$this->forward('index', 'hotel-type', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

}

?>
