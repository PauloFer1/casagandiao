<?php
/**

/**
 * @package tarambola
 * @subpackage views
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault, 2008
 */
?>
<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('layout/add'); ?>"><img src="images/layout.png" align="middle" alt="layout icon" /> <?php echo __('New Layout'); ?></a></p>



<?php endif; ?>