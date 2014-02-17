<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Lastfm
{
    private $_apikey;
    private $_baseurl;
    public function __construct() {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam('bootstrap');
        if (null === $bootstrap) {
            throw new My_Exception('Unable to find bootstrap');
        }
        $aConfig = $bootstrap->getOptions();
        $this->_apikey = $aConfig['lastfm']['api'];
        $this->_baseurl = 'http://ws.audioscrobbler.com/2.0/';
    }
    
    public function getAlbuminfo($sArtist, $sAlbum) {
        $sCachefile = APPLICATION_PATH."/../public/cache/". md5($sArtist.$sAlbum).".json";
        
        if(!file_exists($sCachefile)) {
            $request = $this->_baseurl.
                    "?method=album.getinfo&api_key=".
                    $this->_apikey.
                    "&artist=".$sArtist."&album=".$sAlbum.
                    "&autocorrect=1".
                    "&format=json";
            try {
            $client = new Zend_Http_Client($this->_baseurl);
            $client->setParameterGet(
                    array(
                        'method' => 'album.getinfo',
                        'api_key' => $this->_apikey,
                        'artist' => $sArtist,
                        'album' => $sAlbum,
                        'format' => 'json',
                    )
                    );
            $response = $client->request();

            $json = $response->decodeGzip($response->getRawBody());
            file_put_contents($sCachefile, $json);
            } catch (Exception $e) {
                Zend_Debug::dump($e);
            } 
        } else {
            $json = file_get_contents($sCachefile);
        }
        return json_decode($json);
    }
    public function getTrackinfo($sArtist, $sTrack) {
        $sCachefile = APPLICATION_PATH."/../public/cache/". md5($sArtist.$sTrack).".json";
        
        if(!file_exists($sCachefile)) {
            $request = $this->_baseurl.
                    "?method=album.getinfo&api_key=".
                    $this->_apikey.
                    "&artist=".$sArtist."&album=".$sTrack.
                    "&autocorrect=1".
                    "&format=json";
            try {
            $client = new Zend_Http_Client($this->_baseurl);
            $client->setParameterGet(
                    array(
                        'method' => 'track.getinfo',
                        'api_key' => $this->_apikey,
                        'artist' => $sArtist,
                        'track' => $sTrack,
                        'format' => 'json',
                    )
                    );
            $response = $client->request();

            $json = $response->decodeGzip($response->getRawBody());
            file_put_contents($sCachefile, $json);
            } catch (Exception $e) {
                Zend_Debug::dump($e);
            } 
        } else {
            $json = file_get_contents($sCachefile);
        }
        return json_decode($json);
    }
}