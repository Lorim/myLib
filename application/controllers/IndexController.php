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

    public function playtimeAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $oMpd = new Application_Model_Mpd();
        $oPl = new Application_Model_Playlist($oMpd);
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
        
    }
    
    public function playAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $oMpd = new Application_Model_Mpd();
        $iPos = $this->_request->getParam('id');
        $oMpd->playSong($iPos);
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
    public function scanAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $bForce = $this->_request->getParam('force');
        $oScan = new Application_Model_Scandir();
        try{
            $files = $oScan->scan();
            $lastfm = new Application_Model_Lastfm();
            $mp3mapper = new Application_Model_Mp3Mapper();
            $oAlbummapper = new Application_Model_AlbumMapper();
            foreach($files as $file) {
                $id3 = new Application_Model_Id3($file);
                $mp3File = $mp3mapper->searchfile($id3->getPath(), $id3->getFilename());
                $oAlbum = $oAlbummapper->fetchOneByName(
                        $id3->getAlbum(),
                        $id3->getArtist()
                        );
                
                if(!$oAlbum->getId()) {
                    $oMeta = $lastfm->getAlbuminfo( $id3->getArtist(), $id3->getAlbum());
                    $oAlbum->setArtist($id3->getArtist())
                            ->setAlbum($id3->getAlbum())
                            ->setMbid($oMeta->album->mbid);
                    
                    $oAlbummapper->save($oAlbum);
                    $oAlbum = $oAlbummapper->fetchOneByName(
                        $id3->getAlbum(),
                        $id3->getArtist()
                        );
                }
                $mp3File->setArtist($id3->getArtist())
                        ->setTitle($id3->getTitle())
                        ->setAlbum($oAlbum->getId())
                        ->setGenre($id3->getGenre())
                        ->setYear($id3->getYear())
                        ->setHash(md5($file))
                        ->setPath($id3->getPath())
                        ->setFilename($id3->getFilename());
                
                $mp3mapper->save($mp3File);
            }
            $this->_helper->redirector('view', 'index', 'default');
        } catch (Exception $ex) {
            Zend_Debug::dump($ex);
        }
    }
    public function albenAction() {
        $oAlbummapper = new Application_Model_AlbumMapper();
        $aList = $oAlbummapper->fetchAll();
        $this->view->alben = $aList;
        $this->view->lastfm = new Application_Model_Lastfm();
    }
    public function albumAction() {
        $oMp3mapper = new Application_Model_Mp3Mapper();
        $oAlbummapper = new Application_Model_AlbumMapper();
        $aSongs = $oMp3mapper->fetchAlbum(
                $this->_request->getParam('alb')
                );
        $this->view->album = $oAlbummapper->fetchOne($this->_request->getParam('alb'));
        $this->view->songs = $aSongs;
        $this->view->lastfm = new Application_Model_Lastfm();
    }
}
