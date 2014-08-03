<?php

class Iadm_HotelFacilitiesController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelFacilities();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$FacilitiesAll = $this->Model->getListFacilities(array('task' => 'getnumAll'));
		$countFacilitiesAll = $FacilitiesAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countFacilitiesAll));

		$listFacilities = $this->Model->getListFacilities(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countFacilitiesAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countFacilitiesAll > 0) ? ($fromItem + count($listFacilities) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countFacilitiesAll', $countFacilitiesAll);
		$this->view->assign('listFacilities', MyZend_Function::filterOutput($listFacilities, 'array'));
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

			//Kiem tra hinh upload
			$file = new Zend_File_Transfer_Adapter_Http();
			$fileInfo = $file->getFileInfo('Picture');
			$picInfo = $fileInfo['Picture'];
			if ($picInfo['name'] != '') {
				//Kiem tra dinh dang file
				$file->addValidator('Extension', false, _CONFIG_EXTENSION_FILE);
				if (!$file->isValid('Picture')) {
					$this->ErrorMsg[] = $this->Msg['PictureNotValid'];
				}

				//Kiem tra dung luong file
				$file->addValidator('FilesSize', false, array('max' => _CONFIG_SIZE_FILE));
				if (!$file->isValid('Picture')) {
					$this->ErrorMsg[] = $this->Msg['PictureSizeValid'];
				}
			}

			if (count($this->ErrorMsg) == 0) {

				$pictureRename = '';
				if ($picInfo['name'] != '') {
					$pictureRename = MyZend_Function::upoadFile($picInfo['name'], _PICTURE_PATH, 'Picture', 'FACILITIES_');
				}
				$input = array();
				$input['Facilities_Name'] = $Params['Name'];
				$input['Facilities_Status'] = $Params['Status'];
				$input['Facilities_Picture'] = $pictureRename;
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertFacilities($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$FacilitiesInfo = $this->Model->getListFacilities(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Name'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyName'];
			}

			//Kiem tra hinh upload
			$file = new Zend_File_Transfer_Adapter_Http();
			$fileInfo = $file->getFileInfo('Picture');
			$picInfo = $fileInfo['Picture'];
			if ($picInfo['name'] != '') {
				//Kiem tra dinh dang file
				$file->addValidator('Extension', false, _CONFIG_EXTENSION_FILE);
				if (!$file->isValid('Picture')) {
					$this->ErrorMsg[] = $this->Msg['PictureNotValid'];
				}

				//Kiem tra dung luong file
				$file->addValidator('FilesSize', false, array('max' => _CONFIG_SIZE_FILE));
				if (!$file->isValid('Picture')) {
					$this->ErrorMsg[] = $this->Msg['PictureSizeValid'];
				}
			}

			if (count($this->ErrorMsg) == 0) {

				$pictureRename = '';
				if ($picInfo['name'] != '') {
					$pictureRename = MyZend_Function::upoadFile($picInfo['name'], _PICTURE_PATH, 'Picture', 'FACILITIES_');
					if ($FacilitiesInfo['Facilities_Picture'] != '' && is_file(_PICTURE_PATH . '/' . $FacilitiesInfo['Facilities_Picture'])) {
						unlink(_PICTURE_PATH . '/' . $FacilitiesInfo['Facilities_Picture']);
					}
				}
				$input = array();
				$input['Facilities_Name'] = $Params['Name'];
				$input['Facilities_Status'] = $Params['Status'];
				$input['Facilities_Picture'] = ($pictureRename != '') ? $pictureRename : $FacilitiesInfo['Facilities_Picture'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateFacilities($input, $id);
				$this->forward('index', 'hotel-facilities', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('Facilities', MyZend_Function::filterOutput($FacilitiesInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {

		//Khong cho xoa
		$this->forward('index', 'hotel-facilities', 'iadm', array(
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
			$FacilitiesInfo = $this->Model->getListFacilities(array('task' => 'getEdit', 'Id' => $v));
			if ($FacilitiesInfo['Facilities_Picture'] != '' && is_file(_PICTURE_PATH . '/' . $FacilitiesInfo['Facilities_Picture'])) {
				unlink(_PICTURE_PATH . '/' . $FacilitiesInfo['Facilities_Picture']);
			}
			$this->Model->deleteFacilities($v);
		}
		$this->forward('index', 'hotel-facilities', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

	public function delpicAction() {

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$picture = $params['picture'];
		$id = $params['id'];
		if (is_file(_PICTURE_PATH . '/' . $picture)) {
			unlink(_PICTURE_PATH . '/' . $picture);
		}
		$this->Model->updatePicture($id);
	}

}

?>
