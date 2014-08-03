<?php

class Iadm_HotelGeoNearController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;
	protected $Province;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelGeoNear();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
		$this->Province = new Iadm_Model_HotelProvince();
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$GeoNearAll = $this->Model->getListGeoNear(array('task' => 'getnumAll'));
		$countGeoNearAll = $GeoNearAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countGeoNearAll));

		$listGeoNear = $this->Model->getListGeoNear(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countGeoNearAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countGeoNearAll > 0) ? ($fromItem + count($listGeoNear) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countGeoNearAll', $countGeoNearAll);
		$this->view->assign('listGeoNear', MyZend_Function::filterOutput($listGeoNear, 'array'));
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
				$input['GeoNear_Name'] = $Params['Name'];
				$input['GeoNear_Status'] = $Params['Status'];
				$input['Province_Id'] = $Params['Province'];
				$input['GeoNear_Lat'] = $Params['lat'];
				$input['GeoNear_Lng'] = $Params['lng'];
				$input['GeoNear_Zoom'] = $Params['zoom'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertGeoNear($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('listProvince', MyZend_Function::filterOutput($this->Province->getListProvince(array('task' => 'getAll')), 'array'));
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$GeoNearInfo = $this->Model->getListGeoNear(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['GeoNear_Name'] = $Params['Name'];
				$input['GeoNear_Status'] = $Params['Status'];
				$input['Province_Id'] = $Params['Province'];
				$input['GeoNear_Lat'] = $Params['lat'];
				$input['GeoNear_Lng'] = $Params['lng'];
				$input['GeoNear_Zoom'] = $Params['zoom'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateGeoNear($input, $id);
				$this->forward('index', 'hotel-geo-near', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('GeoNear', MyZend_Function::filterOutput($GeoNearInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('listProvince', MyZend_Function::filterOutput($this->Province->getListProvince(array('task' => 'getAll')), 'array'));
	}

	public function deleteAction() {
		//Khong cho xoa
		$this->forward('index', 'hotel-geo-near', 'iadm', array(
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
			$this->Model->deleteGeoNear($v);
		}
		$this->forward('index', 'hotel-geo-near', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

}

?>
