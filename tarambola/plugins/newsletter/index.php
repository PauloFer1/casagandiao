<?php
Plugin::setInfos(array(
    'id'          => 'newsletter',
    'title'       => __('Newsletter'),
    'description' => __('Modulo control Newsletter'),
    'version'     => '1.0',
    'website'     => 'http://www.tarambola.pt/')
);

function hello()
{
    echo 'Hello World!';
}
if (strpos($_SERVER['PHP_SELF'], ADMIN_DIR.'/index.php')) {

    AutoLoader::addFolder(dirname(__FILE__) . '/newsletters');
    AutoLoader::addFolder(dirname(__FILE__) . '/php');

    Plugin::addController('newsletter', __('Newsletter'));

function settings()
{
    $this->display('hello_world/views/settings');
}
}
?>
