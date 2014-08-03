<?php

class MyZend_Function {

	public static function setLayout($layoutPath, $layoutFile) {
		$optionLayout = array('layoutPath' => $layoutPath, 'layout' => $layoutFile);
		Zend_Layout::startMvc($optionLayout);
	}

	public static function hasPassword($password) {
		$passHash = md5(md5($password));
		return $passHash;
	}

	public static function hasTokenPictureHotel() {
		return md5(time() . microtime());
	}

	public static function getRandPassword() {
		$str = rand(1, 9999999);
		return $str;
	}

	public static function formatTitleSeo($str) {
		if (!$str) {
			return false;
		}
		$str = strip_tags($str);
		$unicode = array(
			'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'd' => 'đ', 'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'i' => 'í|ì|ỉ|ĩ|ị', 'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
			'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'D' => 'Đ',
			'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
			'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',);
		foreach ($unicode as $nonUnicode => $uni) {
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		}
		$strText = preg_replace('/[^A-Za-z0-9-]/', ' ', $str);
		$strText = preg_replace('/ +/', ' ', $strText);
		$strText = trim($strText);
		$strText = str_replace(' ', '-', $strText);
		$strText = preg_replace('/-+/', '-', $strText);
		$strText = preg_replace("/-$/", "", $strText);

		return strtolower($strText);
	}

	public static function getPaginator($options = array()) {
		$adapter = new Zend_Paginator_Adapter_Null($options['Total']);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setPageRange(_PAGE_RANGE_BACK);
		$paginator->setItemCountPerPage(_ITEM_COUNT_PER_PAGE_BACK);
		$paginator->setCurrentPageNumber($options['CurrentPageNumber']);
		return $paginator;
	}

	public static function upoadFile($nameFile, $path, $input, $prefix = null) {
		$file = new Zend_File_Transfer_Adapter_Http();
		$arrFile = explode('.', $nameFile);
		$ex1 = array_pop($arrFile);
		$pictureRename = $prefix . $arrFile[0] . '_' . time() . '.' . $ex1;
		$file->setDestination($path . '/');
		$file->addFilter('Rename', $pictureRename, $input);
		$file->receive($input);
		return $pictureRename;
	}

	public static function formatDateDMY_YMDHIS($date) {
		if ($date != '') {
			$arrDate = explode('-', $date);
			$arrTime = date("H:i:s");
			$dateFormat = $arrDate[2] . '-' . $arrDate[1] . '-' . $arrDate[0] . ' ' . $arrTime;
			return $dateFormat;
		}
	}

	public static function formatDateDMY_YMD($date, $plit = "-") {
		if ($date != '') {
			$arrDate = explode($plit, $date);
			$dateFormat = $arrDate[2] . '-' . $arrDate[1] . '-' . $arrDate[0];
			return $dateFormat;
		}
	}

	public static function formatDateYMDHIS_DMYHIS($date) {
		if ($date != '') {
			$arrDateTime = explode(' ', $date);
			$arrDate = explode('-', $arrDateTime[0]);
			$dateFormat = $arrDate[2] . '-' . $arrDate[1] . '-' . $arrDate[0] . ' ' . $arrDateTime[1];
			return $dateFormat;
		}
	}

	public static function formatDateYMDHIS_DMY($date, $plit = "-") {
		if ($date != '') {
			$arrDateTime = explode(' ', $date);
			$arrDate = explode('-', $arrDateTime[0]);
			$dateFormat = $arrDate[2] . $plit . $arrDate[1] . $plit . $arrDate[0];
			return $dateFormat;
		}
	}

	public static function formatPriceAddCommas($price) {
		$current = new Zend_Currency(array('display' => Zend_Currency::NO_SYMBOL, 'precision' => 0, 'format' => 'de'));
		$priceFormat = $current->setValue($price);
		return $priceFormat;
	}

	public static function formatPriceRemoveCommas($price) {
		$priceFormat = str_replace('.', '', $price);
		return $priceFormat;
	}

	public static function filterInput($input, $type = 'string', $notFilter = array()) {
		$str = '';
		switch ($type) {
			case 'string':
				$str = addslashes(trim($input));
				break;
			case 'array':
				foreach ($input as $k => $v) {
					//array 2 chieu
					if (is_array($v)) {
						foreach ($v as $kk => $vv) {
							if (!in_array($kk, $notFilter)) {
								$str[$k][$kk] = addslashes(trim($vv));
							} else {
								$str[$k][$kk] = $vv;
							}
						}
						//array don    
					} else {
						if (!in_array($k, $notFilter)) {
							$str[$k] = addslashes(trim($v));
						} else {
							$str[$k] = $v;
						}
					}
				}
				break;
		}
		return $str;
	}

	public static function filterOutput($output, $type = 'string', $notFilter = array()) {
		$str = '';
		switch ($type) {
			case 'string':
				$str = stripslashes(htmlspecialchars(trim($output)));
				break;
			case 'array':
				foreach ($output as $k => $v) {
					//array 2 chieu
					if (is_array($v)) {
						foreach ($v as $kk => $vv) {
							if (!in_array($kk, $notFilter)) {
								$str[$k][$kk] = stripslashes(htmlspecialchars(trim($vv)));
							} else {
								$str[$k][$kk] = $vv;
							}
						}
						//array don    
					} else {
						if (!in_array($k, $notFilter)) {
							$str[$k] = stripslashes(htmlspecialchars(trim($v)));
						} else {
							$str[$k] = $v;
						}
					}
				}
				break;
		}
		return $str;
	}

	public static function filterInputPartner($input, $type = 'string', $notFilter = array()) {
		$str = '';
		switch ($type) {
			case 'string':
				$str = addslashes(trim(self::safeInput($input)));
				break;
			case 'array':
				foreach ($input as $k => $v) {
					//array 2 chieu
					if (is_array($v)) {
						foreach ($v as $kk => $vv) {
							if (!in_array($kk, $notFilter)) {
								$str[$k][$kk] = addslashes(trim(self::safeInput($vv)));
							} else {
								$str[$k][$kk] = $vv;
							}
						}
						//array don    
					} else {
						if (!in_array($k, $notFilter)) {
							$str[$k] = addslashes(trim(self::safeInput($v)));
						} else {
							$str[$k] = $v;
						}
					}
				}
				break;
		}
		return $str;
	}

	public static function filterOutputPartner($output, $type = 'string', $notFilter = array()) {
		$str = '';
		switch ($type) {
			case 'string':
				$str = stripslashes(trim(self::safeOutput($output)));
				break;
			case 'array':
				foreach ($output as $k => $v) {
					//array 2 chieu
					if (is_array($v)) {
						foreach ($v as $kk => $vv) {
							if (!in_array($kk, $notFilter)) {
								$str[$k][$kk] = stripslashes(trim(self::safeOutput($vv)));
							} else {
								$str[$k][$kk] = $vv;
							}
						}
						//array don    
					} else {
						if (!in_array($k, $notFilter)) {
							$str[$k] = stripslashes(trim(self::safeOutput($v)));
						} else {
							$str[$k] = $v;
						}
					}
				}
				break;
		}
		return $str;
	}

	public static function safeInput($input) {
		$filter = new Zend_Filter_PregReplace(array(
			'match' => array(_PATTERN_INVALID, '/&quot;/'),
			'replace' => array('', '\"'))
		);
		return $filter->filter($input);
	}
	
	public static function safeOutput($input) {
		$filter = new Zend_Filter_PregReplace(array(
			'match' => array(_PATTERN_INVALID, '/\\"/'),
			'replace' => array('', '&quot;'))
		);
		return $filter->filter($input);
	}

	public static function thumb($url, $width = null, $height = null) {
		$strUrl = _THUMB_URL . "?src=" . $url;
		if ($width != null) {
			$strUrl .= '&amp;w=' . $width;
		}
		if ($height != null) {
			$strUrl .= '&amp;h=' . $height;
		}
		return $strUrl;
	}

}

?>