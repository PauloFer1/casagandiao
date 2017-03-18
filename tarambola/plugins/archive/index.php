<?php

/**


/**
 * The Archive plugin provides an Archive pagetype behaving similar to a blog or news archive.
 *
 * @package tarambola
 * @subpackage plugin.archive
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 1.0
 * @since tarambola version 0.9.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault, 2008
 */

Plugin::setInfos(array(
    'id'          => 'archive',
    'title'       => 'Archive', 
    'description' => 'Provides an Archive pagetype behaving similar to a blog or news archive.',
    'version'     => '1.0.0',
    'website'     => 'http://www.tarambola.pt/',
));

Behavior::add('archive', 'archive/archive.php');
Behavior::add('archive_day_index', 'archive/archive.php');
Behavior::add('archive_month_index', 'archive/archive.php');
Behavior::add('archive_year_index', 'archive/archive.php');