<?php
if (isset($_REQUEST['save']) && isset($_REQUEST['group_name'])) {

    $group_id = $newsletter->save_group($db, $_REQUEST['group_id'], $_REQUEST['group_name'], $_REQUEST['public']);
    if ($group_id) {
        ob_end_clean();
        header("Location: " . URL_PUBLIC . "tarabackend/plugin/newsletter?p=groups");
        exit;
    }
}
if ((int) isset($_REQUEST['delete'])) {

    $newsletter->delete_group($db, (int) $_REQUEST['delete']);
    ob_end_clean();
    header("Location: " . URL_PUBLIC . "tarabackend/plugin/newsletter?p=groups");
    exit;
}
?>

<h1 class="news"><? echo __('Groups'); ?></h1>

<p>
    <? echo __('Group subscribers to different categories');?>. <br>
    <? echo __('By sending the newsletter can choose the group');?>.<br>
</p>
<form action="<?php echo(URL_PUBLIC) ?>tarabackend/plugin/newsletter?p=groups&save=true" method="post" id="create_form">


<?php
if (isset($_REQUEST['edit_group_id'])) {
    $group_id = (int) $_REQUEST['edit_group_id'];
    $group = $newsletter->get_group($db, $group_id);
    ?>

        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">

        <h2 class="recent"><span><? echo __('Edit Group');?></span></h2>

        <div class="box">
            <table cellpadding="5">
                <tr>
                    <td>
                        <? echo __('Group Name');?>
                    </td>
                    <td>
                        <input type="text" name="group_name" value="<?php echo $group['group_name']; ?>"> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <? echo _('Public')?>
                    </td>
                    <td>
                        <input type="checkbox" name="public" value="1" <?php echo ($group['public']) ? ' checked' : ''; ?>> <? echo __('Public Subscribers can join this group');?>
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td>
                        <input type="submit" name="save" value="<? echo __('Save');?>">
                    </td>
                </tr>
            </table>
        </div>


        <div class="box">
            <? echo __('Delete Group');?>: <input type="button" name="del" value="<? echo __('Delete');?>" onclick="if(confirm('Really remove this group?')){ window.location.href='plugin/newsletter?p=groups&delete=<?php echo $group_id; ?>'; }">
        </div>

    <?php
} else {
    ?>
        <h2 class="recent"><span><? echo __('List of Groups');?></span></h2>

        <input type="hidden" name="group_id" value="new">

        <div class="box">
            <table cellpadding="5">
                <tr>
                    <td><? echo __('Group Name')?></td>
                    <td><? echo __('Public')?></td>
                    <td><? echo __('Members');?></td>
                    <td></td>
                </tr>
    <?php
    $groups = $newsletter->get_groups($db);
    foreach ($groups as $group) {
        $members = $newsletter->get_members($db, $group['group_id']);
        ?>
                    <tr>
                        <td>
                    <?php echo $group['group_name']; ?>
                        </td>
                        <td>
                    <?php echo ($group['public']) ? 'Yes' : 'No'; ?>
                        </td>
                        <td>
                            <?php echo mysql_num_rows($members); ?>
                        </td>
                        <td>
                            <a href="plugin/newsletter?p=groups&edit_group_id=<?php echo $group['group_id']; ?>"><? echo __('Edit Group');?></a>
                        </td>
                    </tr>
                        <?php } ?>

            </table>
        </div>


        <h2 class="recent"><span><? echo __('Add New Group');?></span></h2>

        <div class="box">
            <table cellpadding="5">
                <tr>
                    <td>
                        <? echo __('Group Name');?>
                    </td>
                    <td>
                        <input type="text" name="group_name" id="group_name" value="">
                    </td>
                </tr>
                <tr>
                    <td>
                       <? echo __('Public');?>
                    </td>
                    <td>
                        <input type="checkbox" name="public" id="public" value="1"> <?echo __('Public Subscribers can join this group');?>
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td>
                        <input type="submit" name="<? echo __('Save');?>" value="Adicionar">
                    </td>
                </tr>
            </table>

        </div>

<?php } ?>




</form>