<?php

class Iadm_HotelRoomController extends MyZend_Controller_AdminAction {

    protected $Model;
    protected $SessionPage;
    protected $FieldSearch = array('StatusSearch', 'HotelSearch');
    protected $Facilities;
    protected $UploadImage;
    protected $HotelPicture;
    protected $Hotel;
    protected $Bed;
    protected $RoomView;

    public function init() {

        parent::init();
        $this->Model = new Iadm_Model_HotelRoom();
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
        $this->Facilities = new Iadm_Model_HotelRoomFacilities();
        $this->UploadImage = new Iadm_Model_UploadImage();
        $this->HotelPicture = new Iadm_Model_HotelPicture();
        $this->Bed = new Iadm_Model_HotelRoomBed();
        $this->RoomView = new Iadm_Model_HotelRoomView();

        $this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
        $this->view->assign('Facilities', MyZend_Function::filterOutput($this->Facilities->getListFacilities(array('task' => 'getAll')), 'array'));
        $this->view->assign('Bed', MyZend_Function::filterOutput($this->Bed->getListRoomBed(array('task' => 'getAll')), 'array'));
        $this->view->assign('RoomView', MyZend_Function::filterOutput($this->RoomView->getListRoomView(array('task' => 'getAll')), 'array'));
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

        $HotelRoomAll = $this->Model->getListHotelRoom(array('task' => 'getnumAll', 'search' => $this->Search));
        $countHotelRoomAll = $HotelRoomAll->count;

        $currentPageNumber = $this->_request->getParam('page', 1);
        $this->SessionPage->currentPageNumber = $currentPageNumber;
        $page = MyZend_Function::getPaginator(array(
                    'CurrentPageNumber' => $currentPageNumber,
                    'Total' => $countHotelRoomAll));

        $listHotelRoom = $this->Model->getListHotelRoom(array(
            'task' => 'getPage',
            'pageCurrent' => $currentPageNumber,
            'offset' => _ITEM_COUNT_PER_PAGE_BACK,
            'search' => $this->Search));

        $fromItem = ($countHotelRoomAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
        $toItem = ($countHotelRoomAll > 0) ? ($fromItem + count($listHotelRoom) - 1) : 0;

        $this->view->assign("search", $this->Search);
        $this->view->assign('fromItem', $fromItem);
        $this->view->assign('toItem', $toItem);
        $this->view->assign('countHotelRoomAll', $countHotelRoomAll);
        $this->view->assign('listHotelRoom', MyZend_Function::filterOutput($listHotelRoom, 'array'));
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

                $Facilities = '';
				foreach ($Params['Facilities'] as $v) {
					$Facilities .= ',' . $v;
				}
				$Facilities = substr($Facilities, 1);
				
				$Bed = '';
				foreach ($Params['Bed'] as $v) {
					$Bed .= ',' . $v;
				}
				$Bed = substr($Bed, 1);
                
                $input = array();
                $input['Room_Name'] = $Params['Name'];
                $input['Room_Facilities'] = $Facilities;
                $input['Room_Description'] = $Params['Description'];
                $input['Hotel_Id'] = $Params['Hotel'];
                $input['Room_Status'] = $Params['Status'];
                $input['Room_Date_create'] = date('Y-m-d H:i:s');
                $input['Room_Size'] = $Params['Size'];
                $input['Room_Code'] = time();
                $input['Room_Bed'] = $Bed;
                $input['Room_Allotment'] = $Params['Quantity'];
                $input['Room_TokenPicture'] = $this->SessionPage->Token;
                $input['Room_MaxExtrabeds'] = $Params['MaxExtrabeds'];
                $input['Room_MaxOccupancy'] = $Params['MaxOccupancy'];
                $input['Room_Breakfast'] = $Params['Breakfast'];
                $input['RoomView_Id'] = $Params['RoomView_Id'];
				$input = MyZend_Function::filterInput($input, 'array');
                $this->Model->insertHotelRoom($input);

                //Upload Picture Hotel
                $listImageUpload = MyZend_Function::filterOutput($this->UploadImage->getListUploadImage(array(
                    'task' => 'getWhereToken',
                    'token' => $this->SessionPage->Token)), 'array');
                if (count($listImageUpload) > 0) {
                    foreach ($listImageUpload as $v) {
                        $status_imgList = $_POST['status_imgList'];
                        $title_imgList = $_POST['title_imgList'];
                        $position_imgList = $_POST['position_imgList'];
                        //Insert Images temp to Picture Hotel DB
                        $input = array();
                        $input['HotelPicture_Name'] = 'Room_' . $v['image_name'];
                        $input['HotelPicture_Token'] = $this->SessionPage->Token;
                        if (isset($status_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Status'] = $status_imgList[$v['upload_id']];
                        }
                        if (isset($title_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Title'] = $title_imgList[$v['upload_id']];
                        }
                        if (isset($position_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Position'] = $position_imgList[$v['upload_id']];
                        }
						$input = MyZend_Function::filterInput($input, 'array');
                        $this->HotelPicture->insertHotelPicture($input);
                        //Move file Image temp to Picture Hotel
                        copy(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name'], _PICTURE_HOTEL_PATH . '/' . 'Room_' . $v['image_name']);
                        //Delete file Image temp
                        unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
                        //Delete db Image temp
                        $this->UploadImage->deleteUploadImage($v['upload_id']);
                    }
                }
                $this->SuccessMsg[] = $this->Msg['AddSuccess'];
            }
        }

        $this->SessionPage->Token = MyZend_Function::hasTokenPictureHotel();
        $this->view->assign('Token', $this->SessionPage->Token);
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $HotelRoomInfo = $this->Model->getListHotelRoom(array('task' => 'getEdit', 'Id' => $id));
        $this->SessionPage->Token = $HotelRoomInfo['Room_TokenPicture'];
        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParams();

            $Validator = new Zend_Validate_NotEmpty();
            if (!$Validator->isValid($Params['Name'])) {
                $this->ErrorMsg[] = $this->Msg['NoEmptyName'];
            }

            if (count($this->ErrorMsg) == 0) {

                $Facilities = '';
				foreach ($Params['Facilities'] as $v) {
					$Facilities .= ',' . $v;
				}
				$Facilities = substr($Facilities, 1);
				
				$Bed = '';
				foreach ($Params['Bed'] as $v) {
					$Bed .= ',' . $v;
				}
				$Bed = substr($Bed, 1);

                $input = array();
                $input['Room_Name'] = $Params['Name'];
                $input['Room_Facilities'] = $Facilities;
                $input['Room_Description'] = $Params['Description'];
                $input['Hotel_Id'] = $Params['Hotel'];
                $input['Room_Status'] = $Params['Status'];
                $input['Room_Size'] = $Params['Size'];
                $input['Room_Bed'] = $Bed;
                $input['Room_Allotment'] = $Params['Quantity'];
                $input['Room_MaxExtrabeds'] = $Params['MaxExtrabeds'];
                $input['Room_MaxOccupancy'] = $Params['MaxOccupancy'];
                $input['Room_Breakfast'] = $Params['Breakfast'];
                $input['RoomView_Id'] = $Params['RoomView_Id'];
				$input = MyZend_Function::filterInput($input, 'array');
                $this->Model->updateHotelRoom($input, $id);
                
                //Update Picture Hotel
                $listImgage = MyZend_Function::filterOutput($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array');
                if (count($listImgage) > 0) {
                    foreach ($listImgage as $v) {
                        $status_imgList = $_POST['status_imgListHotel'];
                        $title_imgList = $_POST['title_imgListHotel'];
                        $position_imgList = $_POST['position_imgListHotel'];
                        $input = array();
                        if (isset($status_imgList[$v['HotelPicture_Id']])) {
                            $input['HotelPicture_Status'] = $status_imgList[$v['HotelPicture_Id']];
                        }
                        if (isset($title_imgList[$v['HotelPicture_Id']])) {
                            $input['HotelPicture_Title'] = $title_imgList[$v['HotelPicture_Id']];
                        }
                        if (isset($position_imgList[$v['HotelPicture_Id']])) {
                            $input['HotelPicture_Position'] = $position_imgList[$v['HotelPicture_Id']];
                        }
						$input = MyZend_Function::filterInput($input, 'array');
                        $this->HotelPicture->updateHotelPicture($input, $v['HotelPicture_Id']);
                    }
                }

                //Upload Picture Hotel
                $listImageUpload = MyZend_Function::filterOutput($this->UploadImage->getListUploadImage(array(
                    'task' => 'getWhereToken',
                    'token' => $this->SessionPage->Token)), 'array');
                if (count($listImageUpload) > 0) {
                    foreach ($listImageUpload as $v) {
                        $status_imgList = $_POST['status_imgList'];
                        $title_imgList = $_POST['title_imgList'];
                        $position_imgList = $_POST['position_imgList'];
                        //Insert Images temp to Picture Hotel DB
                        $input = array();
                        $input['HotelPicture_Name'] = 'Room_' . $v['image_name'];
                        $input['HotelPicture_Token'] = $this->SessionPage->Token;
                        if (isset($status_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Status'] = $status_imgList[$v['upload_id']];
                        }
                        if (isset($title_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Title'] = $title_imgList[$v['upload_id']];
                        }
                        if (isset($position_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Position'] = $position_imgList[$v['upload_id']];
                        }
						$input = MyZend_Function::filterInput($input, 'array');
                        $this->HotelPicture->insertHotelPicture($input);
                        //Move file Image temp to Picture Hotel
                        copy(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name'], _PICTURE_HOTEL_PATH . '/' . 'Room_' . $v['image_name']);
                        //Delete file Image temp
                        unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
                        //Delete db Image temp
                        $this->UploadImage->deleteUploadImage($v['upload_id']);
                    }
                }
                $this->forward('index', 'hotel-room', 'iadm', array(
                    'SuccessMsg' => $this->Msg['SuccessUpdate'],
                    'page' => $this->SessionPage->currentPageNumber));
            }
        }
        
        //Xoa Image trong Temp
        $listUploadImageDel = $this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token));
        foreach ($listUploadImageDel as $v) {
            $this->UploadImage->deleteUploadImage($v['upload_id']);
            unlink(_PICTURE_HOTEL_TEMP_PATH . '/' . $v['image_name']);
        }
        $this->view->assign('Token', $this->SessionPage->Token);
        $this->view->assign('listImage', MyZend_Function::filterOutput($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array'));
        $this->view->assign('HotelRoom', MyZend_Function::filterOutput($HotelRoomInfo, 'array'));
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function deleteAction() {

        //Khong cho xoa
        $this->forward('index', 'hotel-room', 'iadm', array(
            'page' => $this->SessionPage->currentPageNumber,
            'ErrorMsg' => 'Dữ liệu không thể xóa.'));
        return;


        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('typeAction') && $this->getRequest()->getParam('typeAction') == 'index') {
            $Params = $this->getRequest()->getParam('checkboxDel');
        } else {
            $Id = $this->getRequest()->getParam('id');
            $Params[0] = $Id;
        }

        foreach ($Params as $v) {
            $this->Model->deleteHotelRoom($v);
        }
        $this->forward('index', 'hotel-room', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
    }

    public function getImageListAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getParams();
        $result = $this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $params['token']));
        echo json_encode($result);
    }

    public function getImageListAllAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getParams();
        $result["temp"] = $this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $params['token']));
        $result["hotel"] = MyZend_Function::filterOutput($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $params['token'])), 'array');
        echo "var data = " . json_encode($result) . ";";
    }

    public function delPicTempAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getParams();
        $this->UploadImage->deleteUploadImage($params['id']);
        unlink(_PICTURE_HOTEL_TEMP_PATH . '/' . $params['image']);
    }

    public function delPicHotelAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getParams();
        $this->HotelPicture->deleteHotelPicture($params['id']);
        unlink(_PICTURE_HOTEL_PATH . '/' . $params['image']);
    }

}

?>
