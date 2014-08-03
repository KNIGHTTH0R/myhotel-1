<?php

class MyZend_Controller_AdminAction extends Zend_Controller_Action {

    protected $User;
    protected $ErrorMsg = array();
    protected $WarningMsg = array();
    protected $SuccessMsg = array();
    protected $Log;
    protected $Msg;

    public function init() {
        parent::init();

        $this->Log = Zend_Registry::get('Log');
        $this->Msg = array(
            'NoEmptyName' => 'Vui lòng nhập Tên.',
            'NoEmptyTitle' => 'Vui lòng nhập Tiêu đề.',
            'NoEmptyLink' => 'Vui lòng nhập Đường dẫn.',
            'AddSuccess' => 'Thêm mới thành công.',
            'ErrorSystem' => 'Có lỗi trong quá trình xử lý.',
            'SuccessUpdate' => 'Cập nhật thành công.',
            'PictureNotValid' => 'File hình ảnh có định dạng không hợp lệ.',
            'PictureSizeValid' => 'File hình ảnh có dung lượng quá hạn cho phép.',
            'EmailExits' => 'Email đã tồn tại. Vui lòng nhập Email khác.',
        );

        $this->User = new Zend_Session_Namespace('userLog');
        if (empty($this->User->UserInfo)) {
            $this->_redirect('/iadm/login');
        }
        MyZend_Function::setLayout(_BACKEND_PATH, 'index');

        $this->view->assign('User', $this->User->UserInfo);
    }

    public function updatestatusAction() {
        try {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender();

            $id = $this->getRequest()->getParam('id');
            $table = $this->getRequest()->getParam('table');
            $statusName = $this->getRequest()->getParam('statusName', null);
            $idName = $this->getRequest()->getParam('idName', null);
            $Model = new MyZend_Model_Db();
            echo $Model->updateStatus($id, $table, $statusName, $idName);
        } catch (Exception $e) {
            $this->Log->log($e->getMessage(), Zend_Log::ERR);
        }
    }

    public function mappingHotelAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getPost();
		$Room = new Iadm_Model_HotelRoom();
		$Hotel = new Iadm_Model_Hotel();
        $result = MyZend_Function::filterOutput($Room->getListHotelRoom(array('task' => 'getWhereHotel', 'hotelId' => $params['hotelId'])), 'array');
        $HotelInfo = MyZend_Function::filterOutput($Hotel->getListHotel(array('task' => 'getEdit', 'Id' => $params['hotelId'])), 'array');
		$data = array();
		foreach($result as $k => $v) {
			$data[$k] = $v;
			$data[$k]['Tax'] = $HotelInfo['Hotel_Tax'];
		}
		echo json_encode($data);
    }

}

?>
