<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModalController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->disableLayout(); 
    }
    
    public function groupAction() {
        $oHue = Zend_Registry::get('hue');
        $iGroup = $this->_request->getParam('id');
        $this->view->group = $oHue->getGroupById($iGroup);
        $this->view->lights = $oHue->getLights();
    }
    
    public function infoAction() {
        $oHue = Zend_Registry::get('hue');
        $iLight = $this->_request->getParam('id');
        try {
            $this->view->light = $oHue->getLightById($iLight);
        } catch (Exception $e) {
            Zend_Debug::dump($e);
        }
    }
}