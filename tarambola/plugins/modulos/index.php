<?php


define('modulos_LOG_EMERG',    0);
define('modulos_LOG_ALERT',    1);
define('modulos_LOG_CRIT',     2);
define('modulos_LOG_ERR',      3);
define('modulos_LOG_WARNING',  4);
define('modulos_LOG_NOTICE',   5);
define('modulos_LOG_INFO',     6);
define('modulos_LOG_DEBUG',    7);


Plugin::setInfos(array(
    'id'          => 'modulos',
    'title'       => __('Modules'),
    'description' => __('Modulos list'),
    'version'     => '0.0',
    'license'     => 'MIT',
    'author'      => 'Paulo Fernandes',
    'require_tarambola_version' => '0.9.4',
    'website'     => 'http://www.tarambola.pt/'
));

/* Stuff for backend. */
if (strpos($_SERVER['PHP_SELF'], ADMIN_DIR.'/index.php')) {
    
    AutoLoader::addFolder(dirname(__FILE__) . '/models');
    AutoLoader::addFolder(dirname(__FILE__) . '/lib');
    
    Plugin::addController('modulos', __('Modules'));

   
} 
