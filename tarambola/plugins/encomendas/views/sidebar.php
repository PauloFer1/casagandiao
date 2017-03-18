<? $url = explode("/",($_SERVER["REQUEST_URI"])); ?>
<? if($url[sizeof($url)-2]=="encomendas"):  ?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A ENCOMENDAS</span></a>
</p>
<? elseif ($url[sizeof($url)-2]=="view_encomenda"):?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/encomendas"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A VER ENCOMENDAS</span></a>
</p>
<? elseif ($url[sizeof($url)-1]=="envios"):?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A ENCOMENDAS</span></a>
</p>
<? elseif ($url[sizeof($url)-1]=="arquivo"):?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A ENCOMENDAS</span></a>
</p>
<? endif; ?>
<? if($url[sizeof($url)-1]=="atributos"): ?>
<p class="button">
    <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/add_atributo" ><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/add_new.png"/><span class="buttonText">ADICIONAR NOVO</span></a>
</p>
<? endif; ?>
<? if($url[sizeof($url)-2]=="view_atributo"): ?>
<p class="button">
    <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/add_atributo_valor/<? echo($url[sizeof($url)-1]); ?>" ><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/add_new.png"/><span class="buttonText">ADICIONAR NOVO</span></a>
</p>
<? endif; ?>
<? if($url[sizeof($url)-1]=="portes"): ?>

<p class="button">
    <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/add_portes" ><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/add_new.png"/><span class="buttonText">ADICIONAR NOVO</span></a>
</p>
<? endif; ?>
<? if($url[sizeof($url)-2]=="view_portes"): ?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/portes"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A PORTES</span></a>
</p>
<p class="button">
    <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/add_portes_assoc/<? echo($url[sizeof($url)-1]); ?>" ><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/add_new.png"/><span class="buttonText">ADICIONAR UNIDADE MÁXIMA</span></a>
</p>
<? endif; ?>
<? if($url[sizeof($url)-2]=="add_portes_assoc"): ?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/view_portes/<? echo($id); ?>"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A PORTES <? echo($id); ?></span></a>
</p>
<? endif; ?>