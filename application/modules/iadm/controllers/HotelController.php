<?php

class Iadm_HotelController extends MyZend_Controller_AdminAction {

    protected $Model;
    protected $SessionPage;
    protected $FieldSearch = array('StatusSearch', 'HotelSearch');
    protected $Province;
    protected $District;
    protected $Partner;
    protected $Facilities;
    protected $GeoNear;
    protected $UploadImage;
    protected $HotelPicture;
    protected $Useful;
    protected $HotelType;
	protected $PictureView;
	
	public function init() {

        parent::init();
        $this->Model = new Iadm_Model_Hotel();
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

        $this->Province = new Iadm_Model_HotelProvince();
        $this->District = new Iadm_Model_HotelDistrict();
        $this->Partner = new Iadm_Model_HotelPartner();
        $this->Facilities = new Iadm_Model_HotelFacilities();
        $this->GeoNear = new Iadm_Model_HotelGeoNear();
        $this->UploadImage = new Iadm_Model_UploadImage();
        $this->HotelPicture = new Iadm_Model_HotelPicture();
        $this->Useful = new Iadm_Model_HotelUseful();
        $this->HotelType = new Iadm_Model_HotelType();
		$this->PictureView = new Iadm_Model_HotelPictureView();
		
        $this->view->assign('Useful', MyZend_Function::filterOutput($this->Useful->getListUseful(array('task' => 'getAll')), 'array'));
        $this->view->assign('Province', MyZend_Function::filterOutput($this->Province->getListProvince(array('task' => 'getAll')), 'array'));
        //$this->view->assign('District', $this->District->getListDistrict(array('task' => 'getAll')));
        $this->view->assign('Partner', MyZend_Function::filterOutput($this->Partner->getListPartner(array('task' => 'getAll')), 'array'));
        $this->view->assign('Facilities', MyZend_Function::filterOutput($this->Facilities->getListFacilities(array('task' => 'getAll')), 'array'));
        $this->view->assign('HotelType', MyZend_Function::filterOutput($this->HotelType->getListHotelType(array('task' => 'getAll')), 'array'));
		$this->view->assign('PictureView', MyZend_Function::filterOutput($this->PictureView->getListPictureView(array('task' => 'getAll')), 'array'));
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

        $HotelAll = $this->Model->getListHotel(array('task' => 'getnumAll', 'search' => $this->Search));
        $countHotelAll = $HotelAll->count;

        $currentPageNumber = $this->_request->getParam('page', 1);
        $this->SessionPage->currentPageNumber = $currentPageNumber;
        $page = MyZend_Function::getPaginator(array(
                    'CurrentPageNumber' => $currentPageNumber,
                    'Total' => $countHotelAll));

        $listHotel = $this->Model->getListHotel(array(
            'task' => 'getPage',
            'pageCurrent' => $currentPageNumber,
            'offset' => _ITEM_COUNT_PER_PAGE_BACK,
            'search' => $this->Search));

        $fromItem = ($countHotelAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
        $toItem = ($countHotelAll > 0) ? ($fromItem + count($listHotel) - 1) : 0;

        $this->view->assign("search", $this->Search);
        $this->view->assign('fromItem', $fromItem);
        $this->view->assign('toItem', $toItem);
        $this->view->assign('countHotelAll', $countHotelAll);
        $this->view->assign('listHotel', MyZend_Function::filterOutput($listHotel, 'array'));
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
				$Useful = (isset($Params['Useful'])) ? json_encode($Params['Useful']) : "";
				
                $input = array();
                $input['Hotel_Name'] = $Params['Name'];
                $input['Hotel_Star'] = $Params['Star'];
                $input['Hotel_Address'] = $Params['Address'];
                $input['Province_Id'] = $Params['Province'];
                $input['Hotel_Geo_Near'] = $Params['Geo_Near'];
                $input['District_Id'] = $Params['District'];
                $input['Hotel_Facilities'] = $Facilities;
                $input['Hotel_Description'] = $Params['Description'];
                $input['Hotel_Useful'] = $Useful;
                $input['Hotel_Rule_Order'] = $Params['Rule_Order'];
                $input['User_Id'] = $Params['Partner'];
                $input['Hotel_Status'] = $Params['Status'];
                $input['Hotel_Date_create'] = date('Y-m-d H:i:s');
                $input['Hotel_Map_Lat'] = $Params['lat'];
                $input['Hotel_Map_Lng'] = $Params['lng'];
                $input['Hotel_Map_Zoom'] = $Params['zoom'];
                $input['Hotel_TokenPicture'] = $this->SessionPage->Token;
                $input['Hotel_Room'] = $Params['Room'];
                $input['HotelType_Id'] = $Params['HotelType'];
                $input['Hotel_Phone'] = $Params['Hotel_Phone'];
                $input['Hotel_Fax'] = $Params['Hotel_Fax'];
                $input['Hotel_Website'] = $Params['Hotel_Website'];
                $input['Hotel_Commission'] = $Params['Hotel_Commission'];
                $input['Hotel_InfantAge'] = $Params['InfantAge'];
                $input['Hotel_ChildAgeTo'] = $Params['ChildAgeTo'];
                $input['Hotel_MinGuestAge'] = $Params['MinGuestAge'];
                $input['Hotel_MaxChildAge'] = $Params['MaxChildAge'];
                $input['Hotel_ChildStayFree'] = $Params['ChildStayFree'];
				$input['Hotel_Tax'] = $Params['Hotel_Tax'];
                $input = MyZend_Function::filterInput($input, 'array');
                $this->Model->insertHotel($input);

                //Upload Picture Hotel
                $listImageUpload = MyZend_Function::filterOutput($this->UploadImage->getListUploadImage(array(
                                    'task' => 'getWhereToken',
                                    'token' => $this->SessionPage->Token)), 'array');
                if (count($listImageUpload) > 0) {
                    foreach ($listImageUpload as $v) {
                        $status_imgList = $_POST['status_imgList'];
                        $title_imgList = $_POST['title_imgList'];
                        $position_imgList = $_POST['position_imgList'];
						$pictureview_imgList = $_POST['pictureview_imgList'];
                        //Insert Images temp to Picture Hotel DB
                        $input = array();
                        $input['HotelPicture_Name'] = 'Hotel_' . $v['image_name'];
                        $input['HotelPicture_Token'] = $this->SessionPage->Token;
                        if (isset($status_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Status'] = $status_imgList[$v['upload_id']];
                        }
                        if (isset($title_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Title'] = $title_imgList[$v['upload_id']];
                        }
						if (isset($pictureview_imgList[$v['upload_id']]) && $pictureview_imgList[$v['upload_id']] != 0) {
                            $input['PictureView_Id'] = $pictureview_imgList[$v['upload_id']];
							$input['HotelPicture_Title'] = '';
                        } else {
							$input['PictureView_Id'] = 0;
						}
                        if (isset($position_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Position'] = $position_imgList[$v['upload_id']];
                        }
                        $input = MyZend_Function::filterInput($input, 'array');
                        $this->HotelPicture->insertHotelPicture($input);
                        //Move file Image temp to Picture Hotel
                        copy(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name'], _PICTURE_HOTEL_PATH . '/' . 'Hotel_' . $v['image_name']);
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
        $HotelInfo = $this->Model->getListHotel(array('task' => 'getEdit', 'Id' => $id));
        $this->SessionPage->Token = $HotelInfo['Hotel_TokenPicture'];

        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParams();

            $Validator = new Zend_Validate_NotEmpty();
            if (!$Validator->isValid($Params['Name'])) {
                $this->ErrorMsg[] = $this->Msg['NoEmptyName'];
            }
			
			if ($Params['Status'] == 2) {
				$this->ErrorMsg[] = 'Khách sạn này có trạng thái "Chờ duyệt". Vui lòng thay đổi trạng thái khác.';
			}

            if (count($this->ErrorMsg) == 0) {
                $Facilities = '';
				foreach ($Params['Facilities'] as $v) {
					$Facilities .= ',' . $v;
				}
				$Facilities = substr($Facilities, 1);
                $Useful = (isset($Params['Useful'])) ? json_encode($Params['Useful']) : "";
                $input = array();
                $input['Hotel_Name'] = $Params['Name'];
                $input['Hotel_Star'] = $Params['Star'];
                $input['Hotel_Address'] = $Params['Address'];
                $input['Province_Id'] = $Params['Province'];
                $input['Hotel_Geo_Near'] = $Params['Geo_Near'];
                $input['District_Id'] = $Params['District'];
                $input['Hotel_Facilities'] = $Facilities;
                $input['Hotel_Description'] = $Params['Description'];
                $input['Hotel_Useful'] = $Useful;
                $input['Hotel_Rule_Order'] = $Params['Rule_Order'];
                $input['User_Id'] = $Params['Partner'];
                $input['Hotel_Status'] = $Params['Status'];
                $input['Hotel_Map_Lat'] = $Params['lat'];
                $input['Hotel_Map_Lng'] = $Params['lng'];
                $input['Hotel_Map_Zoom'] = $Params['zoom'];
                $input['Hotel_Room'] = $Params['Room'];
                $input['HotelType_Id'] = $Params['HotelType'];
                $input['Hotel_Phone'] = $Params['Hotel_Phone'];
                $input['Hotel_Fax'] = $Params['Hotel_Fax'];
                $input['Hotel_Website'] = $Params['Hotel_Website'];
                $input['Hotel_Commission'] = $Params['Hotel_Commission'];
                $input['Hotel_InfantAge'] = $Params['InfantAge'];
                $input['Hotel_ChildAgeTo'] = $Params['ChildAgeTo'];
                $input['Hotel_MinGuestAge'] = $Params['MinGuestAge'];
                $input['Hotel_MaxChildAge'] = $Params['MaxChildAge'];
                $input['Hotel_ChildStayFree'] = $Params['ChildStayFree'];
				$input['Hotel_Tax'] = $Params['Hotel_Tax'];
                $input = MyZend_Function::filterInput($input, 'array');
                $this->Model->updateHotel($input, $id);

                //Update Picture Hotel
                $listImgage = MyZend_Function::filterOutput($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array');
                if (count($listImgage) > 0) {
                    foreach ($listImgage as $v) {
                        $status_imgList = $_POST['status_imgListHotel'];
                        $title_imgList = $_POST['title_imgListHotel'];
                        $position_imgList = $_POST['position_imgListHotel'];
						$pictureview_imgList = $_POST['pictureview_imgListHotel'];
                        $input = array();
                        if (isset($status_imgList[$v['HotelPicture_Id']])) {
                            $input['HotelPicture_Status'] = $status_imgList[$v['HotelPicture_Id']];
                        }
                        if (isset($title_imgList[$v['HotelPicture_Id']])) {
                            $input['HotelPicture_Title'] = $title_imgList[$v['HotelPicture_Id']];
                        }
						if (isset($pictureview_imgList[$v['HotelPicture_Id']]) && $pictureview_imgList[$v['HotelPicture_Id']] != 0) {
                            $input['PictureView_Id'] = $pictureview_imgList[$v['HotelPicture_Id']];
							$input['HotelPicture_Title'] = '';
                        } else {
							$input['PictureView_Id'] = 0;
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
                                    'token' => $HotelInfo['Hotel_TokenPicture'])), 'array');
                if (count($listImageUpload) > 0) {
                    foreach ($listImageUpload as $v) {
                        $status_imgList = $_POST['status_imgList'];
                        $title_imgList = $_POST['title_imgList'];
                        $position_imgList = $_POST['position_imgList'];
						$pictureview_imgList = $_POST['pictureview_imgList'];
                        //Insert Images temp to Picture Hotel DB
                        $input = array();
                        $input['HotelPicture_Name'] = 'Hotel_' . $v['image_name'];
                        $input['HotelPicture_Token'] = $HotelInfo['Hotel_TokenPicture'];
                        if (isset($status_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Status'] = $status_imgList[$v['upload_id']];
                        }
                        if (isset($title_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Title'] = $title_imgList[$v['upload_id']];
                        }
						if (isset($pictureview_imgList[$v['upload_id']]) && $pictureview_imgList[$v['upload_id']] != 0) {
                            $input['PictureView_Id'] = $pictureview_imgList[$v['upload_id']];
							$input['HotelPicture_Title'] = '';
                        } else {
							$input['PictureView_Id'] = 0;
						}
                        if (isset($position_imgList[$v['upload_id']])) {
                            $input['HotelPicture_Position'] = $position_imgList[$v['upload_id']];
                        }
                        $input = MyZend_Function::filterInput($input, 'array');
                        $this->HotelPicture->insertHotelPicture($input);
                        //Move file Image temp to Picture Hotel
                        copy(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name'], _PICTURE_HOTEL_PATH . '/' . 'Hotel_' . $v['image_name']);
                        //Delete file Image temp
                        unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
                        //Delete db Image temp
                        $this->UploadImage->deleteUploadImage($v['upload_id']);
                    }
                }
                $this->forward('index', 'hotel', 'iadm', array(
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
        $this->view->assign('District', MyZend_Function::filterOutput($this->District->getListDistrict(array('task' => 'getWhereProvince', 'provinceId' => $HotelInfo->Province_Id)), 'array'));
        $this->view->assign('GeoNear', MyZend_Function::filterOutput($this->GeoNear->getListGeoNear(array('task' => 'getWhereProvince', 'provinceId' => $HotelInfo->Province_Id)), 'array'));
        $this->view->assign('listImage', MyZend_Function::filterOutput($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array'));
        $this->view->assign('Hotel', MyZend_Function::filterOutput($HotelInfo, 'array'));
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function deleteAction() {
        //Khong cho xoa
        $this->forward('index', 'hotel', 'iadm', array(
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
            $this->Model->deleteHotel($v);
        }
        $this->forward('index', 'hotel', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
    }

    public function mappingProvinceAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getParams();
        $result = MyZend_Function::filterOutput($this->District->getListDistrict(array('task' => 'getWhereProvince', 'provinceId' => $params['provinceId'])), 'array');
        echo json_encode($result);
    }

    public function mappingGeonearAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $params = $this->getRequest()->getParams();
        $result = MyZend_Function::filterOutput($this->GeoNear->getListGeoNear(array('task' => 'getWhereProvince', 'provinceId' => $params['provinceId'])), 'array');
        echo json_encode($result);
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
