<?php

class MyZend_SendMail {

	protected $HostSMTP;
	protected $UserSMTP;
	protected $PassSMTP;
	protected $Auth = 'login';
	protected $Ssl = 'ssl';
	protected $Port = '465';
	protected $From;
	protected $To;
	protected $Subject;
	Protected $Content;

	public function __construct($options = '') {
		if ($options != '' && is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function setOptions($options) {
		$this->HostSMTP = $options['HostSMTP'];
		$this->UserSMTP = $options['UserSMTP'];
		$this->PassSMTP = $options['PassSMTP'];
		($options['Auth']) ? $this->Auth = $options['Auth'] : "";
		($options['Ssl']) ? $this->Ssl = $options['Ssl'] : "";
		($options['Port']) ? $this->Port = $options['Port'] : "";

		$this->From = $options['From'];
		$this->To = $options['To'];
		$this->Subject = $options['Subject'];
		$this->Content = $options['Content'];
	}

	public function TransportSmtp() {
		$connmail = new Zend_Mail_Transport_Smtp($this->HostSMTP, array(
			'username' => $this->UserSMTP,
			'password' => $this->PassSMTP,
			'auth' => $this->Auth,
			'ssl' => $this->Ssl,
			'port' => $this->Port,
		));
		Zend_Mail::setDefaultTransport($connmail);
	}

	public function send() {
		$this->TransportSmtp();
		$mail = new Zend_Mail('UTF-8');
		$mail->setBodyHtml($this->Content);
		$mail->setFrom($this->From, _WEB_NAME);
		$mail->addTo($this->To);
		$mail->setSubject($this->Subject);
		$mail->send();
	}

}

?>
