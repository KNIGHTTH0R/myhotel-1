<?php

class Iadm_HotelRoomPromotionController extends MyZend_Controller_AdminAction {

    protected $Model;
    protected $SessionPage;
    protected $Hotel;
    protected $Room;
    protected $PolicyCancel;
    protected $FieldSearch = array('StatusSearch', 'HotelSearch');

    public function init() {

        parent::init();
        $this->Model = new Iadm_Model_HotelRoomPromotion();
        $this->SessionPage = new Zend_Session_Namespace('SessionPage');
        $this->Hotel = new Iadm_Model_Hotel();
        $this->Room = new Iadm_Model_HotelRoom();
        $this->PolicyCancel = new Iadm_Model_HotelRoomPolicyCancel();

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

        $PromotionAll = $this->Model->getListPromotion(array('task' => 'getnumAll', 'search' => $this->Search));
        $countPromotionAll = $PromotionAll->count;

        $currentPageNumber = $this->_request->getParam('page', 1);
        $this->SessionPage->currentPageNumber = $currentPageNumber;
        $page = MyZend_Function::getPaginator(array(
                    'CurrentPageNumber' => $currentPageNumber,
                    'Total' => $countPromotionAll));

        $listPromotion = MyZend_Function::filterOutput($this->Model->getListPromotion(array(
                            'task' => 'getPage',
                            'pageCurrent' => $currentPageNumber,
                            'offset' => _ITEM_COUNT_PER_PAGE_BACK,
                            'search' => $this->Search)), 'array');

        $fromItem = ($countPromotionAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
        $toItem = ($countPromotionAll > 0) ? ($fromItem + count($listPromotion) - 1) : 0;

        $data = array();
        foreach ($listPromotion as $k => $v) {
            $RoomType = explode(',', $v['RoomPromotion_RoomTypes']);
            $RoomTypeList = MyZend_Function::filterOutput($this->Room->getListHotelRoom(array('task' => 'getWhereListRoomId', 'listId' => $RoomType)), 'array');
            $data[$k] = $v;
            $data[$k]['RoomType'] = $RoomTypeList;
        }
        $listPromotion = $data;

        $this->view->assign('fromItem', $fromItem);
        $this->view->assign('toItem', $toItem);
        $this->view->assign('countPromotionAll', $countPromotionAll);
        $this->view->assign('listPromotion', $listPromotion);
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

				$DayBook = '';
				foreach ($Params['DayBookCheckbox'] as $v) {
					$DayBook .= ',' . $v;
				}
				$DayBook = substr($DayBook, 1);
				
				$DayGetRoom = '';
				foreach ($Params['DayGetRoomCheckbox'] as $v) {
					$DayGetRoom .= ',' . $v;
				}
				$DayGetRoom = substr($DayGetRoom, 1);

                $input = array();
                $input['Hotel_Id'] = $Params['Hotel'];
                $input['RoomPromotion_Name'] = $Params['Name'];
                $input['RoomPromotion_RoomTypes'] = $listRoom;
                $input['RoomPromotion_DiscountType'] = $Params['DiscountType'];
                $input['RoomPromotion_DiscountValue'] = $Params['DiscountValue'];
                $input['RoomPromotion_MinRooms'] = $Params['MinRooms'];
                $input['RoomPromotion_MinNightsStay'] = $Params['MinNightsStay'];
                $input['RoomPromotion_MaxNightsStay'] = $Params['MaxNightsStay'];
                $input['RoomPromotion_BookDateFrom'] = MyZend_Function::formatDateDMY_YMD($Params['BookDateFrom'], '/');
                $input['RoomPromotion_BookDateTo'] = MyZend_Function::formatDateDMY_YMD($Params['BookDateTo'], '/');
                $input['RoomPromotion_StayDateFrom'] = MyZend_Function::formatDateDMY_YMD($Params['StayDateFrom'], '/');
                $input['RoomPromotion_StayDateTo'] = MyZend_Function::formatDateDMY_YMD($Params['StayDateTo'], '/');
                $input['RoomPromotion_DayBook'] = $DayBook;
                $input['RoomPromotion_DayGetRoom'] = $DayGetRoom;
                $input['RoomPromotion_TimePickerBookTimeFrom'] = $Params['TimePickerBookTimeFrom_Hour'] . ':' . $Params['TimePickerBookTimeFrom_Minute'];
                $input['RoomPromotion_TimePickerBookTimeTo'] = $Params['TimePickerBookTimeTo_Hour'] . ':' . $Params['TimePickerBookTimeTo_Minute'];
                $input['PolicyCancel_Id'] = $Params['PolicyCancel'];
                $input['RoomPromotion_Status'] = $Params['Status'];
                $input = MyZend_Function::filterInput($input, 'array');
                $this->Model->insertPromotion($input);
                $this->SuccessMsg[] = $this->Msg['AddSuccess'];
            }
        }
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
        $this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
        $this->view->assign('PolicyCancel', MyZend_Function::filterOutput($this->PolicyCancel->getListPolicyCancel(array('task' => 'getAllWhereType', 'Type' => 2)), 'array'));
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $PromotionInfo = $this->Model->getListPromotion(array('task' => 'getEdit', 'Id' => $id));

        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParams();
            if (count($this->ErrorMsg) == 0) {
                $listRoom = '';
				foreach ($Params['RoomTypes'] as $v) {
					$listRoom .= ',' . $v;
				}
				$listRoom = substr($listRoom, 1);

				$DayBook = '';
				foreach ($Params['DayBookCheckbox'] as $v) {
					$DayBook .= ',' . $v;
				}
				$DayBook = substr($DayBook, 1);
				
				$DayGetRoom = '';
				foreach ($Params['DayGetRoomCheckbox'] as $v) {
					$DayGetRoom .= ',' . $v;
				}
				$DayGetRoom = substr($DayGetRoom, 1);

                $input = array();
                $input['Hotel_Id'] = $Params['Hotel'];
                $input['RoomPromotion_Name'] = $Params['Name'];
                $input['RoomPromotion_RoomTypes'] = $listRoom;
                $input['RoomPromotion_DiscountType'] = $Params['DiscountType'];
                $input['RoomPromotion_DiscountValue'] = $Params['DiscountValue'];
                $input['RoomPromotion_MinRooms'] = $Params['MinRooms'];
                $input['RoomPromotion_MinNightsStay'] = $Params['MinNightsStay'];
                $input['RoomPromotion_MaxNightsStay'] = $Params['MaxNightsStay'];
                $input['RoomPromotion_BookDateFrom'] = MyZend_Function::formatDateDMY_YMD($Params['BookDateFrom'], '/');
                $input['RoomPromotion_BookDateTo'] = MyZend_Function::formatDateDMY_YMD($Params['BookDateTo'], '/');
                $input['RoomPromotion_StayDateFrom'] = MyZend_Function::formatDateDMY_YMD($Params['StayDateFrom'], '/');
                $input['RoomPromotion_StayDateTo'] = MyZend_Function::formatDateDMY_YMD($Params['StayDateTo'], '/');
                $input['RoomPromotion_DayBook'] = $DayBook;
                $input['RoomPromotion_DayGetRoom'] = $DayGetRoom;
                $input['RoomPromotion_TimePickerBookTimeFrom'] = $Params['TimePickerBookTimeFrom_Hour'] . ':' . $Params['TimePickerBookTimeFrom_Minute'];
                $input['RoomPromotion_TimePickerBookTimeTo'] = $Params['TimePickerBookTimeTo_Hour'] . ':' . $Params['TimePickerBookTimeTo_Minute'];
                $input['PolicyCancel_Id'] = $Params['PolicyCancel'];
                $input['RoomPromotion_Status'] = $Params['Status'];
                $input = MyZend_Function::filterInput($input, 'array');
                $this->Model->updatePromotion($input, $id);
                $this->forward('index', 'hotel-room-promotion', 'iadm', array(
                    'SuccessMsg' => $this->Msg['SuccessUpdate'],
                    'page' => $this->SessionPage->currentPageNumber));
            }
        }

        $this->view->assign('Promotion', MyZend_Function::filterOutput($PromotionInfo, 'array'));
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
        $this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
        $this->view->assign('PolicyCancel', MyZend_Function::filterOutput($this->PolicyCancel->getListPolicyCancel(array('task' => 'getAllWhereType', 'Type' => 2)), 'array'));
        $this->view->assign('Room', MyZend_Function::filterOutput($this->Room->getListHotelRoom(array('task' => 'getWhereHotel', 'hotelId' => $PromotionInfo['Hotel_Id'])), 'array'));
    }

    public function deleteAction() {

        //Khong cho xoa
        $this->forward('index', 'hotel-room-promotion', 'iadm', array(
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
            $this->Model->deletePromotion($v);
        }
        $this->forward('index', 'hotel-room-promotion', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
    }

}

?>
