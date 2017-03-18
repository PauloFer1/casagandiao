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

if (Dispatcher::getAction() != 'view'): ?>

<p class="button"><a href="#" onclick="toggle_popup('create-file-popup', 'create_file_name'); return false;"><img src="../tarambola/plugins/file_manager/images/page.png" align="middle" alt="page icon" /> <?php echo __('Create new file'); ?></a></p>
<p class="button"><a href="#" onclick="toggle_popup('create-directory-popup', 'create_directory_name'); return false;"><img src="../tarambola/plugins/file_manager/images/dir.png" align="middle" alt="dir icon" /> <?php echo __('Create new directory'); ?></a></p>
<p class="button"><a href="#" onclick="toggle_popup('upload-file-popup', 'upload_file'); return false;"><img src="../tarambola/plugins/file_manager/images/upload.png" align="middle" alt="upload icon" /><?php echo __('Upload file'); ?></a></p>

<?php endif; ?>