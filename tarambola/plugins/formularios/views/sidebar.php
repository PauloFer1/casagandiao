<?php
require_once(SERVER_URL.'tarambola/plugins/formularios/Mensagem.php');

$msg = new Mensagem();
$un = $msg->getUnread();

?>

<h3 class="AvisoForm"><?php echo (count($un));?> </h3>
<ul id="listaMsgNovas">

<?php foreach($un as $u): ?>

    <li>
        <a title="<?php echo($u->title); ?>" href="page/edit/<?php echo($u->id); ?>">  <?php echo($u->title." - (".$u->parent->title.")"); ?></a>
    </li>
    
<?php endforeach; ?>

</ul>