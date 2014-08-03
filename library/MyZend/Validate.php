<?php

class MyZend_Validate {

	public $Validate;

	public function __construct() {
		$this->Validate = new stdClass();
		//Not empty
		$this->NotEmpty();
		//Input valid
		$this->InputInvalid();
		//Digit
		$this->Digit();
		//Email
		$this->Email();
		return $this->Validate;
	}
	
	public function NotEmpty() {
		$this->Validate->Null = new Zend_Validate_NotEmpty();
	}
	
	public function InputInvalid() {
		$this->Validate->InputInvalid = new Zend_Validate_Regex(array('pattern' => _PATTERN_INVALID));
	}
	
	public function Digit() {
		$this->Validate->Digit = new Zend_Validate_Digits();
	}
	
	public function Email() {
		$this->Validate->Email = new Zend_Validate_EmailAddress();
	}
}

?>
