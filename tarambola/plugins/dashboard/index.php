<?php

/*
 * Dashboard - tarambola CMS dashboard plugin
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

define('DASHBOARD_LOG_EMERG',    0);
define('DASHBOARD_LOG_ALERT',    1);
define('DASHBOARD_LOG_CRIT',     2);
define('DASHBOARD_LOG_ERR',      3);
define('DASHBOARD_LOG_WARNING',  4);
define('DASHBOARD_LOG_NOTICE',   5);
define('DASHBOARD_LOG_INFO',     6);
define('DASHBOARD_LOG_DEBUG',    7);


Plugin::setInfos(array(
    'id'          => 'dashboard',
    'title'       => __('Dashboard'), 
    'description' => __('Tarabackend records'),
    'version'     => '0.4.0',
    'license'     => 'MIT',
    'author'      => 'Paulo Fernandes',
    'require_tarambola_version' => '0.9.4',
    'website'     => 'http://www.tarambola.pt'
));

/* Stuff for backend. */
if (strpos($_SERVER['PHP_SELF'], ADMIN_DIR.'/index.php')) {
    
    AutoLoader::addFolder(dirname(__FILE__) . '/models');
    AutoLoader::addFolder(dirname(__FILE__) . '/lib');
    
    Plugin::addController('dashboard', __('Dashboard'));

    Observer::observe('page_edit_after_save',   'dashboard_log_page_edit');
    Observer::observe('page_add_after_save',    'dashboard_log_page_add');
    Observer::observe('page_delete',            'dashboard_log_page_delete');
    
    /* These currently only work in MIT fork (Toad) or 0.9.5 version of tarambola. */
    Observer::observe('layout_after_delete',    'dashboard_log_layout_delete');
    Observer::observe('layout_after_add',       'dashboard_log_layout_add');
    Observer::observe('layout_after_edit',      'dashboard_log_layout_edit');
    
    Observer::observe('snippet_after_delete',    'dashboard_log_snippet_delete');
    Observer::observe('snippet_after_add',       'dashboard_log_snippet_add');
    Observer::observe('snippet_after_edit',      'dashboard_log_snippet_edit');

    Observer::observe('comment_after_delete',    'dashboard_log_comment_delete');
    Observer::observe('comment_after_add',       'dashboard_log_comment_add');
    Observer::observe('comment_after_edit',      'dashboard_log_comment_edit');
    Observer::observe('comment_after_approve',   'dashboard_log_comment_approve');
    Observer::observe('comment_after_unapprove', 'dashboard_log_comment_unapprove');
    
    Observer::observe('plugin_after_enable',     'dashboard_log_plugin_enable');
    Observer::observe('plugin_after_disable',    'dashboard_log_plugin_disable');

    Observer::observe('admin_login_success',     'dashboard_log_admin_login');
    Observer::observe('admin_login_failed',      'dashboard_log_admin_login_failure');
    Observer::observe('admin_after_logout',      'dashboard_log_admin_logout');
    
    Observer::observe('log_event',               'dashboard_log_event');
    
    function dashboard_log_page_add($page) {
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('page/edit/' . $page->id), $page->title);
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Página <b>:title</b> foi criada por :name',
                                 array(':title' => $linked_title, 
                                       ':name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_page_edit($page) {
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('page/edit/' . $page->id), $page->title);
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Página <b>:title</b> foi revista por :name',
                                 array(':title' => $linked_title, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_page_delete($page) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Página <b>:title</b> foi apagada por :name',
                                 array(':title' =>  $page->title, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_layout_delete($layout) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Layout <b>:title</b> foi apagado por :name',
                                 array(':title' =>  $layout->name, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_layout_add($layout) {
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('layout/edit/' . $layout->id), $layout->name);
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Layout <b>:title</b> foi criado por :name',
                                 array(':title' =>  $linked_title, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_layout_edit($layout) {
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('layout/edit/' . $layout->id), $layout->name);
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Layout <b>:title</b> foi revisto por :name',
                                 array(':title' =>  $linked_title, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    /* Snippet */
    
    function dashboard_log_snippet_delete($snippet) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Snippet <b>:title</b> foi apagado por :name',
                                 array(':title' =>  $snippet->name, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_snippet_add($snippet) {
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('snippet/edit/' . $snippet->id), $snippet->name);
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Snippet <b>:title</b> foi criado por :name',
                                 array(':title' =>  $linked_title, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_snippet_edit($snippet) {
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('snippet/edit/' . $snippet->id), $snippet->name);
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Snippet <b>:title</b> foi revisto por :name',
                                 array(':title' =>  $linked_title, 
                                       ':name' => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_comment_add($comment) {

        /*
        It seems Page class here is NOT the model but the other Page class?
        $page = Page::findByIdFrom('Page', $comment->page_id);
        */
        
        /* TODO: Fetch page title. */
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('plugin/comment/edit/' . $comment->id), 'posted a comment');
        $data['ident']    = 'comment';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __(':name :title.', 
                                 array(':name'  => $comment->author_name,
                                       ':title' =>  $linked_title));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_comment_delete($comment) {
        
        /* TODO: Fetch page title. */
        $data['ident']    = 'comment';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __(':admin_name apagou comentário de :author_name.',
                                 array(':author_name' => $comment->author_name,
                                       ':admin_name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_comment_approve($comment) {
        
        /* TODO: Fetch page title. */
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('plugin/comment/edit/' . $comment->id), 'comment');
        $data['ident']    = 'comment';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __(':admin_name aprovou :title de :author_name.',
                                 array(':author_name' => $comment->author_name,
                                       ':title'       => $linked_title,
                                       ':admin_name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_comment_unapprove($comment) {
        
        /* TODO: Fetch page title. */
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('plugin/comment/edit/' . $comment->id), 'comment');
        $data['ident']    = 'comment';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __(':admin_name rejeitou :title de :author_name.',
                                 array(':author_name' => $comment->author_name,
                                       ':title'       => $linked_title,
                                       ':admin_name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_comment_edit($comment) {
        
        /* TODO: Fetch page title. */
        $linked_title     = sprintf('<a href="%s">%s</a>', 
                                    get_url('plugin/comment/edit/' . $comment->id), 'comment');
        $data['ident']    = 'comment';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __(':admin_name editou :title de :author_name.',
                                 array(':author_name' => $comment->author_name,
                                       ':title'       => $linked_title,
                                       ':admin_name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_plugin_enable($plugin) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Plugin <b>:title</b> activado por :name',
                                 array(':title' => $plugin,
                                       ':name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_plugin_disable($plugin) {        
        $plugin_data      = Plugin::findAll();
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Plugin <b>:title</b> desactivado por :name',
                                 array(':title' => $plugin, 
                                       ':name'  => AuthUser::getRecord()->name));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_admin_login($username) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Utilizador <b>:name</b> efectuou Log In.',
                                 array(':name'  => $username));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

    function dashboard_log_admin_login_failure($username) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Utilizador <b>:name</b> falhou o Log In.',
                                 array(':name'  => $username));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_admin_logout($username) {
        $data['ident']    = 'core';
        $data['priority'] = DASHBOARD_LOG_NOTICE;
        $data['message']  = __('Utilizador <b>:name</b> efectuou Log Out.',
                                 array(':name'  => $username));
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }
    
    function dashboard_log_event($message, $priority=DASHBOARD_LOG_NOTICE, $ident='misc') {
        $data['ident']    = $ident;
        $data['priority'] = $priority;
        $data['message']  = $message;
        $entry = new DashboardLogEntry($data);
        $entry->save();
    }

} 
