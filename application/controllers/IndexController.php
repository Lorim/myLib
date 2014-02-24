<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->getView()->headTitle("myLib", Zend_View_Helper_Placeholder_Container_Abstract::SET);
    }

    public function indexAction() {
        $oMpd = new Application_Model_Mpd();
        $this->view->oMpd = $oMpd;
        $oPl = new Application_Model_Playlist($oMpd);
    }
    
    public function getdirAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $oMpd = new Application_Model_Mpd();
        $sPath = $this->_request->getParam('path');
        $this->view->dir = $oMpd->getDir($sPath);
        $remove = strrchr($sPath, '/');
        if($remove) {
            $sUrl = str_replace("$remove", "", $sPath);
        } else {
            $sUrl = '/';
        }
        $this->view->prevdir = $sUrl;
        try {
            echo $this->view->render('widgets/dirlist.phtml');
        } catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
        }
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
    public function viewAction() {
        $oMp3mapper = new Application_Model_Mp3Mapper();
        $aList = $oMp3mapper->fetchAll();
        $this->view->mp3 = $aList;
    }
    
    public function playlistAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $oMpd = new Application_Model_Mpd();
        $oPl = new Application_Model_Playlist($oMpd);
        switch($this->_request->getParam('a')) {
            case 'add':
                $sSong = $this->_request->getParam('song');
                $oMpd->addSong($sSong);
                break;
            case 'play':
                $iPos = $this->_request->getParam('id');
                $oMpd->playSong($iPos);
                break;
            case 'playtime':
                $aActivesong = $oPl->getActiveSong();
                $iElapsed = $aActivesong['elapsed'];
                $iToplay = $aActivesong['Time'];

                $aReturn = array(
                    'status' => $iElapsed ? true : false,
                    'elapsed' => $iElapsed,
                    'full' => $iToplay,
                    'songid' => $aActivesong['Id']
                );
                echo json_encode($aReturn);
                break;
        }
    }
}
