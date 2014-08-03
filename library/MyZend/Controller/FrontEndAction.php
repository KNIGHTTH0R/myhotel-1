<?php

class MyZend_Controller_FrontEndAction extends Zend_Controller_Action {

	protected $breadcrumb;
	protected $listInterface;
	
	public function init() {
		parent::init();
		MyZend_Function::setLayout(_FRONTEND_PATH, 'index');
		$this->getHeader();
		$this->getDataGeneral();
	}

	public function getHeader() {
		//Get keyword, description, icon
		$Web = new Frontend_Model_WebInfo();
		$infoWeb = $Web->getWebInfo(array('task' => 'getEdit', 'id' => 1));

		$this->view->headTitle($infoWeb['Title']);
		$this->view->headMeta()->appendName('keywords', $infoWeb['Keywords']);
		$this->view->headMeta()->appendName('description', $infoWeb['Description']);
		$this->view->headLink()->headLink(array('href' => _FRONTEND_URL . '/pictures/' . $infoWeb['Picture']
			, 'rel' => 'shortcut icon'
			, 'type' => 'image/x-icon'));
		$this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
		$this->view->headMeta()->appendName('robots', 'noodp,index,follow');
		$this->view->headMeta()->appendName('revisit-after', '1 days');
		$this->view->headMeta()->appendHttpEquiv('content-language', 'vi');

		//Seo
		$Seo = new Frontend_Model_Seo();
		$SeoInfo = $Seo->getListSeo(array('task' => 'getEdit'));
		if (isset($SeoInfo['Keyword']) && $SeoInfo['Keyword'] != '') {
			$this->view->headMeta()->setName('keywords', $SeoInfo['Keyword']);
		}

		if (isset($SeoInfo['Description']) && $SeoInfo['Description'] != '') {
			$this->view->headMeta()->setName('description', $SeoInfo['Description']);
		}
		if (isset($SeoInfo['Title']) && $SeoInfo['Title'] != '') {
			$this->view->headTitle($SeoInfo['Title'], true);
		}
	}

	public function getDataGeneral() {
		//Menu
		$Category = new Frontend_Model_Category();
		$listMenu = $Category->getListCategory(array('task' => 'getMenuMapping'));
		$this->view->assign('listMenu', $listMenu);

		//Danh mục dung de mapping
		$listCategoryAllMapping = $Category->getListCategory(array('task' => 'getAllMapping'));
		$this->view->assign('listCategoryAllMapping', $listCategoryAllMapping);

		//Support online
		$supportOnline = new Frontend_Model_SupportOnline();
		$this->view->assign('listSupportOnline', $supportOnline->getListSupportOnline(array('task' => 'getAll')));

		//Danh sach san pham
		$product = new Frontend_Model_Product();
		$listProduct = $product->getListProduct(array('task' => 'getAll'));
		$this->view->assign('listProductMenu', $listProduct);

		//Banner slide
		$bannerSlide = new Frontend_Model_BannerSlide();
		$this->view->assign('listBannerSlide', $bannerSlide->getListBannerSlide(array('task' => 'getAll')));

		//Tin khoa hoc trang sub | Limit 5
		$news = new Frontend_Model_News();
		$listNewsKHLeft = $news->getListNewsOfCategory(array('task' => 'getPage', 'category' => 5, 'pageCurrent' => 1, 'offset' => 5));
		$this->view->assign('listNewsKHLeft', $listNewsKHLeft);

		//Giao dien
		$interface = new Frontend_Model_Interface();
		$this->listInterface = $interface->getListInterface(array('task' => 'getAllMapping'));
		$this->view->assign('listInterface', $this->listInterface);
	}

}

?>