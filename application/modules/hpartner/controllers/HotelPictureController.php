<?php

class Hpartner_HotelPictureController extends MyZend_Controller_AdminHpartnerAction {

	protected $ModelHotel;
	protected $ModelRoom;
	protected $Validate;
	protected $SessionPage;
	protected $HotelPicture;
	protected $UploadImage;
	protected $PictureView;

	public function init() {
		parent::init();
		$this->ModelHotel = new Hpartner_Model_Hotel();
		$this->ModelRoom = new Hpartner_Model_HotelRoom();
		$this->HotelPicture = new Hpartner_Model_HotelPicture();
		$this->UploadImage = new Hpartner_Model_UploadImage();
		$this->PictureView = new Hpartner_Model_HotelPictureView();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');

		$ListRoom = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoWhereHotel',
			'select' => array('Room_Id', 'Room_Name'),
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		$this->view->assign('ListRoom', $ListRoom);
	}

	/*	 * ********************HOTEL************************** */

	public function indexAction() {
		//Session token picture use to upload image hotel
		$this->SessionPage->Token = $this->UserPartner->HotelInfo['Hotel_TokenPicture'];

		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('type') == 'upload') {
			$params = MyZend_Function::filterInputPartner($this->getRequest()->getPost(), 'array');

			//Upload Picture Hotel
			$listImageUpload = MyZend_Function::filterOutputPartner($this->UploadImage->getListUploadImage(array(
								'task' => 'getWhereToken',
								'token' => $this->SessionPage->Token)), 'array');
			if (count($listImageUpload) > 0) {
				try {
					foreach ($listImageUpload as $v) {
						$status_imgList = $params['status_imgList'];
						$title_imgList = $params['title_imgList'];
						$position_imgList = $params['position_imgList'];
						$pictureview_imgList = $params['pictureview_imgList'];
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
						$resultInsertHotelPicture = $this->HotelPicture->insertHotelPicture($input);
						if ($resultInsertHotelPicture) {
							//Move file Image temp to Picture Hotel
							copy(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name'], _PICTURE_HOTEL_PATH . '/' . 'Hotel_' . $v['image_name']);
							$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
							//Delete db Image temp
							$resultDeleteUploadImage = $this->UploadImage->deleteUploadImage($v['upload_id'], $this->SessionPage->Token);
							if ($resultDeleteUploadImage) {
								//Delete file Image temp
								unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
							}
						} else {
							$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
						}
					}
				} catch (Zend_Exception $e) {
					Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}

		//Update picture hotel
		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('type') == 'update') {
			$params = MyZend_Function::filterInputPartner($this->getRequest()->getPost(), 'array');
			//Update Picture Hotel
			$listImgage = MyZend_Function::filterOutputPartner($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array');
			if (count($listImgage) > 0) {
				try {
					foreach ($listImgage as $v) {
						$status_imgList = $params['status_imgListHotel'];
						$title_imgList = $params['title_imgListHotel'];
						$position_imgList = $params['position_imgListHotel'];
						$pictureview_imgList = $params['pictureview_imgListHotel'];
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
						$resultUpdateHotelPicture = $this->HotelPicture->updateHotelPicture($input, $v['HotelPicture_Id'], $this->SessionPage->Token);
						if ($resultUpdateHotelPicture) {
							$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
						} else {
							$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
						}
					}
				} catch (Zend_Exception $e) {
					Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}

		//Delete Img Temp uploaded
		$PictureTemp = $this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token));
		foreach ($PictureTemp as $v) {
			unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
			$this->UploadImage->deleteUploadImage($v['upload_id']);
		}

		//Get hotel info
		$HotelInfo = $this->ModelHotel->getHotel(array(
			'task' => 'getInfo',
			'select' => array('Hotel_Name'),
			'User_Id' => $this->UserPartner->UserInfo['User_Id'])
		);
		$HotelPicture = $this->HotelPicture->getListHotelPicture(array(
			'task' => 'getWhereToken',
			'token' => $this->SessionPage->Token));

		$PictureView = $this->PictureView->getListPictureView(array('task' => 'getAll'));

		//Filter output
		$HotelInfo = MyZend_Function::filterOutputPartner($HotelInfo, 'array');
		$HotelPicture = MyZend_Function::filterOutputPartner($HotelPicture, 'array');
		$PictureView = MyZend_Function::filterOutputPartner($PictureView, 'array');

		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('HotelInfo', $HotelInfo);
		$this->view->assign('listImage', $HotelPicture);
		$this->view->assign('PictureView', $PictureView);
	}

	/*	 * ********************ROOM************************** */

	public function roomAction() {

		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('type') == 'upload') {
			$params = MyZend_Function::filterInputPartner($this->getRequest()->getPost(), 'array');

			//Upload Picture room
			$listImageUpload = MyZend_Function::filterOutputPartner($this->UploadImage->getListUploadImage(array(
								'task' => 'getWhereToken',
								'token' => $this->SessionPage->Token)), 'array');
			if (count($listImageUpload) > 0) {
				try {
					foreach ($listImageUpload as $v) {
						$status_imgList = $params['status_imgList'];
						$position_imgList = $params['position_imgList'];
						//Insert Images temp to Picture Hotel DB
						$input = array();
						$input['HotelPicture_Name'] = 'Room_' . $v['image_name'];
						$input['HotelPicture_Token'] = $this->SessionPage->Token;
						if (isset($status_imgList[$v['upload_id']])) {
							$input['HotelPicture_Status'] = $status_imgList[$v['upload_id']];
						}
						if (isset($position_imgList[$v['upload_id']])) {
							$input['HotelPicture_Position'] = $position_imgList[$v['upload_id']];
						}
						$resultInsertHotelPicture = $this->HotelPicture->insertHotelPicture($input);
						if ($resultInsertHotelPicture) {
							//Move file Image temp to Picture Hotel
							copy(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name'], _PICTURE_HOTEL_PATH . '/' . 'Room_' . $v['image_name']);
							$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
							//Delete db Image temp
							$resultDeleteUploadImage = $this->UploadImage->deleteUploadImage($v['upload_id'], $this->SessionPage->Token);
							if ($resultDeleteUploadImage) {
								//Delete file Image temp
								unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
							}
						} else {
							$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
						}
					}
				} catch (Zend_Exception $e) {
					Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}

		//Update picture room
		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('type') == 'update') {
			$params = MyZend_Function::filterInputPartner($this->getRequest()->getPost(), 'array');
			//Update Picture Hotel
			$listImgage = MyZend_Function::filterOutputPartner($this->HotelPicture->getListHotelPicture(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array');
			if (count($listImgage) > 0) {
				try {
					foreach ($listImgage as $v) {
						$status_imgList = $params['status_imgListHotel'];
						$position_imgList = $params['position_imgListHotel'];
						$input = array();
						if (isset($status_imgList[$v['HotelPicture_Id']])) {
							$input['HotelPicture_Status'] = $status_imgList[$v['HotelPicture_Id']];
						}
						if (isset($position_imgList[$v['HotelPicture_Id']])) {
							$input['HotelPicture_Position'] = $position_imgList[$v['HotelPicture_Id']];
						}
						$resultUpdateHotelPicture = $this->HotelPicture->updateHotelPicture($input, $v['HotelPicture_Id'], $this->SessionPage->Token);
						if ($resultUpdateHotelPicture) {
							$this->SuccessMsg[] = $this->Msg->getMessage('update-success');
						} else {
							$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
						}
					}
				} catch (Zend_Exception $e) {
					Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
					$this->ErrorMsg[] = $this->Msg->getMessage('update-fail');
				}
			}
		}

		$idRoom = MyZend_Function::filterInputPartner($this->getRequest()->getParam('id'));
		//Get info Hotel by Room ID
		$RoomInfo = $this->ModelRoom->getHotelRoom(array(
			'task' => 'getInfoOneRoom',
			'select' => array('r.Room_Name', 'r.Room_TokenPicture'),
			'Room_Id' => $idRoom,
			'Hotel_Id' => $this->UserPartner->HotelInfo['Hotel_Id']));
		//Filter out put Room info
		$RoomInfo = MyZend_Function::filterOutputPartner($RoomInfo, 'array');
		//Set session token picture
		$this->SessionPage->Token = $RoomInfo['Room_TokenPicture'];
		//Delete Img Temp uploaded
		$PictureTemp = $this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token));
		foreach ($PictureTemp as $v) {
			unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
			$this->UploadImage->deleteUploadImage($v['upload_id']);
		}
		//Get Picture of Room where Token's Room
		$HotelPicture = $this->HotelPicture->getListHotelPicture(array(
			'task' => 'getWhereToken',
			'token' => $this->SessionPage->Token));
		$HotelPicture = MyZend_Function::filterOutputPartner($HotelPicture, 'array');

		$this->view->assign('ErrorMsg', $this->ErrorMsg);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('HotelPicture', $HotelPicture);
		$this->view->assign('RoomInfo', $RoomInfo);
	}

	public function delPicTempAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$params = MyZend_Function::filterInputPartner($this->getRequest()->getParams(), 'array');
		$result = $this->UploadImage->deleteUploadImage($params['id'], $this->SessionPage->Token);
		if ($result) {
			unlink(_PICTURE_HOTEL_TEMP_PATH . '/' . $params['image']);
		}
	}

	public function delPicHotelAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$params = MyZend_Function::filterInputPartner($this->getRequest()->getParams(), 'array');
		$result = $this->HotelPicture->deleteHotelPicture($params['id'], $this->SessionPage->Token);
		if ($result) {
			unlink(_PICTURE_HOTEL_PATH . '/' . $params['image']);
		}
	}

}

?>
