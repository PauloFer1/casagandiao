<?php


define('paginas_LOG_EMERG',    0);
define('paginas_LOG_ALERT',    1);
define('paginas_LOG_CRIT',     2);
define('paginas_LOG_ERR',      3);
define('paginas_LOG_WARNING',  4);
define('paginas_LOG_NOTICE',   5);
define('paginas_LOG_INFO',     6);
define('paginas_LOG_DEBUG',    7);


Plugin::setInfos(array(
    'id'          => 'paginas',
    'title'       => __('pages'),
    'description' => __('List pages'),
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
    
    Plugin::addController('paginas', __('pages'));

   
} 
