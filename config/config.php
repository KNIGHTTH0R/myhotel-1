<?php
//Cau hinh host
//define('_HOST', 'localhost');
//define('_USER_HOST', 'maboo_db');
//define('_PASS_HOST', 'maboo_db');
//define('_DBNAME', 'maboo_db');	
//define('_PREFIX', 'tbl_');

//define('_HOST', '192.168.11.10');
//define('_USER_HOST', 'cmsadmin');
//define('_PASS_HOST', '123456789');
//define('_DBNAME', 'test_zend22');	
//define('_PREFIX', 'tbl_');

define('_HOST', 'localhost');
define('_USER_HOST', 'root');
define('_PASS_HOST', '');
define('_DBNAME', 'mytour');	
define('_PREFIX', 'tbl_');


//Cau hinh duong dan website
define('_WEB_NAME', 'hotel.vn');
define('_URL', 'http://localhost/mytour');
define('_URL_ADMIN', _URL . '/iadm');
define('_PATH', realpath(dirname(__FILE__)) . '/../');
define('_PUBLIC_URL', _URL . '/public');
define('_BACKEND_URL', _PUBLIC_URL . '/admin');
define('_PUBLIC_PATH', _PATH . '/public');
define('_BACKEND_PATH', _PUBLIC_PATH . '/admin');
define('_FRONTEND_PATH', _PUBLIC_PATH . '/FrontEnd');
define('_FRONTEND_URL', _PUBLIC_URL . '/FrontEnd');
define('_LIBRARY_URL', _URL . '/library');
define('_LIBRARY_PATH', _URL . '/library');
define('_PICTURE_PATH', _FRONTEND_PATH . '/pictures');
define('_PICTURE_URL', _FRONTEND_URL . '/pictures');
define('_THUMB_URL', _LIBRARY_URL . '/MyZend/Timthumb.php');
define('_LOG_PATH', _PATH . '/log');
define('_PICTURE_HOTEL_PATH', _PICTURE_PATH . '/hotel/hotel');
define('_PICTURE_HOTEL_URL', _PICTURE_URL . '/hotel/hotel');
define('_PICTURE_HOTEL_TEMP_PATH', _PICTURE_PATH . '/hotel/temp');
define('_PICTURE_HOTEL_TEMP_URL', _PICTURE_URL . '/hotel/temp');
define('_LOG_FILE_SIZE', 4194304);

//Cau hinh dinh dang file va kich thuoc file upload
define('_CONFIG_EXTENSION_FILE', 'jpg, png, gif, ico, bmp, jpeg');
define('_CONFIG_SIZE_FILE', 1024*1024*2); //2MB

//Cau hinh phan trang
define('_PAGE_RANGE_BACK', 10);
define('_ITEM_COUNT_PER_PAGE_BACK', 20);

define('_COOKIE_TIME', 7200*7200);

//Hotel Config
define('_HOTEL_PICTURE_PATH_ROOT', _PICTURE_PATH . '/hotel');
define('_HOTEL_PICTURE_URL_ROOT', _PICTURE_URL . '/hotel');
define('_HOTEL_PIC_PATH', _HOTEL_PICTURE_PATH_ROOT . '/hotel');
define('_HOTEL_PIC_URL', _HOTEL_PICTURE_URL_ROOT . '/hotel');
define('_HOTEL_PIC_PATH_PROVINCE', _HOTEL_PICTURE_PATH_ROOT . '/province');
define('_HOTEL_PIC_URL_PROVINCE', _HOTEL_PICTURE_URL_ROOT . '/province');

/*
 * Hotel Partner Config
 */
define('_URL_HPARTNER', _URL . '/hpartner');
define('_HPARTNER_URL', _PUBLIC_URL . '/hpartner');
define('_HPARTNER_PATH', _PUBLIC_PATH . '/hpartner');
define('_PATTERN_INVALID', '/(script|iframe|meta|form|onmouseover|object|applet|style|img|body)/');
define('_QUOTA_PICTURE_UPLOAD', 50);
define('_SIZE_PICTURE_UPLOAD', 1024*50) //500KB
?>
