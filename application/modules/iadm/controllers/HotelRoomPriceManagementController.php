<?php

class Iadm_HotelRoomPriceManagementController extends MyZend_Controller_AdminAction {

    protected $Hotel;
    protected $Room;
    protected $RoomSet;


    public function init() {
        parent::init();
        $this->Hotel = new Iadm_Model_Hotel();
        $this->Room = new Iadm_Model_HotelRoom();
        $this->RoomSet = new Iadm_Model_HotelRoomSet();
        $this->view->assign('Hotel', MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getAll')), 'array'));
    }

    public function indexAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getPost();
            $Id = $params['Id'];

            foreach ($Id as $k => $v) {
                $input = array();
                $input['RoomSet_SingleNetRate'] = $params['SingleNetRate'][$k];
                $input['RoomSet_DoubleNetRate'] = $params['DoubleNetRate'][$k];
                $input['RoomSet_ExtraBed'] = $params['ExtraBed'][$k];
                $input['RoomSet_Allotment'] = $params['Allotment'][$k];
                $input['RoomSet_Breakfast'] = ($params['Breakfast'][$k] == 1) ? 1 : 0;
                $input['RoomSet_Promotion'] = ($params['Promotion'][$k] == 1) ? 1 : 0;
                $input = MyZend_Function::filterInput($input, 'array');
                $this->RoomSet->updateRoomSet($input, $v);
            }
            $this->SuccessMsg[] = $this->Msg['AddSuccess'];
        }
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
    }

    public function searchRoomPreviewAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getPost();

        $dateFrom = $params['dateFrom'];
        $dateTo = $params['dateTo'];
        $dateFromArr = explode('/', $dateFrom);
        $dateToArr = explode('/', $dateTo);
        $hotel = $params['hotel'];
        $room = $params['room'];

        $dateF = $dateFromArr[2] . '-' . $dateFromArr[1] . '-' . $dateFromArr[0];
        $dateT = $dateToArr[2] . '-' . $dateToArr[1] . '-' . $dateToArr[0];

        $hotelInfo = MyZend_Function::filterOutput($this->Hotel->getListHotel(array('task' => 'getEdit', 'Id' => $hotel)), 'array');

        $result = MyZend_Function::filterOutput($this->RoomSet->getListRoomSet(array("task" => 'getEditWhereBetweenDateHotelRoom', 'DateFrom' => $dateF, 'DateTo' => $dateT, 'Hotel' => $hotel, 'Room' => $room)), 'array');

        $data = array();
        for ($i = 0; $i < count($result); $i++) {
            $data[$i]['Date'] = date('d-m-Y', strtotime($result[$i]['RoomSet_Date']));
            $data[$i]['Day'] = date('w', strtotime($result[$i]['RoomSet_Date']));
            $data[$i]['SingleNetRate'] = $result[$i]['RoomSet_SingleNetRate'];
            $data[$i]['DoubleNetRate'] = $result[$i]['RoomSet_DoubleNetRate'];
            $data[$i]['ExtraBed'] = $result[$i]['RoomSet_ExtraBed'];
            $data[$i]['Promotion'] = $result[$i]['RoomSet_Promotion'];
            $data[$i]['Breakfast'] = $result[$i]['RoomSet_Breakfast'];
            $data[$i]['Id'] = $result[$i]['RoomSet_Id'];
            $data[$i]['TaxSingleNetRate'] = $result[$i]['RoomSet_SingleNetRate'] - ($result[$i]['RoomSet_SingleNetRate'] * (abs($result[$i]['RoomSet_Tax'] / 100)));
            $data[$i]['TaxDoubleNetRate'] = $result[$i]['RoomSet_DoubleNetRate'] - ($result[$i]['RoomSet_DoubleNetRate'] * (abs($result[$i]['RoomSet_Tax'] / 100)));
            $data[$i]['TaxExtraBed'] = $result[$i]['RoomSet_ExtraBed'] - ($result[$i]['RoomSet_ExtraBed'] * (abs($result[$i]['RoomSet_Tax'] / 100)));
        }
        echo json_encode($data);
    }

}

?>
