<?php

if (!defined("FRAMEWORK_STARTING_MICROTIME")) {
    die("All your base are belong to us!");
}

$PDO = Record::getConnection();

$table = TABLE_PREFIX . "formularios_log";
$PDO->exec("DROP TABLE $table");
