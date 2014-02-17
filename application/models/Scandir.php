<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Scandir
{
    private $_aConfig;

    
    public function __construct() {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam('bootstrap');
        if (null === $bootstrap) {
            throw new My_Exception('Unable to find bootstrap');
        }
        $this->_aConfig = $bootstrap->getOptions();
    }
    
    public function scan($sDir=NULL) {
        if(!$sDir) {
            $sDir = $this->_aConfig['mylib']['root'];
        }
        $directory = new RecursiveDirectoryIterator(
                realpath($sDir),
                RecursiveDirectoryIterator::SKIP_DOTS
                );
        $iterator = new RecursiveIteratorIterator(
                $directory,
                RecursiveIteratorIterator::LEAVES_ONLY
                );

        $extensions = array("mp3");

        foreach ($iterator as $fileinfo) {
            if (in_array($fileinfo->getExtension(), $extensions)) {
                $files[] = $fileinfo->getPathname();
            }
        }
        return $files;
    }
    
    public function normalize() {
        $files = $this->scan();
        foreach($files as $file) {
            $path_parts = pathinfo($file);
            $target = $path_parts['dirname'].
                    '/'.
                    str_replace(
                            array(" "),
                            "_",
                            $path_parts['basename']
                            );
            rename($file, $target);
        }
    }
}