<?php

class Hpartner_Model_Register extends MyZend_Model_DbHpartner {

	public function init() {
		parent::init();
	}

	public function regsiter($options) {
		$this->Db->beginTransaction();
		try {
			$inputUser = array();
			$inputUser['User_Name'] = $options['Name'];
			$inputUser['User_Phone'] = $options['Phone'];
			$inputUser['User_Email'] = $options['Email'];
			$inputUser['User_DateCreate'] = date('Y-m-d H:i:s');
			$inputUser['User_Status'] = 2; //Cho duyet
			$inputUser['User_Type'] = 1; //Doi tac
			$resultInsertPartner = $this->Db->insert(_PREFIX . 'user', $inputUser);
			if ($resultInsertPartner) {
				$idUserLastInsert = $this->Db->lastInsertId();
				$inpputHotel = array();
				$inpputHotel['Hotel_Name'] = $options['Hotel_Name'];
				$inpputHotel['Hotel_Star'] = $options['Star'];
				$inpputHotel['Hotel_Room'] = $options['Room'];
				$inpputHotel['Province_Id'] = $options['Province'];
				$inpputHotel['District_Id'] = $options['District'];
				$inpputHotel['HotelType_Id'] = $options['HotelType'];
				$inpputHotel['Hotel_Address'] = $options['Address'];
				$inpputHotel['Hotel_Phone'] = $options['Hotel_Phone'];
				$inpputHotel['Hotel_Fax'] = $options['Hotel_Fax'];
				$inpputHotel['Hotel_Website'] = $options['Hotel_Website'];
				$inpputHotel['Hotel_Date_create'] = date('Y-m-d H:i:s');
				$inpputHotel['Hotel_Status'] = 2; //Cho duyet
				$inpputHotel['User_Id'] = $idUserLastInsert;
				$inpputHotel['Hotel_TokenPicture'] = MyZend_Function::hasTokenPictureHotel();
				$resultInsertHotel = $this->Db->insert(_PREFIX . 'hotel', $inpputHotel);
				if ($resultInsertHotel) {
					$this->Db->commit();
				}
			}
		} catch (Zend_Exception $e) {
			$this->Db->rollback();
			Zend_Registry::get("Log")->log($e->getMessage(), Zend_Log::ERR);
			throw new Exception;
		}
		return true;
	}

}

?>
