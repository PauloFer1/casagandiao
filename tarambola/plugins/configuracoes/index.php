<?php


define('configuracoes_LOG_EMERG',    0);
define('configuracoes_LOG_ALERT',    1);
define('configuracoes_LOG_CRIT',     2);
define('configuracoes_LOG_ERR',      3);
define('configuracoes_LOG_WARNING',  4);
define('configuracoes_LOG_NOTICE',   5);
define('configuracoes_LOG_INFO',     6);
define('configuracoes_LOG_DEBUG',    7);


Plugin::setInfos(array(
    'id'          => 'configuracoes',
    'title'       => __('Configurations'),
    'description' => __('Configurations list'),
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
    
    Plugin::addController('configuracoes',  __('Configurations'));

   
} 
