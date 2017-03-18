<?php
require_once(SERVER_URL.'tarambola/plugins/formularios/Mensagem.php');

$msg = new Mensagem();
$un = $msg->getUnread();

?>
<div id="formAvisos">
    <h2 class="avisoTit"><?php echo __('')?></h2>
    <h3 id="mens" class="AvisoForm"><?php echo (count($un));?></h3>
    <ul id="listaMsgNovas">

    <?php if($un): ?>
    <?php foreach($un as $u): ?>

        <li>
            <a title="<?php echo($u->title); ?>" href="page/edit/<?php echo($u->id); ?>"> <?php echo($u->title." - (".$u->parent->title.")"); ?> </a>
        </li>

    <?php endforeach; ?>
    <?php endif; ?>
    </ul>
</div>
<div id="funcionalidades">
    <a onclick="openLogs()" class=""><? echo __('View Logs');?></a>
</div>
