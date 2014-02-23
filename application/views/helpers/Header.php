<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Breadcrumb
 *
 * @author koberstaedt
 */
class Zend_View_Helper_Header extends Zend_View_Helper_Abstract {
    
    private $_matrix = array(
        'index' => array(
            'index' => array(
                'head' => 'Dashboard', 
                'foot' => 'Übersicht'),
            'view' => array(
                'head' => 'Alben', 
                'foot' => 'Übersicht')
            ),   
        );
    
    public function header($sParam) {
        $sController = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $sAction = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        if($header = $this->getHead($sController, $sAction)) {
            return $header[$sParam];
        }
        switch ($sParam) {
            case 'head':
                return ucfirst($sController);
                break;
            case 'foot':
                return $sAction;
                break;
            default:
                return;
        }
    }
    
    private function getHead($sController, $sAction) {
        if(isset($this->_matrix[$sController][$sAction])) {
            return $this->_matrix[$sController][$sAction];
        }
        return false;
    }
    
}
