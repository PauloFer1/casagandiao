<?php

 
class DashboardController extends PluginController
{
    const PLUGIN_ID      = "dashboard";
     
    function __construct() {
        AuthUser::load();
        if (!(AuthUser::isLoggedIn())) {
            redirect(get_url('login'));            
        }

        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/dashboard/views/sidebar'));
        Plugin::addJavascript(self::PLUGIN_ID, "gettingStarted.js");
        Plugin::addJavascript(self::PLUGIN_ID, "jquery-1.4.2.min.js");
        Plugin::addJavascript(self::PLUGIN_ID, "jquery-ui-1.8.2.custom.min.js");
        Plugin::addJavascript(self::PLUGIN_ID, "analyticsCharts.js");
        Plugin::addJavascript(self::PLUGIN_ID, "reporting.js");
        Plugin::addJavascript(self::PLUGIN_ID, "analytics.js");
    }

    function index() 
    {
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $envio = Encomendas::getEncomendasEnvio();
        $encomendas = Encomendas::getEncomendas();
        
        $this->display('dashboard/views/index', array('encomendas'=>$encomendas, 'envio'=>$envio));
    }
    
    function clear() {
        $log_entry = Record::findAllFrom('DashboardLogEntry');
        foreach ($log_entry as $entry) {
            $entry->delete();
        }        
        redirect(get_url('plugin/dashboard/'));   
    }    
     public static function _checkPermission()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if ( ! AuthUser::hasPermission('administrator,developer,editor'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));
            redirect(get_url());
        }
    }
}
