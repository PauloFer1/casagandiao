<?php
 

//  Constants  ---------------------------------------------------------------

define('tarambola_ROOT', dirname(__FILE__));
define('CORE_ROOT', tarambola_ROOT.'/tarambola');

define('APP_PATH', CORE_ROOT.'/app'); 

require_once(CORE_ROOT.'/utils.php');

$config_file = tarambola_ROOT.'/config.php';

require_once($config_file);

// if you have installed tarambola and see this line, you can comment it or delete it :)
if ( ! defined('DEBUG')) { header('Location: install/'); exit(); }

// Security checks -----------------------------------------------------------
if (DEBUG == false && isWritable($config_file)) {
    // Windows systems always have writable config files... skip those.
    if (substr(PHP_OS, 0, 3) != 'WIN') {
        echo '<html><head><title>Tarabackend automaticamente inactivo!</title></head><body>';
        echo '<h1>Tarabackend automaticamente inactivo!</h1>';
        echo '<p>Modo de Segurança.</p>';
        echo '<p><strong>Reason:</strong> config.php está editável.</p>';
        echo '</body></html>';
        exit();
    }
}

//  Init  --------------------------------------------------------------------

define('BASE_URL', URL_PUBLIC . (endsWith(URL_PUBLIC, '/') ? '': '/') . (USE_MOD_REWRITE ? '': '?'));

require CORE_ROOT.'/Framework.php';

if (USE_PDO)
{
    try 
	{
        $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS); 
	} 
	catch (PDOException $error) 
	{
        die('DB Connection failed: '.$error->getMessage());
	}
    
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

Setting::init();

use_helper('I18n');
I18n::setLocale(Setting::get('language'));

// run everything!
require APP_PATH.'/main.php';
