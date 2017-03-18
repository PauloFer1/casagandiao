<?php


//  Constantes  --------------------------------------------------------------

define('tarambola_VERSION', '0.9.5');

define('tarambola_ROOT', dirname(__FILE__).'/..');
define('CORE_ROOT', tarambola_ROOT.'/tarambola');

require_once(CORE_ROOT.'/utils.php');

$config_file = tarambola_ROOT.'/config.php';

require_once($config_file);

// Security checks -----------------------------------------------------------
if (DEBUG == false && isWritable($config_file)) {
    // Windows systems always have writable config files... skip those.
    if (substr(PHP_OS, 0, 3) != 'WIN') {
        echo '<html><head><title>Tarabackend desactivado automáticamente!</title></head><body>';
        echo '<h1>Tarabackend desactivado automáticamente!</h1>';
        echo '<p>Tarabackend foi desactivado automáticamente por questões de segurança!</p>';
        echo '<p><strong>Causa:</strong> ficheiro de configuração com permissões de escrita.</p>';
        echo '</body></html>';
        exit();
    }
}

define('APP_PATH',  CORE_ROOT.'/app');

define('SESSION_LIFETIME', 3600);
define('REMEMBER_LOGIN_LIFETIME', 1209600); // two weeks

define('DEFAULT_CONTROLLER', 'page');
define('DEFAULT_ACTION', 'index');

define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);

define('BASE_URL', URL_PUBLIC . (endsWith(URL_PUBLIC, '/') ? '': '/') . ADMIN_DIR . (endsWith(ADMIN_DIR, '/') ? '': '/') . (USE_MOD_REWRITE ? '': '?/'));

require CORE_ROOT.'/Framework.php';

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if($actual_link == URL_PUBLIC."funcionalidades/404.html")
        return 0;

//  Database connection  -----------------------------------------------------

if (USE_PDO)
{
    $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
}
else
{
    require_once CORE_ROOT . '/libraries/DoLite.php';
    $__tarambola_CONN__ = new DoLite(DB_DSN, DB_USER, DB_PASS);
}

Record::connection($__tarambola_CONN__);
Record::getConnection()->exec("set names 'utf8'");


//  Initialize  --------------------------------------------------------------

Setting::init();

use_helper('I18n');
I18n::setLocale(Setting::get('language'));

Plugin::init();

//  Get controller and action and execute  -----------------------------------

Dispatcher::dispatch(null, Setting::get('default_tab'));
