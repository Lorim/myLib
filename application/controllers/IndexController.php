<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->getView()->headTitle("myLib", Zend_View_Helper_Placeholder_Container_Abstract::SET);
    }

    public function indexAction() {
        $oHue = Zend_Registry::get('hue');
        
        $this->view->groups = $oHue->getGroups();
    }
    
    public function lightAction() {
        $oHue = Zend_Registry::get('hue');
        $this->view->lights = $oHue->getLights();
    }
    public function groupAction() {
        $oHue = Zend_Registry::get('hue');
        $this->view->groups = $oHue->getGroups();
    }
    
    public function scheduleAction() {
        $oHue = Zend_Registry::get('hue');
        $this->view->schedules = $oHue->getSchedules();
    }
    
    public function sceneAction() {
        
    }
    
    public function loginAction() {
        $this->_helper->_layout->setLayout('login');
        $form = new Application_Form_Login();
        $this->view->form = $form;
        $request = $this->getRequest();
        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($request->getPost())) {
            return;
        }
        $auth = Zend_Auth::getInstance();
        $dbAdapter = Zend_Registry::get('db');

        $authAdapter = new Zend_Auth_Adapter_DbTable(
                $dbAdapter, 'user', 'username', 'pwd'
        );
        $authAdapter->setIdentity($form->getValue('name'));
        $authAdapter->setCredential($form->getValue('password'));
        $result = $auth->authenticate($authAdapter);
        
        if ($result->isValid()) {
            $user = $authAdapter->getResultRowObject();
            $auth->getStorage()->write($user);
            $this->_helper->redirector('index', 'index', 'default');
            return true;
        }
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_helper->redirector->gotoUrl($this->getRequest()->getServer('HTTP_REFERER'));
    }
}
