<?php
Plugin::setInfos(array(
    'id'          => 'imagens',
    'title'       => __('images'),
    'description' => __('Insertion image area'),
    'version'     => '0.1b',
    'license'     => 'AGPL',
    'author'      => 'Paulo Fernandes',
    'website'     => 'http://www.tarambola.pt/',
    'require_tarambola_version' => '0.9.3'
));

Filter::add('imagens', 'imagens/Imagens.php');


?>
