<?php

class Iadm_WebInfoController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $Msg;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_WebInfo();
		$this->Msg = array(
			'ErrorSystem' => 'Có lỗi trong quá trình xử lý.',
			'SuccessUpdate' => 'Cập nhật thành công.',
			'PictureNotValid' => 'File hình ảnh có định dạng không hợp lệ.',
			'PictureSizeValid' => 'File hình ảnh có dung lượng quá hạn cho phép.',
		);
	}

	public function indexAction() {

		if ($this->getRequest()->isPost()) {
			$params = $this->getRequest()->getParams();

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

				$data = $this->Model->getWebInfo(array('task' => 'getEdit'));
				$pictureRename = '';
				if ($picInfo['name'] != '') {
					$pictureRename = MyZend_Function::upoadFile($picInfo['name'], _PICTURE_PATH, 'Picture', 'WEB_INFO_ICON_');
					if ($data['Picture'] != '' && is_file(_PICTURE_PATH . '/' . $data['Picture'])) {
						unlink(_PICTURE_PATH . '/' . $data['Picture']);
					}
				}
				$input = array();
				$input['Title'] = $params['Title'];
				$input['Keywords'] = $params['Keywords'];
				$input['Description'] = $params['Description'];
				$input['Email'] = $params['Email'];
				$input['Picture'] = ($pictureRename != '') ? $pictureRename : $data['Picture'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateWebInfo($input);
				$this->SuccessMsg[] = $this->Msg['SuccessUpdate'];
			}
		}

		$data = $this->Model->getWebInfo(array('task' => 'getEdit'));
		$this->view->assign('WebInfo', MyZend_Function::filterOutput($data, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function delpicAction() {

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$params = $this->getRequest()->getParams();
		$picture = $params['picture'];
		if (is_file(_PICTURE_PATH . '/' . $picture)) {
			unlink(_PICTURE_PATH . '/' . $picture);
		}
		$this->Model->updatePicture();
	}

}

?>
