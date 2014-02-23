<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Playlist
{
    private $_mpd;
    private $_playlist;
    
    public function __construct(Application_Model_Mpd $mpd) {
        $this->_mpd = $mpd;
        $this->loadPlaylist();
    }
    public function loadPlaylist() {
        $this->_playlist = array();
        $aPlaylist = $this->_mpd->getPlaylistinfo();
        $aCurrent = $this->_mpd->getCurrent();
        $aStatus = $this->_mpd->getStatus();
        foreach($aPlaylist as $aSong) {
            
            $aSong['active'] = FALSE;
            if(isset($aCurrent['Id']) && $aSong['Id'] == $aCurrent['Id']) {
                $aSong['active'] = TRUE;
            }
            if(isset($aStatus['elapsed']) && $aStatus['songid'] == $aSong['Id']) {
                $aSong['elapsed'] = (int) $aStatus['elapsed'];
            }
            if (!isset($aSong['Last-Modified'])) {
                $aSong['Timeformat'] = false;
                $aSong['Time'] = 0;
                $aSong['Artist'] = basename($aSong['file']);
                $aSong['Title'] = "Radiostream";
            }
            $time = new Zend_Date();
            $time->setTime('00:00:00');
            $time->addSecond($aSong['Time']);
            $aSong['Timeformat'] = $time->toString('mm:ss');
            $this->_playlist[] = $aSong;
        }
        
    }
    public function getPlaylist() {
        return $this->_playlist;
    }
    public function getActiveSong() {
        foreach($this->_playlist as $aSong) {
            if($aSong['active']) {
                return $aSong;
            }
        }
        return FALSE;
    }
}