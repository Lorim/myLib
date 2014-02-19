<?php
class Application_Model_Album
{
    protected $_id;
    protected $_album;
    protected $_artist;
    protected $_mbid;

 
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
    public function setArtist($artist) {
        $this->_artist = $artist;
        return $this;
    }
    public function getArtist() {
        return $this->_artist;
    }
    public function setMbid($mbid) {
        $this->_mbid = $mbid;
        return $this;
    }
    public function getMbid() {
        return $this->_mbid;
    }
}
