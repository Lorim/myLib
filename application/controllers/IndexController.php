<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->getView()->headTitle("myLib", Zend_View_Helper_Placeholder_Container_Abstract::SET);
    }

    public function indexAction() {
        
    }

    public function loginAction() {
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
    public function scanAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $bForce = $this->_request->getParam('force');
        $oScan = new Application_Model_Scandir();
        try{
            $files = $oScan->scan();
            $mp3mapper = new Application_Model_Mp3Mapper();
            foreach($files as $file) {
                $id3 = new Application_Model_Id3($file);
                $mp3File = $mp3mapper->searchfile($id3->getPath(), $id3->getFilename());
                $mp3File->setArtist($id3->getArtist())
                        ->setTitle($id3->getTitle())
                        ->setAlbum($id3->getAlbum())
                        ->setGenre($id3->getGenre())
                        ->setYear($id3->getYear())
                        ->setHash(md5($file))
                        ->setPath($id3->getPath())
                        ->setFilename($id3->getFilename());
                
                $mp3mapper->save($mp3File);
                //Zend_Debug::dump($xml);
                //return;
            }
            $this->_helper->redirector('view', 'index', 'default');
        } catch (Exception $ex) {
            Zend_Debug::dump($ex);
        }
        $oMp3mapper = new Application_Model_Mp3Mapper();
        $aList = $oMp3mapper->fetchAll();
        foreach($aList as $mp3) {
            $lastfm = new Application_Model_Lastfm();
            Zend_Debug::dump($lastfm->getAlbuminfo($mp3->getArtist(), $mp3->getAlbum()));
            $lastfm->getTrackinfo($mp3->getArtist(), $mp3->getTitle());
            die();
            //Zend_Debug::dump($mp3);
        }
    }
    public function playAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $iMp3 = $this->_request->getParam('id');
        $oMp3mapper = new Application_Model_Mp3Mapper();
        $oMp3 = $oMp3mapper->fetchOne($iMp3);
        //Zend_Debug::dump($oMp3);
        readfile($oMp3->getPath(). "/" . $oMp3->getFilename());
    }
    public function albenAction() {
        $oMp3mapper = new Application_Model_Mp3Mapper();
        $aList = $oMp3mapper->fetchAlben();
        $this->view->alben = $aList;
        $this->view->lastfm = new Application_Model_Lastfm();
    }
    public function albumAction() {
        $oMp3mapper = new Application_Model_Mp3Mapper();
        $aSongs = $oMp3mapper->fetchAlbum(
                $this->_request->getParam('alb')
                );
        $this->view->album = $this->_request->getParam('alb');
        $this->view->songs = $aSongs;
        $this->view->lastfm = new Application_Model_Lastfm();
    }
}
