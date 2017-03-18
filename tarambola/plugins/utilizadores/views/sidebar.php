<? $url = explode("/",($_SERVER["REQUEST_URI"])); ?>

<? if($url[sizeof($url)-1]=="utilizadores"): ?>

    <p class="button"><a href="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/plugin/utilizadores/add"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/add_new.png"/><span class="buttonText"> <? echo(__('Add New')); ?> </span></a></p>

<? else: ?>
    <p>item</p>
<? endif; ?>