<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    private $_whitelist;
    protected $_request;

    public function __construct() {
        $this->_whitelist = array(
            'error',
        );
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->_request = $request;
        $module = strtolower($this->_request->getControllerName());

        if (in_array($module, $this->_whitelist)) {
            return;
        }

        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_request->setModuleName('default'); 
            $this->_request->setControllerName('index');
            $this->_request->setActionName('login');
            return;
        }
    }
}