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
if (Dispatcher::getAction() == 'index'):
    ?>

    <p class="button"><a href="<?php echo get_url('user/add'); ?>"><img src="images/add_user.png" align="middle" alt="user icon" /> <?php echo __('New User'); ?></a></p>


<?php endif; ?>