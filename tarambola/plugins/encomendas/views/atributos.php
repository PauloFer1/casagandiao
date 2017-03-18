<? foreach ($atributos AS $attr): ?>
    <a class="newBigBtn"  href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/view_atributo/<? echo($attr->id); ?>"><? echo($attr->description); ?></a>
<? endforeach; ?>
