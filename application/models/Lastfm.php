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
        $sCachefile = APPLICATION_PATH."/../public/cache/album_". md5($sArtist.$sAlbum).".json";
        $sCacheAlbum = APPLICATION_PATH."/../public/cache/album/";
        
        if(!file_exists($sCachefile)) {
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
            $json = $response->getBody();
            file_put_contents($sCachefile, $json);
            $xml = json_decode($json);
            /*
             * Cache Cover
             */
            $sMbid = $xml->album->mbid;
            if($sMbid) {
                $sCover = "";
                foreach($xml->album->image as $image) {
                    $sCover = $image->{'#text'};
                    if($image->size == 'large') {
                        break;
                    }
                }
                $client = new Zend_Http_Client($sCover);
                $response = $client->request()->getBody();
                file_put_contents($sCacheAlbum.  basename($sCover), $response);
            }
            } catch (Exception $e) {
                Zend_Debug::dump($e);
            } 
        } else {
            $json = file_get_contents($sCachefile);
        }
        return json_decode($json);
    }
    public function getTrackinfo($sArtist, $sTrack) {
        $sCachefile = APPLICATION_PATH."/../public/cache/track_". md5($sArtist.$sTrack).".json";
        
        if(!file_exists($sCachefile)) {
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

            $json = $response->getBody();
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