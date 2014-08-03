<?php

class MyZend_TemplateMail {

	public function getTemplateRegisPartner($params) {
		$strTemplate = '';
		$strTemplate .= '<strong>Xin chào</strong> Bạn!<br>
                    ' . _WEB_NAME . ' cảm ơn bạn đã đăng ký sử dụng dịch vụ quản lý khách sạn. <br>
                    Sau đây là thông tin đã đăng ký:<br>
                    <ul>
                        <li><strong>Khách sạn:</strong> ' . $params['Hotel_Name'] . '</li>
                        <li><strong>Địa chỉ:</strong> ' . $params['Address'] . '</li>
                        <li><strong>Số lượng phòng:</strong> ' . $params['Room'] . '</li>
                        <li><strong>Số sao:</strong> ' . $params['Star'] . '</li>
                        <li><strong>Tài khoản:</strong> ' . $params['Email'] . '</li>
                        <li><strong>Số điện thoại liên hệ:</strong> ' . $params['Phone'] . '</li>
                    </ul>
                    <strong>Lưu ý rằng:</strong> Tài khoản quản lý của bạn vẫn chưa được kích hoạt, Chúng tôi sẽ kiểm tra sự tin cậy những thông tin mà bạn đã đăng ký trong vòng từ 3 - 12 tiếng. Ngay sau khi tài khoản được kích hoạt, chúng tôi sẽ thông báo vào Email hoặc Điện Thoại của bạn, và bạn có thể bắt đầu sử dụng chương trình. Liên hệ 08.6660.7451 hoặc hotline 0933.186.166 để được hỗ trợ.
                    <br>
                    <strong>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi !</strong>';
		return $strTemplate;
	}
	
	public function getTemplateConfirmRegisPartnerSuccess($params) {
		$strTemplate = '';
		$strTemplate .= '<strong>Xin chào</strong> Bạn!<br>
                    ' . _WEB_NAME . ' cảm ơn bạn đã đăng ký sử dụng dịch vụ quản lý khách sạn. <br>
                    Tài khoản của bạn đã được kích hoạt. Sau đây là thông tin đã xác thực:<br>
                    <ul>
                        <li><strong>Khách sạn:</strong> ' . $params['Hotel_Name'] . '</li>
                        <li><strong>Địa chỉ:</strong> ' . $params['Address'] . '</li>
                        <li><strong>Số lượng phòng:</strong> ' . $params['Room'] . '</li>
                        <li><strong>Số sao:</strong> ' . $params['Star'] . '</li>
                        <li><strong>Tài khoản:</strong> ' . $params['Email'] . '</li>
						<li><strong>Mật khẩu:</strong> ' . $params['Password'] . '</li>	
                        <li><strong>Số điện thoại liên hệ:</strong> ' . $params['Phone'] . '</li>
                    </ul>
                    <strong>Lưu ý rằng:</strong> Tài khoản quản lý của bạn đã được xác thực và có thể sử dụng. Liên hệ 08.6660.7451 hoặc hotline 0933.186.166 để được hỗ trợ.
                    <br>
                    <strong>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi !</strong>';
		return $strTemplate;
	}
	
	public function getTemplateForgotPassword($params) {
		$strTemplate = '';
		$strTemplate .= '<strong>Xin chào</strong> Bạn!<br>
                    ' . _WEB_NAME . ' cảm ơn bạn đã đăng ký sử dụng dịch vụ quản lý khách sạn. <br>
                    Mật khẩu tài khoản của bạn đã được thay đổi. Sau đây là thông tin:<br>
                    <ul>
                        <li><strong>Tài khoản:</strong> ' . $params['Email'] . '</li>
                        <li><strong>Mật khẩu mới:</strong> ' . $params['Password'] . '</li>
                    </ul>
                    <br>Liên hệ 08.6660.7451 hoặc hotline 0933.186.166 để được hỗ trợ.
                    <br>
                    <strong>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi !</strong>';
		return $strTemplate;
	}

}

?>
