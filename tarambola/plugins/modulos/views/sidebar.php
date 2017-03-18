<? $url = explode("/",($_SERVER["REQUEST_URI"]));?>
<? if(isset($_GET['modulo'])):  ?>
<p class="button buttonLeft">
    <a  href=" <? echo(URL_PUBLIC); ?>tarabackend/plugin/modulos"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A MODULOS</span></a>
</p>
<? endif; ?>
<?php 
require_once(SERVER_URL.'tarambola/plugins/modulos/Modulos.php');

if(isset($_GET['modulo']))
{
    if(!isset($_GET['it']))
    {
        function addMod()
        {
            $mod = new Modulos();
            $mod->getModulo();
            $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add/".$mod->modulo->id;
            if($mod->modId->menu==0)
            {
                return('   <p class="button"><a href="'.$newUrl.'"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/add_new.png"/><span class="buttonText">'.  __('Add New').'</span></a></p>');
            }
        }
            echo(addMod());
    }
    else
    {
        function addMod()
        {
            $mod = new Modulos();
            $page = $mod->getPageById($_GET['it']);
            $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add/".$page->id;
            return('   <p class="button"><a href="'.$newUrl.'"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/add_new.png"/><span class="buttonText">'.  __('Add New').'</span></a></p>');
         
        }
        
            echo(addMod());
        
    }
}

?>