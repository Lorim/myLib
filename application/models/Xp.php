<?php
class Application_Model_Xp
{
    protected $_level;
    protected $_minxp;
    protected $_maxxp;
 
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
            throw new Exception('Ungültige News Eigenschaft');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Ungültige News Eigenschaft');
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
 
    public function setLevel($level)
    {
        $this->_level = $level;
        return $this;
    }
 
    public function getLevel()
    {
        return $this->_level;
    }
    public function setMinxp($xp)
    {
        $this->_minxp = $xp;
        return $this;
    }
    public function getMinxp()
    {
        return $this->_minxp;
    }
    public function setMaxxp($xp)
    {
        $this->_maxxp = $xp;
        return $this;
    }
    public function getMaxxp()
    {
        return $this->_maxxp;
    }
}
