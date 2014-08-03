<?php

class Iadm_ContentController extends MyZend_Controller_AdminAction {

	protected $Model;
	protected $SessionPage;

	public function init() {
		parent::init();
		$this->Model = new Iadm_Model_Content();
		$this->SessionPage = new Zend_Session_Namespace('SessionPage');
	}

	public function indexAction() {
		if ($this->getRequest()->getParam('ErrorMsg')) {
			$this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
		}

		if ($this->getRequest()->getParam('SuccessMsg')) {
			$this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
		}

		$ContentAll = $this->Model->getListContent(array('task' => 'getnumAll'));
		$countContentAll = $ContentAll->count;

		$currentPageNumber = $this->_request->getParam('page', 1);
		$this->SessionPage->currentPageNumber = $currentPageNumber;
		$page = MyZend_Function::getPaginator(array(
					'CurrentPageNumber' => $currentPageNumber,
					'Total' => $countContentAll));

		$listContent = $this->Model->getListContent(array(
			'task' => 'getPage',
			'pageCurrent' => $currentPageNumber,
			'offset' => _ITEM_COUNT_PER_PAGE_BACK));

		$fromItem = ($countContentAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
		$toItem = ($countContentAll > 0) ? ($fromItem + count($listContent) - 1) : 0;

		$this->view->assign('fromItem', $fromItem);
		$this->view->assign('toItem', $toItem);
		$this->view->assign('countContentAll', $countContentAll);
		$this->view->assign('listContent', MyZend_Function::filterOutput($listContent, 'array'));
		$this->view->assign('page', $page);
		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function addAction() {

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();
			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Title'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyTitle'];
			}

			if (count($this->ErrorMsg) == 0) {
				$input = array();
				$input['Title'] = $Params['Title'];
				$input['Status'] = $Params['Status'];
				$input['Body'] = $Params['Body'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->insertContent($input);
				$this->SuccessMsg[] = $this->Msg['AddSuccess'];
			}
		}

		$this->view->assign('SuccessMsg', $this->SuccessMsg);
		$this->view->assign('ErrorMsg', $this->ErrorMsg);
	}

	public function editAction() {

		$id = $this->getRequest()->getParam('id');
		$contentInfo = $this->Model->getListContent(array('task' => 'getEdit', 'Id' => $id));

		if ($this->getRequest()->isPost()) {
			$Params = $this->getRequest()->getParams();

			$Validator = new Zend_Validate_NotEmpty();
			if (!$Validator->isValid($Params['Title'])) {
				$this->ErrorMsg[] = $this->Msg['NoEmptyTitle'];
			}

			if (count($this->ErrorMsg) == 0) {
				$input = array();
				$input['Title'] = $Params['Title'];
				$input['Status'] = $Params['Status'];
				$input['Body'] = $Params['Body'];
				$input = MyZend_Function::filterInput($input, 'array');
				$this->Model->updateContent($input, $id);
				$this->forward('index', 'content', 'iadm', array(
					'SuccessMsg' => $this->Msg['SuccessUpdate'],
					'page' => $this->SessionPage->currentPageNumber));
			}
		}

		$this->view->assign('Content', MyZend_Function::filterOutput($contentInfo, 'array'));
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
			$this->Model->deleteContent($v);
		}
		$this->forward('index', 'content', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
	}

}

?>
