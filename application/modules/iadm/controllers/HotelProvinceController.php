<?php

class Iadm_HotelProvinceController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_HotelProvince();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$ProvinceAll = $this->Model->getListProvince(array('task' => 'getnumAll'));
		$countProvinceAll = $ProvinceAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countProvinceAll));

		$listProvince = $this->Model->getListProvince(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countProvinceAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countProvinceAll > 0) ? ($fromItem + count($listProvince) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countProvinceAll', $countProvinceAll);
		$this->view->assign('listProvince', MyZend_Function::filterOutput($listProvince, 'array'));
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
					$pictureRename = MyZend_Function::upoadFile($picInfo['name'], _HOTEL_PIC_PATH_PROVINCE, 'Picture', 'PROVINCE_');
				}
				$input = array();
				$input['Province_Name'] = $Params['Name'];
				$input['Province_Status'] = $Params['Status'];
				$input['Province_Picture'] = $pictureRename;
				$input['Province_Lat'] = $Params['lat'];
				$input['Province_Lng'] = $Params['lng'];
				$input['Province_Zoom'] = $Params['zoom'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertProvince($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}

		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$ProvinceInfo = $this->Model->getListProvince(array('task' => 'getEdit', 'Id' => $id));

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
					$pictureRename = MyZend_Function::upoadFile($picInfo['name'], _HOTEL_PIC_PATH_PROVINCE, 'Picture', 'PROVINCE_');
					if ($ProvinceInfo['Province_Picture'] != '' && is_file(_HOTEL_PIC_PATH_PROVINCE . '/' . $ProvinceInfo['Province_Picture'])) {
						unlink(_HOTEL_PIC_PATH_PROVINCE . '/' . $ProvinceInfo['Province_Picture']);
					}
				}
				$input = array();
				$input['Province_Name'] = $Params['Name'];
				$input['Province_Status'] = $Params['Status'];
				$input['Province_Picture'] = ($pictureRename != '') ? $pictureRename : $ProvinceInfo['Province_Picture'];
				$input['Province_Lat'] = $Params['lat'];
				$input['Province_Lng'] = $Params['lng'];
				$input['Province_Zoom'] = $Params['zoom'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateProvince($input, $id);
				$this->forward('index', 'hotel-province', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('Province', MyZend_Function::filterOutput($ProvinceInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {

		//Khong cho xoa
		$this->forward('index', 'hotel-province', 'iadm', array(
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
			$ProvinceInfo = $this->Model->getListProvince(array('task' => 'getEdit', 'Id' => $v));
			if ($ProvinceInfo['Province_Picture'] != '' && is_file(_HOTEL_PIC_PATH_PROVINCE . '/' . $ProvinceInfo['Province_Picture'])) {
				unlink(_HOTEL_PIC_PATH_PROVINCE . '/' . $ProvinceInfo['Province_Picture']);
			}
			$this->Model->deleteProvince($v);
		}
		$this->forward('index', 'hotel-province', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

	public function delpicAction() {

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$picture = $params['picture'];
		$id = $params['id'];
		if (is_file(_HOTEL_PIC_PATH_PROVINCE . '/' . $picture)) {
			unlink(_HOTEL_PIC_PATH_PROVINCE . '/' . $picture);
		}
		$this->Model->updatePicture($id);
	}

}

?>
