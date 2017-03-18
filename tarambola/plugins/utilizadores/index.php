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

define('utilizadores_LOG_EMERG',    0);
define('utilizadores_LOG_ALERT',    1);
define('utilizadores_LOG_CRIT',     2);
define('utilizadores_LOG_ERR',      3);
define('utilizadores_LOG_WARNING',  4);
define('utilizadores_LOG_NOTICE',   5);
define('utilizadores_LOG_INFO',     6);
define('utilizadores_LOG_DEBUG',    7);


Plugin::setInfos(array(
    'id'          => 'utilizadores',
    'title'       => 'Utilizadores',
    'description' => 'Lista Utilizadores',
    'version'     => '0.3.1', 
    'license'     => 'MIT',
    'author'      => 'Paulo Fernandes',
    'require_frog_version' => '0.9.4',
    'website'     => 'http://www.tarambola.pt/'
));

/* Stuff for backend. */
if (strpos($_SERVER['PHP_SELF'], ADMIN_DIR.'/index.php')) {

    AutoLoader::addFolder(dirname(__FILE__) . '/models');
    AutoLoader::addFolder(dirname(__FILE__) . '/lib');
    
    Plugin::addController('utilizadores', 'Utilizadores frontend');
    

    Plugin::addJavascript('utilizadores',  "js/front_users.js");
    
   
}
