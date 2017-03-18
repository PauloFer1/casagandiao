<?php 

// Database information:
// for SQLite, use sqlite:/tmp/tarambola.db (SQLite 3)
// The path can only be absolute path or :memory:
// For more info look at: www.php.net/pdo

// Database settings:
define('DB_DSN', 'mysql:dbname=gandiao;host=localhost;port=3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('TABLE_PREFIX', 'tara_');
//Tamanho M???ximo para upload de imagens
define('MAX_IMG_UPLOAD', 10);
//Tamanho M???ximo para upload de ficheiros
define('MAX_FILE_UPLOAD', 10);
// Should tarambola produce PHP error messages for debugging?
define('DEBUG', TRUE);
define('DEBUG_PAYPAL', false);

// Should tarambola check for updates on tarambola itself and the installed plugins?
define('CHECK_UPDATES', false);

// The number of seconds before the check for a new tarambola version times out in case of problems.
define('CHECK_TIMEOUT', 3);

//site domain
define('SITE_NAME', 'casagandiao');
// The full URL of your tarambola CMS install
define('URL_PUBLIC', 'http://localhost:80/'.SITE_NAME.'/');
 
// url completo do servidor 
define( 'SERVER_URL', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_NAME.'/');

// url das imagens

define('IMG_URL', URL_PUBLIC.'public/images/');

// url dos ficheiros

define('FILE_URL', URL_PUBLIC.'public/files/');

// The directory name of your tarambola CMS administration (you will need to change it manually)
define('ADMIN_DIR', 'tarabackend');

// Change this setting to enable mod_rewrite. Set to "true" to remove the "?" in the URL.
// To enable mod_rewrite, you must also change the name of "_.htaccess" in your
// tarambola CMS root directory to ".htaccess"
define('USE_MOD_REWRITE', true);

// Add a suffix to pages (simluating static pages '.html')
define('URL_SUFFIX', '.html');

// If your server doesn't have PDO (with MySQL driver) set the below to false:
define('USE_PDO', true);

define('USE_LOGIN', true);

// Set the timezone of your choice.
// Go here for more information on the available timezones:
// http://php.net/timezones
define('DEFAULT_TIMEZONE', 'Europe/Lisbon');

define('IN_tarambola', true);
