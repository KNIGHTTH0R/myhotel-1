<?php

class Iadm_UploadImageController extends MyZend_Controller_AdminAction {

	protected $SessionPage;
	protected $UploadImage;

	public function init() {
		parent::init();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
		$this->UploadImage = new Iadm_Model_UploadImage();
	}

	public function indexAction() {

		$this->_helper->layout()->disableLayout();
		$timeLast = time() - (60 * 30); //30 minute
		$imgTemp = $this->UploadImage->getListUploadImage(array('task' => 'getAll'));
		foreach ($imgTemp as $v) {
			if ($v['created'] < $timeLast) {
				unlink(_PICTURE_HOTEL_TEMP_PATH . "/" . $v['image_name']);
				$this->UploadImage->deleteUploadImage($v['upload_id']);
			}
		}
	}

	public function uploadAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();



		//$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");
		$valid_formats = explode(", ", _CONFIG_EXTENSION_FILE);

		if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

			$uploaddir = _PICTURE_HOTEL_TEMP_PATH . "/"; //a directory inside
			$uploadpurl = _PICTURE_HOTEL_TEMP_URL . "/";
			foreach ($_FILES['photos']['name'] as $name => $value) {

				$filename = stripslashes($_FILES['photos']['name'][$name]);
				$size = filesize($_FILES['photos']['tmp_name'][$name]);
				//get the extension of the file in a lower case format
				$ext = $this->getExtension($filename);
				$ext = strtolower($ext);

				if (in_array($ext, $valid_formats)) {
					if ($size < _CONFIG_SIZE_FILE) {
						$image_name = time() . $filename;
						echo "<img src='" . MyZend_Function::thumb($uploadpurl . $image_name, 180) . "' class='imgList'>";
						$newname = $uploaddir . $image_name;
						if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) {
							$time = time();
							$input = array();
							$input['image_name'] = $image_name;
							$input['token'] = $this->SessionPage->Token;
							$input['created'] = $time;
							$this->UploadImage->insertUploadImage($input);
						} else {
							echo '<span class="imgList">Hình ảnh vượt quá giới hạn! Upload thất bại!</span>';
						}
					} else {
						echo '<span class="imgList">Kích thước hình ảnh quá giới hạn!</span>';
					}
				} else {
					echo '<span class="imgList">Tập tin hình ảnh không hợp lệ</span>';
				}
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

}

?>
