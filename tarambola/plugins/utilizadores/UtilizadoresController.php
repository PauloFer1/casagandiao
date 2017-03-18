<?php

/*
 * utilizadores - Frog CMS utilizadores plugin
 *
 * Copyright (c) 2008-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/
 *
 */
 
class utilizadoresController extends PluginController
{
    function __construct() {
        AuthUser::load();
        if (!(AuthUser::isLoggedIn())) {
            redirect(get_url('login'));            
        }

        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/utilizadores/views/sidebar'));
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
    function index() {
        $this->_checkPermission();
        $pdo = Record::getConnection();

        if ('mysql' == $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {

            $this->display('utilizadores/views/index', array(
                'log_entry_today'     => Record::findAllFrom('utilizadoresLogEntry', 'created_on > CURRENT_DATE() ORDER BY created_on DESC'),
                'log_entry_yesterday' => Record::findAllFrom('utilizadoresLogEntry', 'created_on > DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY) AND created_on < CURRENT_DATE() ORDER BY created_on DESC'),
                'log_entry_older'     => Record::findAllFrom('utilizadoresLogEntry', 'created_on < DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY) ORDER BY created_on DESC')
            ));            
        } else {

            $this->display('utilizadores/views/index', array(
                'log_entry_today'     => Record::findAllFrom('utilizadoresLogEntry', "created_on > DATE('now') ORDER BY created_on DESC"),
                'log_entry_yesterday' => Record::findAllFrom('utilizadoresLogEntry', "created_on > DATE('now', 'start of day', '-1 day') AND created_on < DATE('now', 'start of day') ORDER BY created_on DESC"),
                'log_entry_older'     => Record::findAllFrom('utilizadoresLogEntry', "created_on < DATE('now', 'start of day', '-1 day') ORDER BY created_on DESC")
            ));            
        }
    }
    function add(){
        $this->_checkPermission();
        $this->display('utilizadores/views/add');
    }
    function view(){
        $this->_checkPermission();
        $this->display('utilizadores/views/view');
    }
    function addNotBtn()
    {
        $idPage=Page::find('noticias')->id;
        $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add".$idPage;
        return($newUrl);
    }
    function clear() {
        $log_entry = Record::findAllFrom('utilizadoresLogEntry');
        foreach ($log_entry as $entry) {
            $entry->delete();
        }
        
        redirect(get_url('plugin/utilizadores/'));
    }    
}
