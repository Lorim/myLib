<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        date_default_timezone_set("Europe/Berlin");
    }

    protected function _initAutoLoad() {
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->registerNamespace('Application_');
        return $autoLoader;
    }

    protected function _initAuth()
    {
      $this->bootstrap('frontController');
      $this->getResource('frontController')
           ->registerPlugin(new Application_Plugin_Auth());
    }
    
    protected function _initNavigation() {

        $helper = new Application_Controller_Helper_Acl();

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin(new Application_Controller_Plugin_Acl());

        /**
         * add custom routes
         */
        $router = $frontController->getRouter();
        $router->addRoute(
                'loginroute', new Zend_Controller_Router_Route(
                'login', array(
            'controller' => 'index',
            'action' => 'login'
                )
                )
        );
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $front = Zend_Controller_Front::getInstance();
        $navigation = new Zend_Navigation($config);
        /*
         * load Accesslist
         */
        $acl = Zend_Registry::get("acl");
        $auth = Zend_Auth::getInstance();

        $identity = $auth->getIdentity();

        $view->navigation($navigation);
        $view->navigation()->setAcl($acl);
        $view->navigation()->setDefaultRole("guest");
        if ($auth->getIdentity()) {
            $view->navigation()->setRole($identity->group);
        }
        Zend_Registry::set('nav', $navigation);
    }

    protected function _initDB() {

        $dbOptions = $this->getOption('db');

        $db = Zend_Db::factory(
                        $dbOptions['adapter'], $dbOptions['params']
        );
        $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
        $profiler->setEnabled(false);
        // Den Profiler an den DB Adapter anfügen
        $db->setProfiler($profiler);

        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }

    protected function _initBase() {
        $this->bootstrap('view');
        $view = $this->getResource('view');

        $view->baseUrl = $view->baseUrl();
        $css = array(
            //'/css/bootstrap.css',
            '/css/AdminLTE.css',
            '/css/font-awesome.min.css',
            '/css/ionicons.min.css',
            '/css/site.css',
        );
        foreach ($css as $file) {
            $view->headLink()->appendStylesheet($view->baseUrl($file));
        }
        $js = array(
            '/js/jquery.min.js',
            '/js/bootstrap.js',
            '/js/AdminLTE/app.js',
            '/js/raphael-min.js',
            '/js/plugins/morris/morris.min.js',
            '/js/jquery.progressTimer.js',
            '/js/site.js',
        );
        foreach ($js as $file) {
            $view->headScript()->appendFile($view->baseUrl($file));
        }
    }

}
