
<?php
require_once(SERVER_URL.'tarambola/app/main_plug.php');
require_once(SERVER_URL.'tarambola/app/classes/Page.php'); 
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modulos
 *
 * @author paulofernandes
 */
class Modulos
{
    public $modulo;
    public $modId;
    public $campos;
    private $child;
    
    public function __construct()
    {

    }
    public function setModulo($id)
    {
        $modulo = Page::findById($id);
        $mod = Page::getModuloById($id);
        $this->modId = $mod;
    }
    public function getModulo()
    {
        $modulo = Page::findById($_GET['modulo']);
        $mod = Page::getModuloById($_GET['modulo']);
        $this->modId = $mod;
        $this->modulo = $modulo;
        $this->campos = $mod->campos;
    }
    private function stringToArray()
    {
        $a=preg_split("/[\s]*[,][\s]*/", $this->campos);
        return($a);
    }
    private function getHref($id)
    {
        $h;
        if($this->modId->parent>0)
        {
            if($this->modId->parent>1)
            {
                if($this->modId->parent>2)
                    //$ch='&ch='.$this->modId->parent;
                    $ch='&ch='.$this->modId->parent;
                else
                    $ch='&ch=1';
            }
            else
                $ch='';
            $h='href='.URL_PUBLIC.ADMIN_DIR.'/plugin/modulos?modulo='.$this->modulo->id().'&it='.$this->child[$id]->id().$ch;
        }
        else
        {$h='';}
        return $h;
    }
    private function getPageHref($id)
    {
        $h='href='.URL_PUBLIC.ADMIN_DIR.'/plugin/modulos?modulo='.$this->modulo->id().'&it='.$id;
        
        return $h;
    }
    public function addModulo()
    {
        $idPage=$this->modulo->id;
        $newUrl = URL_PUBLIC.ADMIN_DIR."/page/add/".$idPage;
        return($newUrl);
    }
    private function cutStr($str,$len){
    if(strlen($str) > $len){
        $str = substr($str,0,$len - 2);
        if(strlen(substr(strrchr($str," "),1)) % 2)
            $str = substr($str,0,strlen($str) - 1);
        $str .= "...";
    }
    return $str;
}
    public function getPageById($id)
    {
        $modulo = Page::findById($id);
        return $modulo;
    }
    //-------------------prints----------------------------------------
    public function getListMod()
    {
        $modulosId = Page::getModulos();
        $modulos = array();
        $html='';
        foreach($modulosId as $m)
            {
                if($m->is_modulo==1)
                {
                    $modulo = Page::findById($m->modulo_id);
                    $html.='<li><a class="newBigBtn" title="'.$modulo->title.'" href="'.URL_PUBLIC.ADMIN_DIR.'/plugin/modulos?modulo='.$modulo->id.'">'.$modulo->title.'</a></li>';
                }
            }
        return($html);
    }
    public function printCamposLabel()
    {
        $html='';
         $html.=
            '<th>'. __('Title') .'</th>';
        $camp = $this->stringToArray();
        foreach($camp as $cam)
        {
            if($cam!="")
            {
                $html.=
                '<th>'.$cam.'</th>';
            }
                    
        }
        if($this->modId->menu==0)
        {
                $html.=
                    '<th class="optList">
                        '. __('Edit') .'
                    </th>
                    <th class="optList">
                        '. __('Delete') .'
                    </th>
                    <th class="optList">
                       '. __('Status') .'
                     </th>';
        }
         return($html);
    }
    public function printCampos()
    {
        $this->child = $this->modulo->childrenAll();
        $html='';
        for($i=0; $i<sizeOf($this->child); $i++)
        {
            $html.='<tr class="listagemLinha">';
            $html.='
                    <td>
                       <a '.$this->getHref($i).' title="'.$this->child[$i]->title.'"> '.$this->cutStr($this->child[$i]->title,150).'</a>
                    </td>';
            $camp = $this->stringToArray();
            foreach($camp as $cam)
            {
                if($cam!="")
                {
                    $html.='
                        <td>
                            <a '.$this->getHref($i).' title="'.strip_tags($this->child[$i]->content($cam)).'"> '.$this->cutStr(strip_tags($this->child[$i]->content($cam)),150).'</a>
                        </td>';
                }
            }
            if(isset($_SESSION['pagina']))
                $p=$_SESSION['pagina'];
            else
                $p="#";
            if($this->modId->menu==0)
            {
            $html.='
                    <td class="optList">
                        <a href="page/edit/'.$this->child[$i]->id.'" class="editIcon"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/edit.png" alt="edit"/></a>
                    </td>
                    <td class="optList">
                         <a href="'.$p.'" onclick="confirmDel('.$this->child[$i]->id.'); return false"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/delete_icon.png" alt="X"/></a>
                    </td>
                    <td class="optList">
                        <span>
                             '.$this->child[$i]->getStatus().'
                        </span>
                    </td>';
            }
            $html.='</tr>';
        }
        return($html);
    }
    public function printPaCamposLabel()
    {
        $html='';
         $html.=
            '<th>'. __('Title') .'</th>';
    /*    $camp = $this->stringToArray();
        foreach($camp as $cam)
        {
            $html.=
            '<th>'.$cam.'</th>';

        }*/
           $html.=
                    '<th class="optList">
                        '. __('Edit').'
                    </th>
                    <th class="optList">
                        '. __('Delete').'
                    </th>
                    <th class="optList">
                        '. __('Status').'
                     </th>';
         return($html);
    }
    public function printPaCampos($pa)
    {
        $paChild = $pa->childrenAll();
        $this->child= $pa->childrenAll();

        $html='';
        for($i=0; $i<sizeOf($paChild); $i++)
        {
            $html.='<tr class="listagemLinha">';
            $html.='
                    <td>
                       <a '.$this->getPageHref($paChild[$i]->id).' title="'.$paChild[$i]->title.'"> '.$this->cutStr($paChild[$i]->title,150).'</a>
                    </td>';
           /* $camp = $this->stringToArray();
            foreach($camp as $cam)
            {
                $html.='
                    <td>
                       <a '.$this->getHref($i).' title="'.$this->child[$i]->content($cam).'"> '.$this->cutStr($this->child[$i]->content($cam),150).'</a>
                    </td>';
            }*/
            if(isset($_SESSION['pagina']))
                $p=$_SESSION['pagina'];
            else
                $p="#";
            $html.='
                    <td class="optList">
                        <a href="page/edit/'.$paChild[$i]->id.'"  class="editIcon"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/edit.png" alt="edit"/></a>
                    </td>
                    <td class="optList">
                         <a href="'.$p.'" onclick="confirmDel('.$paChild[$i]->id.'); return false"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/delete_icon.png" alt="X"/></a>
                    </td>
                    <td class="optList">
                        <span>
                             '.$paChild[$i]->getStatus().'
                        </span>
                    </td>';
            $html.='</tr>';
        }
        return($html);
    }
    public function printPaCamposLevel($pa)
    {
       $paChild = $pa->childrenAll();
        $this->child= $pa->childrenAll();
        $html='';
        for($i=0; $i<sizeOf($paChild); $i++)
        {
            $html.='<tr class="listagemLinha">';
            $html.='
                    <td>
                       <a '.$this->getHref($i).' title="'.$paChild[$i]->title.'"> '.$this->cutStr($paChild[$i]->title,150).'</a>
                    </td>';
           /* $camp = $this->stringToArray();
            foreach($camp as $cam)
            {
                $html.='
                    <td>
                       <a '.$this->getHref($i).' title="'.$this->child[$i]->content($cam).'"> '.$this->cutStr($this->child[$i]->content($cam),150).'</a>
                    </td>';
            }*/
            if(isset($_SESSION['pagina']))
                $p=$_SESSION['pagina'];
            else
                $p="#";
            $html.='
                    <td class="optList">
                        <a href="page/edit/'.$paChild[$i]->id.'"  class="editIcon"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/edit.png" alt="edit"/></a>
                    </td>
                    <td class="optList">
                         <a href="'.$p.'" onClick="confirmDel('.$paChild[$i]->id.')"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/delete_icon.png" alt="X"/></a>
                    </td>
                    <td class="optList">
                        <span>
                             '.$paChild[$i]->getStatus().'
                        </span>
                    </td>';
            $html.='</tr>';
        }
        return($html);
    }

}
?>
