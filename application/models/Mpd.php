<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Mpd
{
    private $_mpd_sock;
    private $_sHost;
    private $_iPort;

    const MPD_RESPONSE_OK = "OK";
    const MPD_RESPONSE_ERR = "ACK";
    
    public function __construct() {
        $this->_sHost = "127.0.0.1";
        $this->_iPort = 6600;
        $this->connect($this->_sHost, $this->_iPort);
    }
    public function connect($sHost, $iPort) {
        try{
            $this->_mpd_sock = @fsockopen($sHost,$iPort,$errNo,$errStr,5);
            if(!$this->_mpd_sock) {
                return FALSE;
            }
            while(!feof($this->_mpd_sock)) {
                $response =  fgets($this->_mpd_sock,1024);
                if (strncmp(self::MPD_RESPONSE_OK,$response,strlen(self::MPD_RESPONSE_OK)) == 0) {
                        return $response;
                }
                if (strncmp(self::MPD_RESPONSE_ERR,$response,strlen(self::MPD_RESPONSE_ERR)) == 0) {
                        // close socket
                        fclose($this->_mpd_sock);
                        return FALSE;
                }
            }
        } catch (Exception $x) {
            return FALSE;
        }
    }
    
    public function request($sCommand, $sArg1="", $sArg2="") {
        if(!$this->_mpd_sock) {
            if(!$this->connect($this->_sHost, $this->_iPort)) {
                return FALSE;
            }
        }
        try{
            if (strlen($sArg1) > 0) $sCommand .= " \"$sArg1\"";
            if (strlen($sArg2) > 0) $sCommand .= " \"$sArg2\"";
            fputs($this->_mpd_sock,"$sCommand\n");
            $res = "";
            while(!feof($this->_mpd_sock)) {
                $response = fgets($this->_mpd_sock,1024);
                $res .= $response;
                if (strncmp(self::MPD_RESPONSE_OK,$response,strlen(self::MPD_RESPONSE_OK)) == 0) {
                    break;
                }
                if (strncmp(self::MPD_RESPONSE_ERR,$response,strlen(self::MPD_RESPONSE_ERR)) == 0) {
                    Zend_Debug::dump($response);
                    break;
                }
            }

        } catch (Exception $x) {
            
        }
        return $res;
    }
    
    private function parseResponse($sData) {
        $sData = explode("\n", $sData);
        if(count($sData) <=2) {
            return array();
        }
        $aReturn = array();
        for($i=0;$i<count($sData)-2; $i++) {
            $aData = explode(': ', $sData[$i], 2);
            $tag = $aData[0];
            $data = $aData[1];
            $aReturn[$tag] = $data;
        }
        return $aReturn;
    }
    private function parseMultiResponse($sData) {
        $sData = explode("\n", $sData);
        if(count($sData) <=2) {
            return array();
        }
        $aReturn = array(array());
        $iEle = 0;
        for($i=0;$i<count($sData)-2; $i++) {
            $aData = explode(': ', $sData[$i], 2);
            $tag = $aData[0];
            $data = $aData[1];
            if($data === "") continue;
            if(array_key_exists($tag, $aReturn[$iEle])) {
                $iEle++;
            }
            $aReturn[$iEle][$tag] = $data;
        }
        return $aReturn;
    }
    
    public function getPlaylistinfo() {
        $sPlaylist = $this->request('playlistinfo', "");
        return $this->parseMultiResponse($sPlaylist);
    }
    public function getDir($sDir = "") {
        return $this->request('lsinfo', $sDir);
    }
    public function getCurrent() {
        return $this->parseResponse($this->request('currentsong'));
    }
    public function getStatus() {
        return $this->parseResponse($this->request('status'));
    }
    public function getCommands() {
        return $this->request('commands');
    }
    public function getAlbums() {
        return $this->parseMultiResponse($this->request('list','album'));
    }
    public function getArtists() {
        return $this->parseMultiResponse($this->request('list','artist'));
    }
    public function getTitles() {
        return $this->parseMultiResponse($this->request('list','title'));
    }
    public function playSong($iId) {
        return $this->request('play', $iId);
    }
    public function getOutputs() {
        return $this->request('outputs');
    }
    public function setOutput($iOutput, $bStatus=true) {
        if($bStatus == true) {
            $this->request('enable', $iOutput);
        } else {
            $this->request('disable', $iOutput);
        }
    }
}