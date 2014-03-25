<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class JsonController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function grouplightsAction() {
        $oHue = Zend_Registry::get('hue');
        $oGroup = $this->_request->getParam('id');
        $oHuegroup = $oHue->getGroupById($oGroup);
        $lights = json_decode($this->_request->getRawBody());
        if(!$lights) {
            $lights = array();
        }
        
        $oHuegroup->setLights($lights);
    }
    
    public function stateAction() {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()
         ->setHeader('Content-Type', 'application/json');
        $oData = json_decode($this->_request->getRawBody());
        $oHue = Zend_Registry::get('hue');
        $aReturn = array();
        switch ($oData->action) {
            case 'setOn':
                if($oData->type == 'group') {
                    $oHue->getGroups()[$oData->id]->setOn(true);
                } elseif ($oData->type == 'light') {
                    $oHue->getLights()[$oData->id]->setOn(true);
                }
                break;
            case 'setOff':
                if($oData->type == 'group') {
                    $oHue->getGroups()[$oData->id]->setOn(false);
                } elseif ($oData->type == 'light') {
                    $oHue->getLights()[$oData->id]->setOn(false);
                }
                break;
            case 'toggle':
                if($oData->type == 'group') {
                    $bStatus = $oHue->getGroups()[$oData->id]->isOn();
                    $oHue->getGroups()[$oData->id]->setOn(!$bStatus);
                    $aReturn = array(
                        'id' => $oData->id,
                        'type' => 'group',
                        'status' => !$bStatus
                    );
                } elseif ($oData->type == 'light') {
                    $bStatus = $oHue->getLights()[$oData->id]->isOn();
                    $oHue->getLights()[$oData->id]->setOn(!$bStatus);
                    $aReturn = array(
                        'id' => $oData->id,
                        'type' => 'light',
                        'status' => !$bStatus
                    );
                }
                break;
            case 'blink':
                if($oData->type == 'group') {
                    $oHue->getGroups()[$oData->id]->setAlert(Phue_Command_SetLightState::ALERT_SELECT);
                } elseif ($oData->type == 'light') {
                    $oHue->getLights()[$oData->id]->setAlert(Phue_Command_SetLightState::ALERT_SELECT);
                }
                break;
            case 'setHSV':
                if($oData->type == 'group') {
                    $oHue->getGroups()[$oData->id]->setHue($oData->x);
                } elseif ($oData->type == 'light') {
                    $color = Application_Model_Helper::rgb2xy(
                        $oData->color->r,
                        $oData->color->g,
                        $oData->color->b
                    );
                    $oHue->getLights()[$oData->id]->setXY($color['x'], $color['y']);
                }
                break;
            default:
                break;
        }
        echo json_encode($aReturn);
    }
}