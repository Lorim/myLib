<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Id3
{
    private $_sPath;
    private $_sFilename;
    private $_id3;
    public function __construct($sPath) {
        try{
            $this->_sPath = dirname($sPath);
            $this->_sFilename = basename($sPath);
            $this->_id3 = new Zend_Media_Id3v2($this->_sPath."/".$this->_sFilename);
        }  catch (Exception $ex) {
            echo "  " . $ex->getMessage() . "\n";
        }
    }
    
    public function getPath() {
        return $this->_sPath;
    }
    public function getFilename() {
        return $this->_sFilename;
    }
    public function getArtist() {
        return $this->_id3->tpe1->text;
    }
    public function setArtist($sArtist) {
        $this->_id3->tpe1->text = $sArtist;
        $this->_id3->write($this->_sPath);
    }
    public function getTitle() {
        return $this->_id3->tit2->text;
    }
    public function setTitle($sTitle) {
        $this->_id3->tit2->text = $sTitle;
        $this->_id3->write($this->_sPath);
    }
    public function getAlbum() {
        return $this->_id3->talb->text;
    }
    public function setAlbum($sAlbum) {
        $this->_id3->talb->text = $sAlbum;
        $this->_id3->write($this->_sPath);
    }
    public function getYear() {
        return $this->_id3->tdrc->text;
    }
    public function setYear($iYear) {
        $this->_id3->tdrc->text = $iYear;
        $this->_id3->write($this->_sPath);
    }
    public function getGenre() {
        return $this->_id3->tcon->text;
    }
    public function setGenre($sGenre) {
        $this->_id3->tcon->text = $sGenre;
        $this->_id3->write($this->_sPath);
    }
}