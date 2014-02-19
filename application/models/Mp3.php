<?php
class Application_Model_Mp3
{
    protected $_id;
    protected $_album;
    protected $_artist;
    protected $_comment;
    protected $_genre;
    protected $_title;
    protected $_track;
    protected $_year;
    protected $_hash;
    protected $_path;
    protected $_filename;

 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Ungültige MP3 Eigenschaft');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Ungültige MP3 Eigenschaft');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }
    public function getId() {
        return $this->_id;
    }
    public function setAlbum($album) {
        $this->_album = $album;
        return $this;
    }
    public function getAlbum() {
        return $this->_album;
    }
    public function getAlbumname() {
        $oAlbummapper = new Application_Model_AlbumMapper();
        $oAlbum = $oAlbummapper->fetchOne($this->getAlbum());
        return $oAlbum->getAlbum();
    }
    public function setArtist($artist) {
        $this->_artist = $artist;
        return $this;
    }
    public function getArtist() {
        return $this->_artist;
    }
    public function setComment($comment) {
        $this->_comment = $comment;
        return $this;
    }
    public function getComment() {
        return $this->_comment;
    }
    public function setGenre($genre) {
        $this->_genre = $genre;
        return $this;
    }
    public function getGenre() {
        return $this->_genre;
    }
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }
    public function getTitle() {
        return $this->_title;
    }
    public function setTrack($track) {
        $this->_track = $track;
        return $this;
    }
    public function getTrack() {
        return $this->_track;
    }
    public function setYear($year) {
        $this->_year = $year;
        return $this;
    }
    public function getYear() {
        return $this->_year;
    }
    public function setHash($hash) {
        $this->_hash = $hash;
        return $this;
    }
    public function getHash() {
        return $this->_hash;
    }
    public function setPath($path) {
        $this->_path = $path;
        return $this;
    }
    public function getPath() {
        return $this->_path;
    }
    public function setFilename($filename) {
        $this->_filename = $filename;
        return $this;
    }
    public function getFilename() {
        return $this->_filename;
    }
    
    public function getUrl(){
        return "/lib/".$this->getFilename();
    }
    public function getWebpath() {
        $sWebpath = str_replace(realpath(APPLICATION_PATH.'/../public'),'',$this->getPath());
        return $sWebpath."/".$this->getFilename();
    }
}
