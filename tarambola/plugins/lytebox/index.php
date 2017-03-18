<?php
//D'apr�s tarambolaR plugin : http://tarambola.bebliuc.ro
Plugin::setInfos(array(
    'id'          => 'lytebox',
    'title'       => 'Lytebox', 
    'description' => 'LyteBox galerie de photographies ou de liens. Markus F. Hay', 
    'version'     => '1.0', 
    'website'     => 'http://office-web.net')
);

function lytebox() {
echo "<script type=\"text/javascript\" src=\"".URL_PUBLIC."tarambola/plugins/lytebox/lytebox.js\"></script>";
echo "<link rel=\"stylesheet\" href=\"".URL_PUBLIC."tarambola/plugins/lytebox/lytebox.css\" type=\"text/css\" media=\"screen\" />";
}

