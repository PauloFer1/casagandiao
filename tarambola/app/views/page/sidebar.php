<?php

require_once(SERVER_URL.'tarambola/plugins/modulos/Modulos.php');

if(isset($_GET['page']))
{
    if(!isset($_GET['it']))
    {
        function addPag()
        {
            $pag = new Paginas();
            $pag->getPagina();
            $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add/".$pag->pagina->id;
            return($newUrl);
        }
        $p = new Paginas();
        $p->getPagina();
        if($p->menu==0)
            echo'   <p class="button"><a href="'.addPag().'"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/add_new.png"/>'. __('Add New').'</a></p>';
    }
    else
    {
        function addPag()
        {
            $pag = new Paginas();
            $page = $pag->getPageById($_GET['it']);
            $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add/".$page->id;
            return($newUrl);
        }
        echo'   <p class="button"><a href="'.addPag().'"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/add_new.png"/>'. __('Add New').'</a></p>';
    }
}

?>