<?php

/**


/**
 * The skeleton plugin serves as a basic plugin template.
 *
 * This skeleton plugin makes use/provides the following features:
 * - A controller without a tab
 * - Three views (sidebar, documentation and settings)
 * - A documentation page
 * - A sidebar
 * - A settings page (that does nothing except display some text)
 * - Code that gets run when the plugin is enabled (enable.php)
 *
 * Note: to use the settings and documentation pages, you will first need to enable
 * the plugin!
 *
 * @package tarambola
 * @subpackage plugin.skeleton
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.0.0
 * @since tarambola version 0.9.5
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Martijn van der Kleijn, 2008
 */

define('DOCUMENTS_DIR', tarambola_ROOT.'/public/documents');

/**
 * Root location where files get uploaded to as a URL.
 */
define('BASE_DOCUMENTS_DIR', URL_PUBLIC . 'public/documents'); 

Plugin::setInfos(array(
    'id'          => 'documentos',
    'title'       => 'Documentos',
    'description' => 'Documentos para frontend',
    'version'     => '1.1.0',
   	'license'     => 'GPL',
	'author'      => 'Paulo Fernandes',
    'website'     => 'http://www.tarambola.pt/',
    'require_tarambola_version' => '0.9.5'
));

Plugin::addController('documentos', 'Documentos');
 
Plugin::addJavascript('documentos',  "js/SimpleAjaxUploader.min.js");
Plugin::addJavascript('documentos',  "js/functions.js");