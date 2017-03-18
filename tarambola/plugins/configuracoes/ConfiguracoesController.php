<?php


 
class ConfiguracoesController extends PluginController
{
    const PLUGIN_ID      = "configuracoes";

    function __construct() {
        AuthUser::load();
        if (!(AuthUser::isLoggedIn())) {
            redirect(get_url('login'));            
        }

        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/configuracoes/views/sidebar'));
        Plugin::addJavascript(self::PLUGIN_ID, "jquery-1.4.2.min.js");
        Plugin::addJavascript(self::PLUGIN_ID, "jquery-ui-1.8.2.custom.min.js");
        Plugin::addJavascript(self::PLUGIN_ID, "js/tinymce/jquery.tinymce.min.js");
        Plugin::addJavascript(self::PLUGIN_ID, "js/functions.js");
    }

    function index() {
        $pdo = Record::getConnection();

        if ('mysql' == $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {

            $this->display('configuracoes/views/index', array(
                'log_entry_today'     => Record::findAllFrom('configuracoesLogEntry', 'created_on > CURRENT_DATE() ORDER BY created_on DESC'),
                'log_entry_yesterday' => Record::findAllFrom('configuracoesLogEntry', 'created_on > DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY) AND created_on < CURRENT_DATE() ORDER BY created_on DESC'),
                'log_entry_older'     => Record::findAllFrom('configuracoesLogEntry', 'created_on < DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY) ORDER BY created_on DESC')
            ));            
        } else {

            $this->display('configuracoes/views/index', array(
                'log_entry_today'     => Record::findAllFrom('configuracoesLogEntry', "created_on > DATE('now') ORDER BY created_on DESC"),
                'log_entry_yesterday' => Record::findAllFrom('configuracoesLogEntry', "created_on > DATE('now', 'start of day', '-1 day') AND created_on < DATE('now', 'start of day') ORDER BY created_on DESC"),
                'log_entry_older'     => Record::findAllFrom('configuracoesLogEntry', "created_on < DATE('now', 'start of day', '-1 day') ORDER BY created_on DESC")
            ));            
        }
    }
    /*
    function addNotBtn()
    {
        $idPage=Page::find('configuracoes')->id;
        $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add/".$idPage;
        return($newUrl);
    }
     */
    function clear() {
        $log_entry = Record::findAllFrom('configuracoesLogEntry');
        foreach ($log_entry as $entry) {
            $entry->delete();
        }
        
        redirect(get_url('plugin/configuracoes/'));
    }  
    function view(){
        $this->_checkPermission();
        $this->display('configuracoes/views/view');
    }
    function edit(){
        $this->_checkPermission();
        $this->display('configuracoes/views/edit');
    }
    function add(){
        $this->_checkPermission();
        $this->display('configuracoes/views/add');
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
        public function getmensagemById($id)
        {
            
        }
}
