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
require_once(SERVER_URL . 'tarambola/app/classes/Modulos.php');
$modulosInst = new Modulos();
$forms = $modulosInst->getForms();
$modulos = $modulosInst->getModulos();
$select = "";
$nivel=0;
?>
<ul<?php if ($level == 1) echo ' id="site-map"'; ?>>
    <?php foreach ($childrens as $child): ?>
        <?php if ((!(AuthUser::hasPermission('administrator,developer')) && !($child->status_id == Page::STATUS_HIDDEN) ) || (AuthUser::hasPermission('administrator,developer'))) : ?>
            <li id="page_<?php echo $child->id; ?>" class="node level-<?php echo $level;
        if (!$child->has_children) echo ' no-children'; else if ($child->is_expanded) echo ' children-visible'; else echo ' children-hidden'; ?>">
                <div class="page">
                    <span class="w1">
                        <?php if ($child->has_children): ?><img align="middle" alt="toggle children" class="expander" src="images/<?php echo $child->is_expanded ? 'collapse' : 'expand'; ?>.png" title="" /><?php endif; ?>
                        <?php if (!AuthUser::hasPermission('administrator') && !AuthUser::hasPermission('developer') && $child->is_protected): ?>
                            <img align="middle" class="icon" src="images/page.png" alt="page icon" /> <span class="title protected"><?php echo $child->title; ?></span> <img class="handle_reorder" src="images/drag_to_sort.gif" alt="<?php echo __('Drag and Drop'); ?>" align="middle" /> <img class="handle_copy" src="images/drag_to_copy.gif" alt="<?php echo __('Drag to Copy'); ?>" align="middle" />
                        <?php else: ?>
                            <a <?php if (AuthUser::hasPermission('administrator,developer')): ?>href="<?php {
                    echo get_url('page/edit/' . $child->id);
                } ?>" <?php endif; ?> title="<?php echo $child->slug; ?>/"><img align="middle" class="icon" src="images/page.png" alt="page icon" /> <span class="title"><?php echo $child->title; ?></span></a> <img class="handle_reorder" src="images/drag_to_sort.gif" alt="<?php echo __('Drag and Drop'); ?>" align="middle" /> <img class="handle_copy" src="images/drag_to_copy.gif" alt="<?php echo __('Drag to Copy'); ?>" align="middle" />
        <?php endif; ?>
                <?php if (!empty($child->behavior_id)): ?> <small class="info">(<?php echo Inflector::humanize($child->behavior_id); ?>)</small><?php endif; ?> 
                        <img align="middle" alt="" class="busy" id="busy-<?php echo $child->id; ?>" src="images/spinner.gif" style="display: none;" title="" />
                    </span>
                </div>
                <div class="page_part_form_option">
                        <? $opt=""; $sel='';?>
                    <div class="selectFormDiv" id="selectBox<?echo($child->id);?>" >
                        <button class="closeSelectFormBtn" onclick="closeSelectBox(<? echo($child->id); ?>)">  close </button>
                        <span>Escolher Page Form</span>
                        <select id="selectOptBox<? echo($child->id); ?>">
                            <option value="nenhum">nenhum</option>
                             <? foreach ($forms as $f): ?>
                                <? if($modulosInst->getFormByChild($child->id)==$f->page_part_form){$sel='selected="selected"'; }else{ $sel='';}?>
                                <option <?echo($sel);?> value="<? echo($f->page_part_form); ?>" ><? echo($f->page_part_form); ?></option>
                             <? endforeach; ?>
                        </select>
                        <span>Escolher nível da árvore</span>
                        <select id="levelSelector<? echo($child->id); ?>">
                            <? for($i=0; $i<$maxLevel; $i++): ?>
                            <option value='<? echo($i+1); ?>'> <? echo($i+1); ?></option>
                            <? endfor; ?>
                        </select>
                        <button class="saveSelectFormBtn" onclick="savePageForm(<? echo($child->id); ?>, '<? echo(URL_PUBLIC); ?>')">  salvar </button>
                    </div>
                        <button class="selectFormBtn"  onclick="showSelectBox(<? echo($child->id); ?>)" > <? echo($modulosInst->getFormByChild($child->id)); ?> </button>
                </div>
                <?php
                switch ($child->status_id) {
                    case Page::STATUS_DRAFT: echo '<div class="status draft-status">' . __('Draft') . '</div>';
                        break;
                    case Page::STATUS_REVIEWED: echo '<div class="status reviewed-status">' . __('Reviewed') . '</div>';
                        break;
                    case Page::STATUS_PUBLISHED: echo '<div class="status published-status">' . __('Published') . '</div>';
                        break;
                    case Page::STATUS_HIDDEN: echo '<div class="status hidden-status">' . __('Hidden') . '</div>';
                        break;
                }
                ?>
            <?php if (AuthUser::hasPermission('administrator,developer')): ?>
                    <div class="modify">
                        <a href="<?php echo get_url('page/add', $child->id); ?>"><img src="images/plus.png" align="middle" title="<?php echo __('Add child'); ?>" alt="<?php echo __('Add child'); ?>" /></a>&nbsp; 
            <?php if (!$child->is_protected || AuthUser::hasPermission('administrator') || AuthUser::hasPermission('developer')): ?>
                            <a class="remove" href="<?php echo get_url('page/delete/' . $child->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $child->title; ?>?');"><img src="images/icon-remove.gif" align="middle" alt="<?php echo __('Remove page'); ?>" /></a>
            <?php endif; ?>
                    </div>
        <?php endif; ?>
        <?php if ($child->is_expanded) echo $child->children_rows; ?>
            </li>
    <?php endif; ?>
<?php endforeach; ?>
</ul>
