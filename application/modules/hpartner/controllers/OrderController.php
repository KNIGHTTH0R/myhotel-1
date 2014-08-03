<?php

class Hpartner_OrderController extends MyZend_Controller_AdminHpartnerAction {

    protected $ModelOrder;
    protected $Validate;
    protected $ModelRoom;
    protected $ModelHotel;
    protected $ModelRoomSet;
    protected $ModelOrderDetail;
    protected $Search;
    protected $FieldSearch = array('RoomSearch', 'DateFromSearch', 'DateToSearch');

    public function init() {
        parent::init();
        $this->ModelOrder = new Hpartner_Model_Order();
        $this->ModelRoom = new Hpartner_Model_HotelRoom();
        $this->ModelHotel = new Hpartner_Model_Hotel();
        $this->ModelRoomSet = new Hpartner_Model_HotelRoomSet();
        $this->ModelOrderDetail = new Hpartner_Model_OrderDetail();
        $this->Validate = new MyZend_Validate();
        $this->Search['DateFromSearch'] = date('d/m/Y');
        $this->Search['DateToSearch'] = date('d/m/Y', strtotime('+7 day'));
    }

    public function indexAction() {

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam('typeAction') && $this->getRequest()->getParam('typeAction') == 'search') {
            $paramsPost = $this->getRequest()->getParams();
            $paramsPost = MyZend_Function::filterInputPartner($paramsPost, 'array');

            $this->Search = $paramsPost;
            $this->Search['HotelSearch'] = $this->UserPartner->HotelInfo['Hotel_Id'];
        }
        $listOrder = $this->ModelOrder->getListOrder(array(
            'task' => 'getList',
            'select' => array('HotelOrder_Total', 'HotelOrder_TotalAmount', 'HotelOrder_Code', 'HotelOrder_Status', 'HotelOrder_ContactName', 'HotelOrder_BookDate', 'HotelOrder_StayDate', 'HotelOrder_OutDate', 'HotelOrder_Id'),
            'search' => $this->Search,));
        $Order = MyZend_Function::filterOutputPartner($listOrder, 'array');
        //Tong tien
        $TotalAmount = 0;
        foreach ($Order as $v) {
            $TotalAmount += $v['HotelOrder_TotalAmount'];
        }
        //Get room info
        $Room = $this->ModelRoom->getHotelRoom(array(
            'task' => 'getInfoWhereHotel',
            'select' => array('Room_Id', 'Room_Name'),
            'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
        $Room = MyZend_Function::filterOutputPartner($Room, 'array');
        //Get hotel info
        $HotelInfo = $this->ModelHotel->getHotel(array(
            'task' => 'getInfo',
            'select' => array('Hotel_Name'),
            'User_Id' => $this->UserPartner->UserInfo['User_Id'])
        );
        //Filter output
        $HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');

        $this->view->assign('HotelInfo', $HotelInfo);
        $this->view->assign('TotalAmount', $TotalAmount);
        $this->view->assign('Room', $Room);
        $this->view->assign('Order', $Order);
    }

    public function editAction() {
        $this->_helper->layout()->disableLayout();

        $id = MyZend_Function::filterInputPartner($this->getRequest()->getParam('id'));
        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParams();
            if (count($this->ErrorMsg) == 0) {
                $result = $this->ModelOrder->updateOrder($Params, $id, $this->UserPartner->HotelInfo['Hotel_Id']);
                if ($result) {
                    $this->SuccessMsg[] = $this->Msg->getMessage('update-success');
                } else {
                    $this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
                }
            }
        }
        $OrderInfo = $this->ModelOrder->getListOrder(array('task' => 'getEdit', 'Id' => $id, 'Hotel' => $this->UserPartner->HotelInfo['Hotel_Id']));
        $OrderInfo = MyZend_Function::filterOutputPartner($OrderInfo, 'array');

        $this->view->assign('HotelOrder', $OrderInfo);
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function exportAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $paramsPost = $this->getRequest()->getParams();
        $paramsPost = MyZend_Function::filterInputPartner($paramsPost, 'array');

        $this->Search = $paramsPost;
        $this->Search['HotelSearch'] = $this->UserPartner->HotelInfo['Hotel_Id'];

        $listOrder = $this->ModelOrder->getListOrder(array(
            'task' => 'getExport',
            'search' => $this->Search,));
        $Order = MyZend_Function::filterOutputPartner($listOrder, 'array');
        //Tong tien
        $TotalAmount = 0;
        foreach ($Order as $v) {
            $TotalAmount += $v['HotelOrder_TotalAmount'];
        }
        //Export Excel
        header('Content-type: text/excel; charset=UTF-8');
        header("Content-Disposition: attachment; filename=Thong_ke_dat_hang.xls");
        header("Pragma: no-cache;");
        header("Expires: 0;");

        $Content = '<table border="1">';
        $Content .= '<tr>
						<td colspan="2">Tổng số giao dịch: ' . ((is_array($Order)) ? count($Order) : 0) . '</td>
						<td colspan="17">Tổng tiền: ' . $TotalAmount . ' VND</td>
					</tr>';
        $Content .= "<tr><td>Tên khách hàng</td><td>Địa chỉ</td><td>Điện thoại</td><td>Email</td><td>Mã đặt phòng</td><td>Khách sạn</td><td>Số phòng</td><td>Ngày đặt phòng</td><td>Ngày nhận phòng</td><td>Ngày trả phòng</td><td>Đêm ở</td><td>Người lớn</td><td>Giường phụ</td><td>Chính sách hủy phòng</td><td>Tổng tiền</td><td>Khuyến mãi</td><td>Giá trị khuyến mãi</td><td>Thành tiền</td><td>Trạng thái</td></tr>";

        foreach ($Order as $v) {
            $statusName;
            switch ($v['HotelOrder_Status']) {
                case 1:
                    $statusName = 'Đã đọc';
                    break;
                case 2:
                    $statusName = 'Đã xác nhận';
                    break;
                case 3;
                    $statusName = 'Chưa đọc';
                    break;
                case 4:
                    $statusName = 'Đang thanh toán';
                    break;
                case -1;
                    $statusName = 'Hủy';
                    break;
            }
            $Content .= "<tr>";
            $Content .= '<td>' . $v['HotelOrder_ContactName'] . '</td>';
            $Content .= '<td>' . '\'' . $v['HotelOrder_ContactAddress'] . '</td>';
            $Content .= '<td>' . '\'' . $v['HotelOrder_ContactPhone'] . '</td>';
            $Content .= '<td>' . '\'' . $v['HotelOrder_ContactEmail'] . '</td>';
            $Content .= '<td>' . '\'' . $v['HotelOrder_Code'] . '</td>';
            $Content .= '<td>' . $v['Hotel_Name'] . '</td>';
            $Content .= '<td>' . $v['HotelOrder_Room'] . '</td>';
            $Content .= '<td>' . '\'' . MyZend_Function::formatDateYMDHIS_DMY($v['HotelOrder_BookDate'], '/') . '</td>';
            $Content .= '<td>' . '\'' . MyZend_Function::formatDateYMDHIS_DMY($v['HotelOrder_StayDate'], '/') . '</td>';
            $Content .= '<td>' . '\'' . MyZend_Function::formatDateYMDHIS_DMY($v['HotelOrder_OutDate'], '/') . '</td>';
            $Content .= '<td>' . $v['HotelOrder_NightsStay'] . '</td>';
            $Content .= '<td>' . $v['HotelOrder_Adult'] . '</td>';
            $Content .= '<td>' . $v['HotelOrder_ExtraBed'] . '</td>';
            $Content .= '<td>' . '\'' . $v['PolicyCancel_Name'] . '</td>';
            $Content .= '<td>' . $v['HotelOrder_Total'] . '</td>';
            $Content .= '<td>' . '\'' . $v['RoomPromotion_Name'] . '</td>';
            $Content .= '<td>' . '\'' . $v['HotelOrder_Discount'] . '%' . '</td>';
            $Content .= '<td>' . $v['HotelOrder_TotalAmount'] . '</td>';
            $Content .= '<td>' . $statusName . '</td>';
            $Content .= "</tr>";
        }
        $Content .= '</table>';
        echo $Content;
    }

}

?>
