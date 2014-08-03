<?php

class Iadm_SeoKeywordController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;

	public function init() {

		parent::init();
		$this->Model = new Iadm_Model_SeoKeyword();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
	}

	public function indexAction() {

		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$SeoKeywordAll = $this->Model->getListSeoKeyword(array('task' => 'getnumAll'));
		$countSeoKeywordAll = $SeoKeywordAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countSeoKeywordAll));

		$listSeoKeyword = $this->Model->getListSeoKeyword(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countSeoKeywordAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countSeoKeywordAll > 0) ? ($fromItem + count($listSeoKeyword) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('CountSeoKeywordAll', $countSeoKeywordAll);
		$this->view->assign('listSeoKeyword', MyZend_Function::filterOutput($listSeoKeyword, 'array'));
		$this->view->assign('page', $page);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function addAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Link'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyLink'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['Link'] = $Params['Link'];
				$input['Keyword'] = $Params['Keyword'];
				$input['Description'] = $Params['Description'];
				$input['Status'] = $Params['Status'];
				$input['Title'] = $Params['Title'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertSeoKeyword($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$SeoKeywordInfo = $this->Model->getListSeoKeyword(array('task' => 'getEdit', 'Id' => $id));
		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Link'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyLink'];
			}

			if (count($this->ErrorMsg) == 0) {

				$input = array();
				$input['Link'] = $Params['Link'];
				$input['Keyword'] = $Params['Keyword'];
				$input['Description'] = $Params['Description'];
				$input['Status'] = $Params['Status'];
				$input['Title'] = $Params['Title'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateSeoKeyword($input, $id);
				$this->forward('index', 'seo-keyword', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}
		$this->view->assign('SeoKeyword', MyZend_Function::filterOutput($SeoKeywordInfo, 'array'));
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function deleteAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParam('checkboxDel');
		} else {
			$Id = $this->getRequest()->getParam('id');
			$Params[0] = $Id;
		}

		foreach ($Params as $v) {
			$this->Model->deleteSeoKeyword($v);
		}
		$this->forward('index', 'seo-keyword', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

}

?>
