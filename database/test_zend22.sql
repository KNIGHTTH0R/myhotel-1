-- phpMyAdmin SQL Dump
-- version 3.0.0
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2014 at 04:57 PM
-- Server version: 5.0.95
-- PHP Version: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test_zend22`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_access`
--

CREATE TABLE IF NOT EXISTS `tbl_admin_access` (
  `Id` int(11) NOT NULL auto_increment,
  `Username` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Password` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Email` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `Parent` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_admin_access`
--

INSERT INTO `tbl_admin_access` (`Id`, `Username`, `Password`, `Name`, `Email`, `Parent`, `Status`) VALUES
(1, 'admin', '550e1bafe077ff0b0b67f4e32f29d751', 'Đào Anh Tuấn', 'anhtuan15071987@gmail.com', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content`
--

CREATE TABLE IF NOT EXISTS `tbl_content` (
  `Id` bigint(20) NOT NULL auto_increment,
  `Title` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Body` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tbl_content`
--

INSERT INTO `tbl_content` (`Id`, `Title`, `Body`, `Status`) VALUES
(16, '<script>', '<p><img alt=\\"\\" src=\\"/public/FrontEnd/pictures/editor/images/Hotel_1403361920PROVINCE_da_lat_250_250_crop_1390224790%20-%20Copy.jpg\\" style=\\"height:250px; width:250px\\" /><img alt=\\"\\" src=\\"/public/FrontEnd/pictures/editor/images/Hotel_1403361920PROVINCE_da_lat_250_250_crop_1390224790%20-%20Copy.jpg\\" style=\\"height:250px; width:250px\\" /></p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_system`
--

CREATE TABLE IF NOT EXISTS `tbl_email_system` (
  `Id` int(11) NOT NULL,
  `HostSMTP` varchar(50) character set utf8 collate utf8_unicode_ci default NULL,
  `UserSMTP` varchar(50) character set utf8 collate utf8_unicode_ci default NULL,
  `PassSMTP` varchar(50) character set utf8 collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_email_system`
--

INSERT INTO `tbl_email_system` (`Id`, `HostSMTP`, `UserSMTP`, `PassSMTP`) VALUES
(1, 'smtp.gmail.com', 'anhtuan15071987@gmail.com', 'anhtuan1507');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel` (
  `Hotel_Id` bigint(11) NOT NULL auto_increment,
  `Hotel_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Hotel_Address` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Province_Id` int(11) NOT NULL,
  `District_Id` int(11) NOT NULL,
  `Hotel_Facilities` text character set utf8 collate utf8_unicode_ci,
  `Hotel_Description` text character set utf8 collate utf8_unicode_ci,
  `Hotel_Useful` text character set utf8 collate utf8_unicode_ci,
  `Hotel_Rule_Order` text character set utf8 collate utf8_unicode_ci,
  `Hotel_Status` int(11) NOT NULL,
  `User_Id` bigint(11) NOT NULL,
  `Hotel_Map_Lat` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `Hotel_Map_Lng` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `Hotel_Date_create` datetime NOT NULL,
  `Hotel_Map_Zoom` varchar(255) NOT NULL,
  `Hotel_Star` int(11) NOT NULL,
  `Hotel_Geo_Near` int(11) NOT NULL,
  `Hotel_View` bigint(20) NOT NULL,
  `Hotel_TokenPicture` text NOT NULL,
  `Hotel_Room` int(11) NOT NULL,
  `HotelType_Id` int(11) NOT NULL,
  `Hotel_Fax` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Hotel_Phone` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Hotel_Website` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Hotel_Commission` int(11) NOT NULL default '5',
  `Hotel_InfantAge` int(11) NOT NULL,
  `Hotel_ChildAgeTo` int(11) NOT NULL,
  `Hotel_MinGuestAge` int(11) NOT NULL,
  `Hotel_MaxChildAge` int(11) NOT NULL,
  `Hotel_ChildStayFree` int(11) NOT NULL,
  `Hotel_Tax` int(11) NOT NULL default '10',
  PRIMARY KEY  (`Hotel_Id`),
  KEY `UserId_HotelStatus` (`User_Id`,`Hotel_Status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `tbl_hotel`
--

INSERT INTO `tbl_hotel` (`Hotel_Id`, `Hotel_Name`, `Hotel_Address`, `Province_Id`, `District_Id`, `Hotel_Facilities`, `Hotel_Description`, `Hotel_Useful`, `Hotel_Rule_Order`, `Hotel_Status`, `User_Id`, `Hotel_Map_Lat`, `Hotel_Map_Lng`, `Hotel_Date_create`, `Hotel_Map_Zoom`, `Hotel_Star`, `Hotel_Geo_Near`, `Hotel_View`, `Hotel_TokenPicture`, `Hotel_Room`, `HotelType_Id`, `Hotel_Fax`, `Hotel_Phone`, `Hotel_Website`, `Hotel_Commission`, `Hotel_InfantAge`, `Hotel_ChildAgeTo`, `Hotel_MinGuestAge`, `Hotel_MaxChildAge`, `Hotel_ChildStayFree`, `Hotel_Tax`) VALUES
(24, 'Hotel A', 'test', 1, 1, '', '', '{\\"25\\":\\"\\",\\"24\\":\\"\\",\\"23\\":\\"\\",\\"22\\":\\"\\",\\"21\\":\\"\\",\\"20\\":\\"\\",\\"19\\":\\"\\",\\"18\\":\\"\\",\\"17\\":\\"\\",\\"16\\":\\"\\",\\"15\\":\\"\\",\\"14\\":\\"\\",\\"13\\":\\"\\",\\"12\\":\\"\\",\\"11\\":\\"\\",\\"10\\":\\"\\",\\"9\\":\\"\\",\\"8\\":\\"\\"}', '', 1, 19, '14.013524563270797', '107.95192297265623', '2014-07-24 17:00:26', '10', 4, 0, 0, '5cbb99b24142ed4e23f769c1369d0f4b', 100, 10, '', '09234234324', '', 5, 0, 0, 0, 0, 0, 10),
(25, 'Hotel C', 'test', 1, 7, NULL, NULL, NULL, NULL, 2, 21, NULL, NULL, '2014-07-25 14:47:23', '', 2, 0, 0, 'e5b3af967d77201019ba716f6526591c', 100, 9, '', '12312312', '', 5, 0, 0, 0, 0, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_district`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_district` (
  `District_Id` bigint(11) NOT NULL auto_increment,
  `Province_Id` int(11) NOT NULL,
  `District_Name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `District_Status` int(11) NOT NULL,
  PRIMARY KEY  (`District_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `tbl_hotel_district`
--

INSERT INTO `tbl_hotel_district` (`District_Id`, `Province_Id`, `District_Name`, `District_Status`) VALUES
(1, 1, 'Quận 1', 1),
(2, 1, 'Quận 2', 1),
(3, 1, 'Quận 3', 1),
(4, 1, 'Quận 4', 1),
(5, 1, 'Quận 5', 1),
(6, 1, 'Quận 6', 1),
(7, 1, 'Quận 7', 1),
(8, 1, 'Quận 8', 1),
(9, 1, 'Quận 9', 1),
(10, 1, 'Quận 10', 1),
(11, 1, 'Quận 11', 1),
(12, 1, 'Quận 12', 1),
(13, 1, 'Bình Thạnh', 1),
(14, 1, 'Phú Nhuận', 1),
(15, 1, 'Thủ Đức', 1),
(16, 1, 'Huyện Nhà Bè', 1),
(17, 1, 'Tân Bình', 1),
(18, 1, 'Bình Tân', 1),
(19, 1, '\\"test\\"', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_facilities`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_facilities` (
  `Facilities_Id` bigint(11) NOT NULL auto_increment,
  `Facilities_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Facilities_Picture` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Facilities_Status` int(11) NOT NULL,
  PRIMARY KEY  (`Facilities_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `tbl_hotel_facilities`
--

INSERT INTO `tbl_hotel_facilities` (`Facilities_Id`, `Facilities_Name`, `Facilities_Picture`, `Facilities_Status`) VALUES
(31, 'dịch vụ trông trẻ', '', 1),
(30, 'dịch vụ phòng 24 giờ', '', 1),
(29, 'dịch vụ làm đẹp', '', 1),
(28, 'dịch vụ internet', '', 1),
(27, 'dịch vụ giặt là', '', 1),
(26, 'dịch vụ đưa đón', '', 1),
(25, 'dịch vụ du lịch', '', 1),
(24, 'cửa hàng lưu niệm', '', 1),
(22, 'cho thuê xe', '', 1),
(23, 'cho thuê xe đạp', '', 1),
(17, 'báo chí', '', 1),
(18, 'bếp chung', '', 1),
(19, 'các phòng đều có wifi miễn phí', '', 1),
(20, 'câu lạc bộ đêm', '', 1),
(21, 'cho phép mang theo vật nuôi', '', 1),
(15, 'bãi đỗ xe', '', 1),
(32, 'dịch vụ trông xe', '', 1),
(33, 'đưa đón khách sạn/sân bay', '', 1),
(34, 'dụng cụ các món nướng', '', 1),
(35, 'giặt khô', '', 1),
(36, 'két sắt', '', 1),
(37, 'lưu trữ hành lý', '', 1),
(38, 'máy atm rút tiền bên', '', 1),
(39, 'máy bán hàng tự động', '', 1),
(40, 'người vận chuyển hành lý', '', 1),
(41, 'nhà chờ chung / khu vực tivi', '', 1),
(42, 'nhà hàng', '', 1),
(43, 'phòng gia đình', '', 1),
(44, 'phòng hút thuốc', '', 1),
(45, 'phục vụ ăn tại phòng', '', 1),
(46, 'quán bar cạnh bể bơi', '', 1),
(47, 'quán cà phê', '', 1),
(48, 'quầy lễ tân 24 giờ', '', 1),
(49, 'quầy nước', '', 1),
(50, 'sòng bài', '', 1),
(51, 'tầng cao cấp', '', 1),
(52, 'thang máy', '', 1),
(53, 'thiết bị phòng họp', '', 1),
(54, 'thu đổi ngoại tệ', '', 1),
(55, 'thủ tục nhận phòng/trả phòng nhanh', '', 1),
(56, 'thư viện', '', 1),
(57, 'tiện nghi cho người khuyết tật', '', 1),
(58, 'tủ đựng đồ có khoá', '', 1),
(59, 'Wi-Fi ở khu vực công cộng', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_geo_near`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_geo_near` (
  `GeoNear_Id` bigint(11) NOT NULL auto_increment,
  `GeoNear_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Province_Id` int(11) NOT NULL,
  `GeoNear_Status` int(11) NOT NULL,
  `GeoNear_Lat` varchar(255) NOT NULL,
  `GeoNear_Lng` varchar(255) NOT NULL,
  `GeoNear_Zoom` varchar(255) NOT NULL,
  PRIMARY KEY  (`GeoNear_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_hotel_geo_near`
--

INSERT INTO `tbl_hotel_geo_near` (`GeoNear_Id`, `GeoNear_Name`, `Province_Id`, `GeoNear_Status`, `GeoNear_Lat`, `GeoNear_Lng`, `GeoNear_Zoom`) VALUES
(1, 'Chợ Bến Thành, Khu Trung Tâm, Quận 1 - Ven Sông', 1, 1, '10.774498349068576', '106.70742090509032', '15'),
(2, 'Chợ Bà Chiểu', 1, 1, '10.802485200908814', '106.69853742883299', '18'),
(3, 'Chợ Bến Thành', 1, 1, '10.772748755008905', '106.6982155637512', '16'),
(4, 'Ga Sài Gòn', 1, 1, '10.787135175230011', '106.6685503320465', '16'),
(5, 'Khu Tây Ba Lô', 1, 1, '10.768026908260591', '106.69199283883665', '16'),
(6, 'Quận 1 - Khu người Nhật', 1, 1, '', '', ''),
(7, 'Sân Bay Tân Sơn Nhất', 1, 1, '10.821080181550384', '106.6607719259033', '15'),
(8, 'Quận 2 - Thảo Điền', 1, 1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_order`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_order` (
  `HotelOrder_Id` bigint(20) NOT NULL auto_increment,
  `HotelOrder_ContactName` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `HotelOrder_ContactAddress` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `HotelOrder_ContactPhone` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `HotelOrder_ContactEmail` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Hotel_Id` int(11) NOT NULL,
  `Room_Id` int(11) NOT NULL,
  `HotelOrder_BookDate` datetime NOT NULL,
  `HotelOrder_StayDate` date NOT NULL,
  `HotelOrder_NightsStay` int(11) NOT NULL,
  `HotelOrder_Room` int(11) NOT NULL,
  `HotelOrder_Adult` int(11) NOT NULL,
  `RoomPromotion_Id` int(11) NOT NULL,
  `HotelOrder_ExtraBed` int(11) NOT NULL,
  `HotelOrder_Total` int(11) NOT NULL,
  `User_Id` bigint(20) default NULL,
  `HotelOrder_Code` int(11) NOT NULL,
  `HotelOrder_OutDate` date NOT NULL,
  `HotelOrder_Status` varchar(11) NOT NULL,
  `RoomSet_Id` varchar(255) NOT NULL,
  `PolicyCancel_Id` int(11) NOT NULL,
  `HotelOrder_Discount` float NOT NULL,
  `HotelOrder_TotalAmount` int(11) NOT NULL,
  PRIMARY KEY  (`HotelOrder_Id`),
  KEY `HotelId` (`Hotel_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_hotel_order`
--

INSERT INTO `tbl_hotel_order` (`HotelOrder_Id`, `HotelOrder_ContactName`, `HotelOrder_ContactAddress`, `HotelOrder_ContactPhone`, `HotelOrder_ContactEmail`, `Hotel_Id`, `Room_Id`, `HotelOrder_BookDate`, `HotelOrder_StayDate`, `HotelOrder_NightsStay`, `HotelOrder_Room`, `HotelOrder_Adult`, `RoomPromotion_Id`, `HotelOrder_ExtraBed`, `HotelOrder_Total`, `User_Id`, `HotelOrder_Code`, `HotelOrder_OutDate`, `HotelOrder_Status`, `RoomSet_Id`, `PolicyCancel_Id`, `HotelOrder_Discount`, `HotelOrder_TotalAmount`) VALUES
(2, 'Tuấn', '19/9', '0944118855', 'konokoshino2002@yahoo.com', 24, 14, '2014-07-25 17:12:00', '2014-07-28', 2, 2, 2, 23, 1, 19932000, 2, 6565656, '2014-07-30', '3', '5,6,7', 5, 1.2, 19692816),
(1, 'Tuấn', '19/9', '0944118855', 'konokoshino2002@yahoo.com', 24, 14, '2014-07-24 17:12:00', '2014-07-25', 1, 1, 2, 23, 1, 2904000, 2, 6565656, '2014-07-26', '-1', '2,3', 5, 1.2, 2869152);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_order_detail`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_order_detail` (
  `OrderDetail_Id` bigint(20) NOT NULL auto_increment,
  `OrderDetail_SingleNetRate` int(11) NOT NULL,
  `OrderDetail_DoubleNetRate` int(11) NOT NULL,
  `OrderDetail_ExtraBed` int(11) NOT NULL,
  `OrderDetail_Tax` int(11) NOT NULL,
  `HotelOrder_Id` bigint(20) NOT NULL,
  `OrderDetail_Date` date NOT NULL,
  PRIMARY KEY  (`OrderDetail_Id`),
  KEY `HotelOrderId` (`HotelOrder_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_hotel_order_detail`
--

INSERT INTO `tbl_hotel_order_detail` (`OrderDetail_Id`, `OrderDetail_SingleNetRate`, `OrderDetail_DoubleNetRate`, `OrderDetail_ExtraBed`, `OrderDetail_Tax`, `HotelOrder_Id`, `OrderDetail_Date`) VALUES
(4, 1650000, 2079000, 825000, 10, 2, '2014-07-28'),
(2, 1650000, 2079000, 825000, 10, 1, '2014-07-26'),
(1, 1650000, 2079000, 825000, 10, 1, '2014-07-25'),
(5, 1650000, 2079000, 2079000, 10, 2, '2014-07-29'),
(6, 1650000, 2079000, 825000, 10, 2, '2014-07-30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_picture`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_picture` (
  `HotelPicture_Id` bigint(20) NOT NULL auto_increment,
  `HotelPicture_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `HotelPicture_Status` int(11) NOT NULL,
  `HotelPicture_Position` int(11) NOT NULL,
  `HotelPicture_Token` varchar(255) NOT NULL,
  `HotelPicture_Title` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `PictureView_Id` int(11) NOT NULL,
  PRIMARY KEY  (`HotelPicture_Id`),
  KEY `HotelPictureToken` (`HotelPicture_Token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_hotel_picture`
--

INSERT INTO `tbl_hotel_picture` (`HotelPicture_Id`, `HotelPicture_Name`, `HotelPicture_Status`, `HotelPicture_Position`, `HotelPicture_Token`, `HotelPicture_Title`, `PictureView_Id`) VALUES
(2, 'Hotel_1406181199PROVINCE_con_dao_250_250_crop_1390224862.jpg', 1, 0, '1c8554e417f95659fe6c2c92eeb4bd38', '', 0),
(3, 'Hotel_1406196563PROVINCE_da_lat_250_250_crop_1390224790 - Copy.jpg', 1, 0, '5cbb99b24142ed4e23f769c1369d0f4b', '', 0),
(4, 'Room_1406196625PROVINCE_ha_noi_250_250_crop_1390223017.jpg', 1, 0, 'e68e3d7feebbc8be563a9aea1ed4b14d', '', 0),
(5, 'Hotel_1406781984Room_1406196625PROVINCE_ha_noi_250_250_crop_1390223017.jpg', 1, 0, '5cbb99b24142ed4e23f769c1369d0f4b', '', 0),
(6, 'Hotel_1406781984Hotel_1406196563PROVINCE_da_lat_250_250_crop_1390224790 - Copy.jpg', 1, 0, '5cbb99b24142ed4e23f769c1369d0f4b', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_picture_view`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_picture_view` (
  `PictureView_Id` bigint(11) NOT NULL auto_increment,
  `PictureView_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `PictureView_Status` int(11) NOT NULL,
  PRIMARY KEY  (`PictureView_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_hotel_picture_view`
--

INSERT INTO `tbl_hotel_picture_view` (`PictureView_Id`, `PictureView_Name`, `PictureView_Status`) VALUES
(1, 'Phòng khách', 1),
(2, 'Phòng ngủ', 1),
(3, 'Phòng tắm', 1),
(4, 'Nhà bếp', 1),
(5, 'Nhà hàng', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_province`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_province` (
  `Province_Id` bigint(11) NOT NULL auto_increment,
  `Province_Name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Province_Status` int(11) NOT NULL,
  `Province_Picture` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Province_Lat` varchar(255) NOT NULL,
  `Province_Lng` varchar(255) NOT NULL,
  `Province_Zoom` varchar(255) NOT NULL,
  PRIMARY KEY  (`Province_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_hotel_province`
--

INSERT INTO `tbl_hotel_province` (`Province_Id`, `Province_Name`, `Province_Status`, `Province_Picture`, `Province_Lat`, `Province_Lng`, `Province_Zoom`) VALUES
(1, 'TP Hồ Chí Minh', 1, 'PROVINCE_ho_chi_minh_250_250_crop_1390223007.jpg', '10.820658658913779', '106.63493688867186', '7'),
(2, 'Hà Nội', 1, 'PROVINCE_ha_noi_250_250_crop_1390223017.jpg', '21.02121814174868', '105.85490759179686', '7'),
(3, 'Đà Nẵng', 1, 'PROVINCE_da_nang_250_250_crop_1390224760.jpg', '16.05455500116378', '108.21696813867186', '7'),
(4, 'Nha Trang', 1, 'PROVINCE_nha_trang_250_250_crop_1390224776.jpg', '12.276435678174051', '109.18376501367186', '9'),
(5, 'Đà Lạt', 1, 'PROVINCE_da_lat_250_250_crop_1390224790.jpg', '11.95150016039808', '108.45866735742186', '7'),
(6, 'Phan Thiết', 1, 'PROVINCE_phan_thiet_250_250_crop_1390224800.jpg', '10.974390607098549', '108.12083776757811', '9'),
(7, 'Vũng Tàu', 1, 'PROVINCE_vung_tau_250_250_crop_1390224812.jpg', '10.367099149223632', '107.06340368554686', '7'),
(8, 'Phú Quốc', 1, 'PROVINCE_phu_quoc_250_250_crop_1390224846.jpg', '8.904912582536225', '105.49785192773436', '8'),
(9, 'Côn Đảo', 1, 'PROVINCE_con_dao_250_250_crop_1390224862.jpg', '8.703381864295268', '106.6479831533203', '11'),
(10, 'Huế', 1, 'PROVINCE_hue_250_250_crop_1390224872.jpg', '16.444809390057376', '107.62370641992186', '7'),
(11, 'SaPa', 1, 'PROVINCE_sapa_250_250_crop_1390224884.jpg', '22.297512635877847', '103.84166296289061', '8');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room` (
  `Room_Id` bigint(20) NOT NULL auto_increment,
  `Hotel_Id` int(11) NOT NULL,
  `Room_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Room_Facilities` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `Room_Size` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `RoomView_Id` int(255) NOT NULL,
  `Room_Code` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Room_Status` int(11) NOT NULL,
  `Room_Description` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `Room_Date_create` datetime NOT NULL,
  `Room_TokenPicture` text NOT NULL,
  `Room_Bed` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `Room_MaxOccupancy` int(11) NOT NULL,
  `Room_MaxExtrabeds` int(11) NOT NULL,
  `Room_Breakfast` int(11) NOT NULL,
  `Room_Price_SingleNetRate` int(11) NOT NULL,
  `Room_Price_DoubleNetRate` int(11) NOT NULL,
  `Room_Price_ExtraBed` int(11) NOT NULL,
  `Room_Allotment` int(11) NOT NULL,
  PRIMARY KEY  (`Room_Id`),
  KEY `HotelId_RoomStatus` (`Hotel_Id`,`Room_Status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tbl_hotel_room`
--

INSERT INTO `tbl_hotel_room` (`Room_Id`, `Hotel_Id`, `Room_Name`, `Room_Facilities`, `Room_Size`, `RoomView_Id`, `Room_Code`, `Room_Status`, `Room_Description`, `Room_Date_create`, `Room_TokenPicture`, `Room_Bed`, `Room_MaxOccupancy`, `Room_MaxExtrabeds`, `Room_Breakfast`, `Room_Price_SingleNetRate`, `Room_Price_DoubleNetRate`, `Room_Price_ExtraBed`, `Room_Allotment`) VALUES
(14, 24, 'Loại A', '', '100', 10, '1406196628', 1, '', '2014-07-24 17:10:28', 'e68e3d7feebbc8be563a9aea1ed4b14d', '', 3, 1, 1, 1650000, 2079000, 825000, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_bed`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_bed` (
  `Room_Bed_Id` bigint(11) NOT NULL auto_increment,
  `Room_Bed_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Room_Bed_Status` int(11) NOT NULL,
  PRIMARY KEY  (`Room_Bed_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_hotel_room_bed`
--

INSERT INTO `tbl_hotel_room_bed` (`Room_Bed_Id`, `Room_Bed_Name`, `Room_Bed_Status`) VALUES
(1, '1 Single Bed', 1),
(2, '1 giường đơn', 1),
(3, '2 giường đôi', 1),
(4, '1 giường đôi', 1),
(5, '2 Giường Đơn', 1),
(6, 'Giường tầng', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_facilities`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_facilities` (
  `Facilities_Id` bigint(11) NOT NULL auto_increment,
  `Facilities_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Facilities_Picture` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Facilities_Status` int(11) NOT NULL,
  PRIMARY KEY  (`Facilities_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `tbl_hotel_room_facilities`
--

INSERT INTO `tbl_hotel_room_facilities` (`Facilities_Id`, `Facilities_Name`, `Facilities_Picture`, `Facilities_Status`) VALUES
(12, 'báo hàng ngày', '', 1),
(11, 'bàn ủi quần áo', '', 1),
(10, 'ban công', '', 1),
(9, 'bàn', '', 1),
(8, 'áo choàng tắm', '', 1),
(13, 'bồn tắm', '', 1),
(14, 'bồn tắm tạo sóng', '', 1),
(15, 'cách âm', '', 1),
(16, 'đầu đĩa DVD/CD', '', 1),
(17, 'đế sạc máy nghe nhạc ipod', '', 1),
(18, 'đồ dùng nhà tắm', '', 1),
(19, 'giường dài bổ sung (hơn 2 m)', '', 1),
(20, 'góc ngồi nghỉ', '', 1),
(21, 'hẹn giờ báo thức', '', 1),
(22, 'hồ bơi riêng', '', 1),
(23, 'kênh phim nội bộ', '', 1),
(24, 'két an toàn cho laptop', '', 1),
(25, 'két sắt', '', 1),
(26, 'lò vi sóng', '', 1),
(27, 'lưới chống muỗi', '', 1),
(28, 'màn rèm cửa', '', 1),
(29, 'máy điện thoại', '', 1),
(30, 'máy giặt', '', 1),
(31, 'máy lạnh', '', 1),
(32, 'máy pha trà/cà phê', '', 1),
(33, 'máy rửa chén', '', 1),
(34, 'máy sấy quần áo', '', 1),
(35, 'máy sấy tóc', '', 1),
(36, 'nhà bếp', '', 1),
(37, 'nước đóng chai miễn phí', '', 1),
(38, 'phòng ăn riêng', '', 1),
(39, 'phòng có thể thông nhau', '', 1),
(40, 'phòng dành cho khách', '', 1),
(41, 'phòng không hút thuốc', '', 1),
(42, 'phòng tắm chung', '', 1),
(43, 'quạt', '', 1),
(44, 'sưởi ấm', '', 1),
(45, 'tắm bồn và tắm hoa sen riêng', '', 1),
(46, 'thiết bị là ủi', '', 1),
(47, 'tivi', '', 1),
(48, 'tivi LCD/Plasma', '', 1),
(49, 'trò chơi điện tử', '', 1),
(50, 'truy cập internet có dây', '', 1),
(51, 'truy cập internet có dây (miễn phí)', '', 1),
(52, 'truy cập internet có dây (tính phí)', '', 1),
(53, 'truy cập internet không dây', '', 1),
(54, 'truy cập internet không dây (miễn phí)', '', 1),
(55, 'truy cập internet không dây (tính phí)', '', 1),
(56, 'truyền hình cáp', '', 1),
(57, 'tủ đồ ăn uống nhẹ', '', 1),
(58, 'tủ lạnh', '', 1),
(59, 'vật dụng nhà bếp', '', 1),
(60, 'vòi hoa sen', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_policy_cancel`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_policy_cancel` (
  `PolicyCancel_Id` bigint(11) NOT NULL auto_increment,
  `PolicyCancel_Name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `PolicyCancel_Body` text collate utf8_unicode_ci NOT NULL,
  `PolicyCancel_Status` int(11) NOT NULL,
  `PolicyCancel_Type` int(11) NOT NULL,
  PRIMARY KEY  (`PolicyCancel_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tbl_hotel_room_policy_cancel`
--

INSERT INTO `tbl_hotel_room_policy_cancel` (`PolicyCancel_Id`, `PolicyCancel_Name`, `PolicyCancel_Body`, `PolicyCancel_Status`, `PolicyCancel_Type`) VALUES
(2, 'Cancel 3D prior arrival 1N charge, No Show 100% charge', '<p>Lệnh hủy phòng nhận được trong vòng 3 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn bạn sẽ được coi là Không Nhận Phòng và không hoàn phí </p>\r\n\r\n<p>tiền đặt phòng đã thu(Chính sách khách sạn).</p>', 1, 2),
(3, 'Cancel 3D prior arrival 1N charge, No Show 1N charge', '<p>Lệnh hủy phòng nhận được trong vòng 3 ngày trước ngày đến sẽ bị tính phí đêm đầu </p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và sẽ bị tính phí </p>\r\n\r\n<p>đêm đầu tiên(Chính sách khách sạn).</p>', 1, 1),
(4, 'Cancel 3D prior arrival 1N charge, No Show 100% charge', '<p>Lệnh hủy phòng nhận được trong vòng 3 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và không hoàn phí </p>\r\n\r\n<p>tiền đặt phòng đã thu(Chính sách khách sạn).</p>', 1, 1),
(5, 'Cancel 5D prior arrival 1N charge, No Show 1N charge', '<p>Lệnh hủy phòng nhận được trong vòng 5 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và sẽ bị tính phí </p>\r\n\r\n<p>đêm đầu tiên(Chính sách khách sạn).</p>', 1, 1),
(6, 'Cancel 5D prior arrival 1N charge, No Show 100% charge', '<p>Lệnh hủy phòng nhận được trong vòng 5 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và không hoàn phí </p>\r\n\r\n<p>tiền đặt phòng đã thu(Chính sách khách sạn).</p>', 1, 1),
(7, 'Cancel booking, No Show 100% charge.', '<p>Huỷ đặt phòng, tính phí 100%, và không hoàn phí tiền đặt phòng đã thu(Chính sách</p>\r\n\r\n<p>khách sạn)</p>', 1, 2),
(8, 'Cancel 3D prior arrival 1N charge, No Show 100% charge', '<p>Lệnh hủy phòng nhận được trong vòng 3 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn bạn sẽ được coi là Không Nhận Phòng và không hoàn phí </p>\r\n\r\n<p>tiền đặt phòng đã thu(Chính sách khách sạn).</p>', 1, 2),
(9, 'Cancel 3D prior arrival 1N charge, No Show 1N charge', '<p>Lệnh hủy phòng nhận được trong vòng 3 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và sẽ bị tính phí </p>\r\n\r\n<p>đêm đầu tiên(Chính sách khách sạn).</p>', 1, 1),
(10, 'Cancel 3D prior arrival 1N charge, No Show 100% charge', '<p>Lệnh hủy phòng nhận được trong vòng 3 ngày trước ngày đến sẽ bị tính phí đêm đầu </p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và không hoàn phí </p>\r\n\r\n<p>tiền đặt phòng đã thu(Chính sách khách sạn).</p>', 1, 2),
(11, 'Cancel 5D prior arrival 1N charge, No Show 1N charge', '<p>Lệnh hủy phòng nhận được trong vòng 5 ngày trước ngày đến sẽ bị tính phí đêm đầu </p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và sẽ bị tính phí </p>\r\n\r\n<p>đêm đầu tiên(Chính sách khách sạn).</p>', 1, 2),
(12, 'Cancel 5D prior arrival 1N charge, No Show 100% charge', '<p>Lệnh hủy phòng nhận được trong vòng 5 ngày trước ngày đến sẽ bị tính phí đêm đầu</p>\r\n\r\n<p>tiên. Không tới khách sạn của bạn sẽ được coi là Không Nhận Phòng và không hoàn phí </p>\r\n\r\n<p>tiền đặt phòng đã thu(Chính sách khách sạn).</p>', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_policy_cancel_set`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_policy_cancel_set` (
  `PolicyCancelSet_Id` bigint(20) NOT NULL auto_increment,
  `PolicyCancelSet_DateFrom` date NOT NULL,
  `PolicyCancelSet_DateTo` date NOT NULL,
  `PolicyCancel_Id` int(11) NOT NULL,
  `Hotel_Id` int(11) NOT NULL,
  `Room_Id` text collate utf8_unicode_ci NOT NULL,
  `PolicyCancelSet_Status` int(11) NOT NULL,
  `PolicyCancelSet_Active` int(11) NOT NULL,
  PRIMARY KEY  (`PolicyCancelSet_Id`),
  KEY `HotelId_PolicyCancelSetStatus` (`Hotel_Id`,`PolicyCancelSet_Status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tbl_hotel_room_policy_cancel_set`
--

INSERT INTO `tbl_hotel_room_policy_cancel_set` (`PolicyCancelSet_Id`, `PolicyCancelSet_DateFrom`, `PolicyCancelSet_DateTo`, `PolicyCancel_Id`, `Hotel_Id`, `Room_Id`, `PolicyCancelSet_Status`, `PolicyCancelSet_Active`) VALUES
(9, '2014-07-11', '2014-07-31', 9, 22, '12', 1, 1),
(10, '2014-07-23', '2014-07-30', 6, 22, '12', 1, 1),
(11, '2014-07-23', '2014-07-23', 6, 22, '12,13', 1, 1),
(12, '2014-07-17', '2014-07-18', 4, 22, '12,13', 1, 1),
(13, '0000-00-00', '0000-00-00', 9, 24, '', 1, 1),
(14, '0000-00-00', '0000-00-00', 4, 24, '14', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_promotion`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_promotion` (
  `RoomPromotion_Id` bigint(20) NOT NULL auto_increment,
  `RoomPromotion_DiscountType` int(11) NOT NULL,
  `RoomPromotion_DiscountValue` float NOT NULL,
  `RoomPromotion_MinRooms` int(11) NOT NULL,
  `RoomPromotion_RoomTypes` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `PolicyCancel_Id` int(11) NOT NULL,
  `RoomPromotion_MinNightsStay` int(11) NOT NULL,
  `RoomPromotion_BookDateFrom` date NOT NULL,
  `RoomPromotion_BookDateTo` date NOT NULL,
  `RoomPromotion_StayDateFrom` date NOT NULL,
  `RoomPromotion_StayDateTo` date NOT NULL,
  `RoomPromotion_DayBook` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `RoomPromotion_DayGetRoom` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `RoomPromotion_MaxNightsStay` int(11) NOT NULL,
  `RoomPromotion_TimePickerBookTimeFrom` time NOT NULL,
  `RoomPromotion_TimePickerBookTimeTo` time NOT NULL,
  `RoomPromotion_Status` int(11) NOT NULL,
  `Hotel_Id` int(11) NOT NULL,
  `RoomPromotion_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `RoomPromotion_Active` int(11) NOT NULL,
  PRIMARY KEY  (`RoomPromotion_Id`),
  KEY `RoomPromotionStatus_Hotel_Id` (`RoomPromotion_Status`,`Hotel_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `tbl_hotel_room_promotion`
--

INSERT INTO `tbl_hotel_room_promotion` (`RoomPromotion_Id`, `RoomPromotion_DiscountType`, `RoomPromotion_DiscountValue`, `RoomPromotion_MinRooms`, `RoomPromotion_RoomTypes`, `PolicyCancel_Id`, `RoomPromotion_MinNightsStay`, `RoomPromotion_BookDateFrom`, `RoomPromotion_BookDateTo`, `RoomPromotion_StayDateFrom`, `RoomPromotion_StayDateTo`, `RoomPromotion_DayBook`, `RoomPromotion_DayGetRoom`, `RoomPromotion_MaxNightsStay`, `RoomPromotion_TimePickerBookTimeFrom`, `RoomPromotion_TimePickerBookTimeTo`, `RoomPromotion_Status`, `Hotel_Id`, `RoomPromotion_Name`, `RoomPromotion_Active`) VALUES
(23, 1, 1.2, 1, '12', 11, 1, '2014-07-02', '2014-07-02', '2014-07-16', '2014-07-10', '1,4,6', '0,2,5', 0, '02:01:00', '01:03:00', 1, 22, '\\"test\\"', 1),
(26, 1, 1.2, 1, '12', 0, 1, '2014-07-23', '2014-07-30', '2014-07-23', '2014-07-30', '0,1,2,3,4,5,6', '0,1,2,3,4,5,6', 0, '00:00:00', '00:00:00', 1, 22, 'test2', 1),
(27, 1, 1.2, 1, '14', 0, 1, '2014-07-25', '2014-08-01', '2014-07-25', '2014-08-01', '0,1,2,3,4,5,6', '0,1,2,3,4,5,6', 0, '00:00:00', '00:00:00', 1, 24, '\\"test\\"', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_set`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_set` (
  `RoomSet_Id` bigint(20) NOT NULL auto_increment,
  `RoomSet_Date` date NOT NULL,
  `RoomSet_SingleNetRate` int(11) NOT NULL,
  `RoomSet_DoubleNetRate` int(11) NOT NULL,
  `RoomSet_ExtraBed` int(11) NOT NULL,
  `RoomSet_Allotment` int(11) NOT NULL,
  `RoomSet_Status` int(11) NOT NULL,
  `RoomSet_Breakfast` int(11) NOT NULL,
  `Hotel_Id` int(11) NOT NULL,
  `Room_Id` int(11) NOT NULL,
  `RoomSet_ExistedPolicy` int(11) default NULL,
  `RoomSet_Promotion` int(11) NOT NULL,
  `RoomSet_AllotmentUsed` int(11) NOT NULL,
  `RoomSet_Open` int(11) NOT NULL default '1',
  `RoomSet_Tax` int(11) NOT NULL,
  PRIMARY KEY  (`RoomSet_Id`),
  KEY `RoomSetDate_HotelId_RoomId` (`RoomSet_Date`,`Hotel_Id`,`Room_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_hotel_room_set`
--

INSERT INTO `tbl_hotel_room_set` (`RoomSet_Id`, `RoomSet_Date`, `RoomSet_SingleNetRate`, `RoomSet_DoubleNetRate`, `RoomSet_ExtraBed`, `RoomSet_Allotment`, `RoomSet_Status`, `RoomSet_Breakfast`, `Hotel_Id`, `Room_Id`, `RoomSet_ExistedPolicy`, `RoomSet_Promotion`, `RoomSet_AllotmentUsed`, `RoomSet_Open`, `RoomSet_Tax`) VALUES
(1, '2014-07-24', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(2, '2014-07-25', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(3, '2014-07-26', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(4, '2014-07-27', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(5, '2014-07-28', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(6, '2014-07-29', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(7, '2014-07-30', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10),
(8, '2014-07-31', 1650000, 2079000, 825000, 5, 1, 1, 24, 14, 1, 0, 0, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_room_view`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_room_view` (
  `RoomView_Id` bigint(11) NOT NULL auto_increment,
  `RoomView_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `RoomView_Status` int(11) NOT NULL,
  PRIMARY KEY  (`RoomView_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_hotel_room_view`
--

INSERT INTO `tbl_hotel_room_view` (`RoomView_Id`, `RoomView_Name`, `RoomView_Status`) VALUES
(2, 'Sông', 1),
(3, 'Thành phố', 1),
(4, 'Hồ', 1),
(5, 'Núi', 1),
(6, 'Thiên nhiên', 1),
(7, 'Đại dương', 1),
(8, 'Công viên', 1),
(9, 'Hồ bơi', 1),
(10, 'Biển', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_sights`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_sights` (
  `Sights_Id` bigint(11) NOT NULL auto_increment,
  `Sights_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Sights_Body` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `Province_Id` int(11) NOT NULL,
  `Sights_Status` int(11) NOT NULL,
  `Sights_Lat` varchar(255) NOT NULL,
  `Sights_Lng` varchar(255) NOT NULL,
  `Sights_Zoom` varchar(50) NOT NULL,
  PRIMARY KEY  (`Sights_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tbl_hotel_sights`
--

INSERT INTO `tbl_hotel_sights` (`Sights_Id`, `Sights_Name`, `Sights_Body`, `Province_Id`, `Sights_Status`, `Sights_Lat`, `Sights_Lng`, `Sights_Zoom`) VALUES
(1, 'Làng du lịch Bình Quới', '<p>Vị tr&iacute;: L&agrave;ng Du lịch B&igrave;nh Quới nằm tr&ecirc;n b&aacute;n đảo Thanh Đa b&ecirc;n bờ s&ocirc;ng S&agrave;i G&ograve;n, c&aacute;ch trung t&acirc;m th&agrave;nh phố Hồ Ch&iacute; Minh 8km. <br /> Đặc điểm: Với cảnh quan s&ocirc;ng nước độc đ&aacute;o, đ&acirc;y l&agrave; khu du lịch tổng hợp lớn nhất tại th&agrave;nh phố Hồ Ch&iacute; Minh. Tại đ&acirc;y c&oacute; 55 ph&ograve;ng ngủ trang thiết bị hiện đại, ẩn m&igrave;nh dưới những t&aacute;n c&acirc;y rợp m&aacute;t ven s&ocirc;ng. Nh&agrave; h&agrave;ng ở đ&acirc;y l&agrave; địa chỉ tin cậy cho những cuộc li&ecirc;n hoan, chi&ecirc;u đ&atilde;i v&agrave; rất nổi tiếng với những m&oacute;n nướng hay đặc sản Việt Nam. H&agrave;ng đ&ecirc;m tại khu du lịch c&oacute; chương tr&igrave;nh văn nghệ d&acirc;n tộc độc đ&aacute;o: &ldquo;lễ hội Kỳ Y&ecirc;n&rdquo;, &ldquo;ca nhạc t&agrave;i tử Nam bộ tr&ecirc;n Ghe Hầu&rdquo;, &ldquo;đ&aacute;m cưới truyền thống Việt Nam&rdquo;, ca nhạc d&acirc;n tộc... C&aacute;c chương tr&igrave;nh văn nghệ n&agrave;y đ&atilde; cuốn h&uacute;t đ&ocirc;ng đảo du kh&aacute;ch trong v&agrave; ngo&agrave;i nước tới xem. Tại đ&acirc;y, du kh&aacute;ch cũng c&oacute; thể tham dự lướt v&aacute;n, c&acirc;u c&aacute;, quần vợt, bơi lội, v&agrave; nhiều m&ocirc;n thể thao kh&aacute;c. Từ B&igrave;nh Quới, du kh&aacute;ch c&oacute; thể du thuyền theo tuyến s&ocirc;ng S&agrave;i G&ograve;n đến thăm địa đạo Bến Dược, vườn tr&aacute;i L&aacute;i Thi&ecirc;u hoặc về bến cảng Nh&agrave; Rồng..</p>', 1, 1, '10.818234892251299', '106.72244127557371', '16'),
(2, 'Khu du lịch Văn Thánh', '<p>Nếu bạn l&agrave; một kh&aacute;ch bộ h&agrave;nh đang mệt mỏi v&agrave; ngh&egrave;o kh&oacute;, Văn Th&aacute;nh kh&ocirc;ng thu của bạn một xu tiền v&eacute; v&agrave;o cửa n&agrave;o. Bạn c&oacute; thể ng&atilde; lưng b&ecirc;n ao sen trong vắt thoang thoảng hương thơm với những chiếc lu vại ng&agrave;y xưa, b&ecirc;n cạnh lối đi sỏi đ&aacute; gợi nhớ về miền qu&ecirc; xa tắp c&oacute; mẹ gi&agrave; đang ng&oacute;ng đợi. Nhắm mắt m&agrave; nghe gi&oacute; thổi r&igrave; r&agrave;o x&ocirc;n xao qua bụi chuối, gợi nhớ thiết tha c&acirc;u ru của mẹ ng&agrave;y xưa : <br /> &ldquo;Gi&oacute; đưa bụi chuối sau h&egrave;.<br /> Anh m&ecirc; vợ b&eacute; bỏ b&egrave; con thơ&rdquo; <br /> Tưởng như được bay bổng khắp bốn phương trời về ngay với mẹ, b&ecirc;n m&aacute;i tranh ngh&egrave;o đơn sơ c&oacute; h&agrave;ng dừa bốn m&ugrave;a cho quả ngọt ngon thơm m&aacute;t. Một chiếc xe thổ mộ cũ kỷ, một chiếc xe k&eacute;o c&ograve;n in dấu r&ecirc;u phong của thời gian, một c&acirc;y cầu tre nho nhỏ bắc qua con rạch &hellip; tất cả đều mang đến cho bạn cảm gi&aacute;c &ecirc;m đềm, y&ecirc;n l&agrave;nh m&agrave; kh&ocirc;ng bạc v&agrave;ng n&agrave;o thay thế được.<br /> <br /> Nếu bạn đang nhớ về một mối t&igrave;nh đầu thơ dại, nhớ đến c&ocirc; h&agrave;ng x&oacute;m ng&acirc;y thơ m&aacute; đỏ h&acirc;y h&acirc;y suốt ng&agrave;y c&ugrave;ng bạn chơi những tr&ograve; chơi d&acirc;n gian, th&igrave; bạn h&atilde;y leo l&ecirc;n chiếc x&iacute;ch đu v&agrave; nhấc m&igrave;nh theo từng nhịp đưa để được nhấc bổng l&ecirc;n cao v&uacute;t ch&iacute;n tầng m&acirc;y rồi từ quay lại. L&ograve;ng n&ocirc;n nao kh&ocirc;ng xiết khi nhớ về những tr&ograve; chơi tuổi xu&acirc;n th&igrave; qua c&acirc;u thơ nghịch ngợm của Hồ Xu&acirc;n Hương : &ldquo;Bốn mảnh quần hồng bay phấp phới<br /> Hai h&agrave;ng ch&acirc;n ngọc duỗi song song<br /> Chơi xu&acirc;n c&oacute; biết xu&acirc;n chăng t&aacute;<br /> Cọc nhổ đi rồi, lỗ bỏ kh&ocirc;ng? <br /> Ph&oacute;ng tầm mắt về khoảng ch&acirc;n trời xa t&iacute;t tắp, bạn h&atilde;y thả hồn theo hương thơm của vị cỏ dại quanh m&igrave;nh. Một kh&ocirc;ng gian rộng bao la bạt ng&agrave;n cỏ xanh được chăm ch&uacute;t tỉ mỉ với những h&agrave;ng c&acirc;y cảnh, hoa sứ &hellip; điểm xuyết tạo n&ecirc;n một khung cảnh n&ecirc;n thơ như vườn Ngự Uyền của nh&agrave; vua. Một vườn ngự uyển thật sang trọng m&agrave; d&acirc;n d&atilde;, đơn sơ m&agrave; lộng lẫy nằm ngay giữa l&ograve;ng th&agrave;nh phố sẵn s&agrave;ng đ&oacute;n ch&agrave;o những du kh&aacute;ch b&igrave;nh d&acirc;n nhất.<br /> <br /> Trong khung cảnh n&ecirc;n thơ đ&oacute;, bạn kh&ocirc;ng thể n&agrave;o muốn dời ch&acirc;n. Vậy th&igrave; ngại ngần g&igrave; m&agrave; kh&ocirc;ng mang theo &iacute;t qu&agrave; b&aacute;nh rồi ngồi nh&acirc;m nhi tr&ecirc;n b&atilde;i cỏ mượt m&agrave;. C&ograve;n nếu th&iacute;ch, bạn c&oacute; thể v&agrave;o nh&agrave; h&agrave;ng để d&ugrave;ng bữa. Gi&aacute; thật b&igrave;nh d&acirc;n m&agrave; m&oacute;n ăn th&igrave; tuyệt vời. C&oacute; xưa, c&oacute; nay. C&oacute; m&oacute;n xếp theo kiểu h&agrave;ng g&aacute;nh. C&oacute; m&oacute;n b&agrave;y theo phong c&aacute;ch nh&agrave; h&agrave;ng. Ngồi nh&acirc;m nhi từng m&oacute;n ăn giữa khung cảnh thanh b&igrave;nh y&ecirc;n ả, bạn c&oacute; thể qu&ecirc;n cả kh&ocirc;ng gian thời gian, giũ bỏ mọi nhọc nhằn gian lao, tiếp th&ecirc;m năng lực cho cuộc sống. <br /> <br /> C&aacute;ch trung t&acirc;m th&agrave;nh phố khoảng 2km, Khu du lịch Văn Th&aacute;nh c&oacute; tổng diện t&iacute;ch diện t&iacute;ch 77.000 m2, phần hồ chiếm khoảng 2 ha, khu du lịch m&aacute;t mẻ, rộng r&atilde;i ph&ugrave; hợp với nhiều hoạt động giải tr&iacute; thư gi&atilde;n. <br /> Ở đ&acirc;y c&oacute; kh&ocirc;ng gian rộng r&atilde;i , c&oacute; nhiều cảnh, n&ecirc;n c&oacute; nhiều đ&ocirc;i uy&ecirc;n ương chọn đ&acirc;y l&agrave;m địa điểm chụp h&igrave;nh hoặc đặt tiệc cưới.<br /> Ng&ograve;ai ra ở đ&acirc;y c&ograve;n c&oacute; Chương tr&igrave;nh Buffet Văn Th&aacute;nh với tr&ecirc;n 50 m&oacute;n &Acirc;u-&Aacute;, phục vụ từ 17 giờ thứ bảy v&agrave; chủ nhật h&agrave;ng tuần. <br /> Gi&aacute;: 109.000/phần/người lớn; 69.000/phần/trẻ em.<br /> Buổi s&aacute;ng , Văn Th&aacute;nh c&ograve;n phục vụ ăn s&aacute;ng. KDL mở cửa l&uacute;c 6h s&aacute;ng mỗi ng&agrave;y.Bạn c&oacute; thể gọi theo phần <br /> 5.000đ/ phần (bao gồm tr&agrave; đ&aacute;/ tr&agrave; n&oacute;ng, khăn)<br /> 30.000đ / phần ( bao gồm 01 phần thức uống)<br /> Hoặc cũng c&oacute; thể gọi m&oacute;n như Cơm tấm, B&ograve; kho b&aacute;nh m&igrave;, B&uacute;n b&ograve; Huế, B&aacute;nh m&igrave; Ốp-la thịt nguội&hellip;<br /> C&aacute;c m&oacute;n ăn được ưa chuộng tại Văn Th&aacute;nh: Gỏi củ hủ dừa t&ocirc;m thịt (coconut shoot salad), Gi&ograve; heo chi&ecirc;n gi&ograve;n, G&agrave; quay lu &ndash; X&ocirc;i chi&ecirc;n phồng, B&ograve; nướng ti&ecirc;u xanh, Ba chỉ quay lu- b&aacute;nh hỏi&hellip;<br /> M&igrave;nh th&igrave; th&iacute;ch đến qu&aacute;n n&agrave;y ăn điểm t&acirc;m buổi s&aacute;ng v&agrave; uống caf&ecirc; v&agrave;o buổi tối. Buổi tối ở đ&acirc;y rất đẹp. Ngồi trong những nh&agrave; s&agrave;n đưa ra bờ hồ, vừa thưởng thức cafe , vừa ngắm hồ nước, ngắm mấy c&aacute;i đ&egrave;n lồng đỏ. <br /> Gi&aacute; nước ở đ&acirc;y th&igrave; từ 12000 trở l&ecirc;n, c&ograve;n đồ ăn th&igrave; từ 15 000 trở l&ecirc;n.<br /> C&aacute;c dịch vụ kh&aacute;c ở đ&acirc;y<br /> Hồ bơi:rộng 500 m2, đẹp, an to&agrave;n, c&oacute; khu d&agrave;nh cho trẻ em.<br /> Gi&aacute; v&eacute;: 10.000đ/trẻ em, 20.000đ/người lớn. <br /> Thứ bảy, chủ nhật, Lễ: 15.000đ/trẻ em, 30.000đ/ người lớn. <br /> Tennis: 4 s&acirc;n đạt ti&ecirc;u chuẩn gồm 02 s&acirc;n c&oacute; m&aacute;i che, 02 s&acirc;n kh&ocirc;ng m&aacute;i che, thuận tiện cho việc tổ chức c&aacute;c giải thi đấu. <br /> Gi&aacute;: 20.000đ - 40.000đ -60.000đ - 80.000đ -100.000đ/giờ .</p>', 1, 1, '10.798506810903826', '106.71684082315062', '16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_type`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_type` (
  `HotelType_Id` bigint(11) NOT NULL auto_increment,
  `HotelType_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `HotelType_Status` int(11) NOT NULL,
  PRIMARY KEY  (`HotelType_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_hotel_type`
--

INSERT INTO `tbl_hotel_type` (`HotelType_Id`, `HotelType_Name`, `HotelType_Status`) VALUES
(3, 'Căn Hộ Cho Thuê', 1),
(4, 'Căn hộ', 1),
(5, 'Biệt Thự Mini', 1),
(6, 'Nhà khách', 1),
(7, 'Khách sạn', 1),
(8, 'Nhà Nghỉ', 1),
(9, 'Tư Gia', 1),
(10, 'Biệt thự', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hotel_useful`
--

CREATE TABLE IF NOT EXISTS `tbl_hotel_useful` (
  `Useful_Id` bigint(11) NOT NULL auto_increment,
  `Useful_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Useful_Status` int(11) NOT NULL,
  PRIMARY KEY  (`Useful_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=27 ;

--
-- Dumping data for table `tbl_hotel_useful`
--

INSERT INTO `tbl_hotel_useful` (`Useful_Id`, `Useful_Name`, `Useful_Status`) VALUES
(11, 'Thời gian đi tới sân bây (phút)', 1),
(10, 'Khoảng cách tới sân bay', 1),
(9, 'Khoảng cách từ trung tâm thành phố', 1),
(8, 'Phí đưa đón sân bay', 1),
(12, 'Điện áp trong phòng', 1),
(13, 'Trả phòng', 1),
(14, 'Thời gian nhận phòng sớm nhất', 1),
(15, 'Phí sử dụng Internet', 1),
(16, 'Phòng / tầng không hút thuốc', 1),
(17, 'Phí ăn sáng', 1),
(18, 'Số lượng quán bar', 1),
(19, 'Số tầng', 1),
(20, 'Số lượng nhà hàng', 1),
(21, 'Số phòng', 1),
(22, 'Phí trông xe (theo ngày)', 1),
(23, 'Quầy tiếp tân mở cửa đến', 1),
(24, 'Khách sạn xây vào', 1),
(25, 'Năm khách sạn được sửa chữa lại lần cuối', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image_uploads`
--

CREATE TABLE IF NOT EXISTS `tbl_image_uploads` (
  `upload_id` bigint(20) NOT NULL auto_increment,
  `image_name` text,
  `token` varchar(255) default NULL,
  `created` int(11) default NULL,
  PRIMARY KEY  (`upload_id`),
  KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_image_uploads`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbl_seo_keyword`
--

CREATE TABLE IF NOT EXISTS `tbl_seo_keyword` (
  `Id` bigint(20) NOT NULL auto_increment,
  `Keyword` text character set utf8 collate utf8_unicode_ci,
  `Description` text character set utf8 collate utf8_unicode_ci,
  `Link` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Status` int(11) NOT NULL,
  `Title` text character set utf8 collate utf8_unicode_ci,
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_seo_keyword`
--

INSERT INTO `tbl_seo_keyword` (`Id`, `Keyword`, `Description`, `Link`, `Status`, `Title`) VALUES
(2, '', '', '\\"http://localhost/myweb/san-pham/camisept.html?id=7\\"', 1, '\\"test\\"');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `User_Id` bigint(20) NOT NULL auto_increment,
  `User_Type` int(11) NOT NULL,
  `User_Name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `User_Phone` int(11) NOT NULL,
  `User_Address` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `User_Company` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `User_Fax` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `User_Email` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `User_Password` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `User_Status` int(11) NOT NULL,
  `User_DateCreate` datetime NOT NULL,
  `User_Birthday` date NOT NULL,
  PRIMARY KEY  (`User_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`User_Id`, `User_Type`, `User_Name`, `User_Phone`, `User_Address`, `User_Company`, `User_Fax`, `User_Email`, `User_Password`, `User_Status`, `User_DateCreate`, `User_Birthday`) VALUES
(20, 2, 'Test', 0, '', '', '', 'anhtuan1507198722@yahoo.com.vn', '550e1bafe077ff0b0b67f4e32f29d751', 1, '2014-07-24 17:16:03', '0000-00-00'),
(19, 1, 'Anh TUấn', 2147483647, '', '', '', 'konokoshino2002@yahoo.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, '2014-07-24 17:00:26', '0000-00-00'),
(21, 1, 'Test', 123123, '', '', '', 'test@yahoo.com', '', 2, '2014-07-25 14:47:23', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_web_info`
--

CREATE TABLE IF NOT EXISTS `tbl_web_info` (
  `Id` int(11) NOT NULL auto_increment,
  `Title` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `Keywords` text character set utf8 collate utf8_unicode_ci,
  `Description` text character set utf8 collate utf8_unicode_ci,
  `Picture` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `Email` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_web_info`
--

INSERT INTO `tbl_web_info` (`Id`, `Title`, `Keywords`, `Description`, `Picture`, `Email`) VALUES
(1, '\\"test\\"', '\\"test\\"', '\\"test\\"', 'WEB_INFO_ICON_logo_amipharma_1386766811.png', '\\"test\\"');
