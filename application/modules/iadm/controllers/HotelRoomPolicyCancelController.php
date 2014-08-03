<?php

class Iadm_HotelRoomPolicyCancelController extends MyZend_Controller_AdminAction {

    protected $Model;
    protected $SessionPage;

    public function init() {

        parent::init();
        $this->Model = new Iadm_Model_HotelRoomPolicyCancel();
        $this->SessionPage = new Zend_Session_Namespace('SessionPage');
    }

    public function indexAction() {

        if ($this->getRequest()->getParam('ErrorMsg')) {
            $this->ErrorMsg[] = $this->getRequest()->getParam('ErrorMsg');
        }

        if ($this->getRequest()->getParam('SuccessMsg')) {
            $this->SuccessMsg[] = $this->getRequest()->getParam('SuccessMsg');
        }

        $PolicyCancelAll = $this->Model->getListPolicyCancel(array('task' => 'getnumAll'));
        $countPolicyCancelAll = $PolicyCancelAll->count;

        $currentPageNumber = $this->_request->getParam('page', 1);
        $this->SessionPage->currentPageNumber = $currentPageNumber;
        $page = MyZend_Function::getPaginator(array(
                    'CurrentPageNumber' => $currentPageNumber,
                    'Total' => $countPolicyCancelAll));

        $listPolicyCancel = $this->Model->getListPolicyCancel(array(
            'task' => 'getPage',
            'pageCurrent' => $currentPageNumber,
            'offset' => _ITEM_COUNT_PER_PAGE_BACK));

        $fromItem = ($countPolicyCancelAll > 0) ? ((_ITEM_COUNT_PER_PAGE_BACK * $currentPageNumber - _ITEM_COUNT_PER_PAGE_BACK) + 1) : 0;
        $toItem = ($countPolicyCancelAll > 0) ? ($fromItem + count($listPolicyCancel) - 1) : 0;

        $this->view->assign('fromItem', $fromItem);
        $this->view->assign('toItem', $toItem);
        $this->view->assign('countPolicyCancelAll', $countPolicyCancelAll);
        $this->view->assign('listPolicyCancel', MyZend_Function::filterOutput($listPolicyCancel, 'array'));
        $this->view->assign('page', $page);
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function addAction() {

        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParams();
            $Validator = new Zend_Validate_NotEmpty();
            if (!$Validator->isValid($Params['Name'])) {
                $this->ErrorMsg[] = $this->Msg['NoEmptyName'];
            }
            if (count($this->ErrorMsg) == 0) {
                $input = array();
                $input['PolicyCancel_Name'] = $Params['Name'];
                $input['PolicyCancel_Status'] = $Params['Status'];
                $input['PolicyCancel_Body'] = $Params['Description'];
                $input['PolicyCancel_Type'] = $Params['Type'];
				$input = MyZend_Function::filterInput($input, 'array');
                $this->Model->insertPolicyCancel($input);
                $this->SuccessMsg[] = $this->Msg['AddSuccess'];
            }
        }
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $PolicyCancelInfo = $this->Model->getListPolicyCancel(array('task' => 'getEdit', 'Id' => $id));

        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParams();

            $Validator = new Zend_Validate_NotEmpty();
            if (!$Validator->isValid($Params['Name'])) {
                $this->ErrorMsg[] = $this->Msg['NoEmptyName'];
            }
            if (count($this->ErrorMsg) == 0) {
                $input = array();
                $input['PolicyCancel_Name'] = $Params['Name'];
                $input['PolicyCancel_Status'] = $Params['Status'];
                $input['PolicyCancel_Body'] = $Params['Description'];
                $input['PolicyCancel_Type'] = $Params['Type'];
				$input = MyZend_Function::filterInput($input, 'array');
                $this->Model->updatePolicyCancel($input, $id);
                $this->forward('index', 'hotel-room-policy-cancel', 'iadm', array(
                    'SuccessMsg' => $this->Msg['SuccessUpdate'],
                    'page' => $this->SessionPage->currentPageNumber));
            }
        }

        $this->view->assign('PolicyCancel', MyZend_Function::filterOutput($PolicyCancelInfo, 'array'));
        $this->view->assign('SuccessMsg', $this->SuccessMsg);
        $this->view->assign('ErrorMsg', $this->ErrorMsg);
    }

    public function deleteAction() {

        //Khong cho xoa
        $this->forward('index', 'hotel-room-policy-cancel', 'iadm', array(
            'page' => $this->SessionPage->currentPageNumber,
            'ErrorMsg' => 'Dữ liệu không thể xóa.'));
        return;


        if ($this->getRequest()->isPost()) {
            $Params = $this->getRequest()->getParam('checkboxDel');
        } else {
            $Id = $this->getRequest()->getParam('id');
            $Params[0] = $Id;
        }

        foreach ($Params as $v) {
            $this->Model->deletePolicyCancel($v);
        }
        $this->forward('index', 'hotel-room-policy-cancel', 'iadm', array('page' => $this->SessionPage->currentPageNumber));
    }

}

?>
