<?php

/**

/**
 * The FileManager allows users to upload and manipulate files.
 *
 * @package tarambola
 * @subpackage plugin.file_manager
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.0.0
 * @since tarambola version 0.9.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault & Martijn van der Kleijn, 2008
 */

/**
 * Root location where files get uploaded to as an absolute path.
 */
define('FILES_DIR', tarambola_ROOT.'/public');

/**
 * Root location where files get uploaded to as a URL.
 */
define('BASE_FILES_DIR', URL_PUBLIC . 'public'); 

// DO NOT EDIT AFTER THIS LINE -----------------------------------------------

Plugin::setInfos(array(
    'id'          => 'file_manager',
    'title'       => 'File Manager', 
    'description' => 'Provides interface to manage files from the administration.', 
    'version'     => '1.0.0', 
    'website'     => 'http://www.tarambola.pt/',
));

Plugin::addController('file_manager', 'Editor de ficheiros', 'developer,editor');

// Make sure possible hack attempts get registered if the statistics API is available.
if (Plugin::isEnabled('statistics_api')) {
    Observer::observe('stats_file_manager_hack_attempt', 'StatisticsEvent::registerEvent');
}