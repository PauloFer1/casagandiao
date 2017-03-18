<?php

    function _getDSN($dsn)
    {
        $result = array();
        $pos = strpos($dsn, ':');
        $params = explode(';', substr($dsn, ($pos + 1)));
        $result['dbtype'] = strtolower(substr($dsn, 0, $pos));
        $nb_params = count($params);
        for ($a = 0, $b = $nb_params; $a < $b; $a++) {
            $tmp = explode('=', $params[$a]);
            if (count($tmp) == 2)
                $result[$tmp[0]] = $tmp[1];
            else
                $result['dbname'] = $params[$a];
        }
        return $result;
    }
$arr = _getDSN(DB_DSN);
define("_DB_NAME",$arr['dbname']);
define("_DB_USER",DB_USER);
define("_DB_PASS",DB_PASS);
define("_DB_SERVER",$arr['host']);
define("_TEMPLATE_DIR",SERVER_URL."tarambola/plugins/newsletter/views/templates/");
define("_NEWSLETTERS_DIR",SERVER_URL."tarambola/plugins/newsletter/views/newsletters/");
define("_IMAGES_DIR",SERVER_URL."tarambola/plugins/newsletter/views/images/");
define("_MAIL_SMTP",true); 
define("_MAIL_SMTP_HOST","montanea.pt");
define("_MAIL_SMTP_AUTH",true); 
define("_MAIL_SMTP_USER","sender@montanea.pt");
define("_MAIL_SMTP_PASS","12345");


define("_DEBUG_MODE",true);
define("_DEMO_MODE",false);
ini_set("display_errors",_DEBUG_MODE);

// date format for printing dates to the screen (uses php date syntax)
define("_DATE_FORMAT","d/m/Y"); 
// date format for inputting dates into the system
// 1 = DD/MM/YYYY
// 2 = YYYY/MM/DD
// 3 = MM/DD/YYYY
define("_DATE_INPUT",1); 
switch(_DATE_INPUT){
	case 1: define('_DATE_INPUT_HELP','DD/MM/YYYY'); break;
	case 2: define('_DATE_INPUT_HELP','YYYY/MM/DD'); break;
	case 3: define('_DATE_INPUT_HELP','MM/DD/YYYY'); break;
}


?>