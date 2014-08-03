<?php

class Hpartner_UploadImageController extends MyZend_Controller_AdminHpartnerAction {

    protected $SessionPage;
    protected $UploadImage;
    protected $PictureView;
    protected $MethodGetBodyPicture;
    protected $HotelPicture;

    public function init() {
        parent::init();
        $this->SessionPage = new Zend_Session_Namespace('SessionPage');
        $this->UploadImage = new Hpartner_Model_UploadImage();
        $this->PictureView = new Hpartner_Model_HotelPictureView();
        $this->HotelPicture = new Hpartner_Model_HotelPicture();

        //Delete temp picture old
        $timeLast = time() - (60 * 30); //30 minute
        $imgTemp = $this->UploadImage->getListUploadImage(array('task' => 'getAll'));
        foreach ($imgTemp as $v) {
            if ($v['created'] < $timeLast) {
                unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
                $this->UploadImage->deleteUploadImage($v['upload_id']);
            }
        }
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function uploadRoomAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->MethodGetBodyPicture = 'getRoomBodyPicture';
        $this->uploadProcess();
    }

    public function uploadAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->MethodGetBodyPicture = 'getBodyPicture';
        $this->uploadProcess();
    }

    function uploadProcess() {
        $HotelPicture = $this->HotelPicture->getListHotelPicture(array(
            'task' => 'getWhereToken',
            'token' => $this->SessionPage->Token));
        $countPictureHotel = count($HotelPicture);
        
        $nameMethodGetBodyPicture = $this->MethodGetBodyPicture;
        $valid_formats = explode(", ", _CONFIG_EXTENSION_FILE);
        $Error = 0;
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            try {
                $uploaddir = _PICTURE_HOTEL_TEMP_PATH . "/"; //a directory inside
                $uploadpurl = _PICTURE_HOTEL_TEMP_URL . "/";
                $picCount = 0;
                $picCount += $countPictureHotel;

                foreach ($_FILES['photos']['name'] as $name => $value) {
                    $filename = stripslashes($_FILES['photos']['name'][$name]);
                    $size = filesize($_FILES['photos']['tmp_name'][$name]);
                    //get the extension of the file in a lower case format
                    $ext = $this->getExtension($filename);
                    $ext = strtolower($ext);

                    if (in_array($ext, $valid_formats)) {
                        if ($size < _SIZE_PICTURE_UPLOAD) {
                            if ($picCount < _QUOTA_PICTURE_UPLOAD) {
                                $image_name = time() . $filename;
                                $newname = $uploaddir . $image_name;
                                if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) {
                                    $time = time();
                                    $input = array();
                                    $input['image_name'] = $image_name;
                                    $input['token'] = $this->SessionPage->Token;
                                    $input['created'] = $time;
                                    $input = MyZend_Function::filterInputPartner($input, 'array');
                                    $result = $this->UploadImage->insertUploadImage($input);
                                    if (!$result) {
                                        echo '<div class="form_alert form_alert-error">' . MyZend_Message::getMessage('upload-error') . '</div>';
                                        $Error = 1;
										break;
                                    } else {
                                        $picCount++;
                                    }
                                } else {
                                    echo '<div class="form_alert form_alert-error">' . MyZend_Message::getMessage('upload-error') . '</div>';
                                    $Error = 1;
									break;
                                }
                            } else {
                                echo '<div class="form_alert form_alert-error">' . MyZend_Message::getMessage('max-quote-picture-upload') . '</div>';
                                $Error = 1;
								break;
                            }
                        } else {
                            echo '<div class="form_alert form_alert-error">' . MyZend_Message::getMessage('max-size-upload') . '</div>';
                            $Error = 1;
							break;
                        }
                    } else {
                        echo '<div class="form_alert form_alert-error">' . MyZend_Message::getMessage('invalid-file-upload') . '</div>';
                        $Error = 1;
						break;
                    }
                }
                if ($Error == 0) {
                    echo $this->{$nameMethodGetBodyPicture}($uploadpurl);
                }
            } catch (Zend_Exception $e) {
                Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
                echo '<div class="form_alert form_alert-error">' . MyZend_Message::getMessage('upload-error') . '</div>';
            }
        }
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    function getBodyPicture($uploadpurl) {
        $str = '';
        $listImg = MyZend_Function::filterOutputPartner($this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array');
        $PictureView = MyZend_Function::filterOutputPartner($this->PictureView->getListPictureView(array('task' => 'getAll')), 'array');
        $str .= '<ul id="sortable">';
        foreach ($listImg as $v) {
            $str .= '<li id="li_imgList_' . $v['upload_id'] . '" class="ui-state-default">
					<div class="del_img" onclick="delPicTemp(' . $v['upload_id'] . ', \'' . $v['image_name'] . '\', this)">
					<img src="' . _HPARTNER_URL . '/images/icon_delete.png" />	
					</div>
					<table class="table_img_list" id="table_img_list_' . $v['upload_id	'] . '">';
            $str .= '<tr><td class="img">';
            $str .= "<img src='" . MyZend_Function::thumb($uploadpurl . $v['image_name'], 106, 106) . "' class='imgList'>";
            $str .= '</td></tr>';
            $str .= '<tr><td>';
            $str .= '<select name="pictureview_imgList[' . $v['upload_id'] . ']">';
            $str .= '<option value="0">Chọn tiêu đề ảnh</option>';
            foreach ($PictureView as $vv) {
                $str .= '<option value="' . $vv['PictureView_Id'] . '">' . $vv['PictureView_Name'] . '</option>';
            }
            $str .= '</select>';
            $str .= '</td></tr>';
            $str .= '<tr><td><select name="status_imgList[' . $v['upload_id'] . ']"><option value="1" checked="checked">Hiển thị</option><option value="0">Ẩn</option></select></td></tr>';
            $str .= '<tr><td><input placeholder="Tiêu đề ảnh" type="text" name="title_imgList[' . $v['upload_id'] . ']" value="" /></td></tr>';
            $str .= '</table><input type="hidden" name="position_imgList[' . $v['upload_id'] . ']" class="position_imgList" value="" /></li>';
        }
        $str .= '</ul>';
        return $str;
    }

    function getRoomBodyPicture($uploadpurl) {
        $str = '';
        $listImg = MyZend_Function::filterOutputPartner($this->UploadImage->getListUploadImage(array('task' => 'getWhereToken', 'token' => $this->SessionPage->Token)), 'array');
        $str .= '<ul id="sortable">';
        foreach ($listImg as $v) {
            $str .= '<li id="li_imgList_' . $v['upload_id'] . '" class="ui-state-default">
					<div class="del_img" onclick="delPicTemp(' . $v['upload_id'] . ', \'' . $v['image_name'] . '\', this)">
					<img src="' . _HPARTNER_URL . '/images/icon_delete.png" />	
					</div>
					<table class="table_img_list" id="table_img_list_' . $v['upload_id	'] . '">';
            $str .= '<tr><td class="img">';
            $str .= "<img src='" . MyZend_Function::thumb($uploadpurl . $v['image_name'], 106, 106) . "' class='imgList'>";
            $str .= '</td></tr>';
            $str .= '<tr><td><select name="status_imgList[' . $v['upload_id'] . ']"><option value="1" checked="checked">Hiển thị</option><option value="0">Ẩn</option></select></td></tr>';
            $str .= '</table><input type="hidden" name="position_imgList[' . $v['upload_id'] . ']" class="position_imgList" value="" /></li>';
        }
        $str .= '</ul>';
        return $str;
    }

}

?>
