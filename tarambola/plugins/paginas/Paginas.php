<?php
require_once(SERVER_URL.'tarambola/app/main_plug.php');
require_once(SERVER_URL.'tarambola/app/classes/Page.php'); 
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paginas
 *
 * @author paulofernandes
 */
class Paginas
{
    public $pagina;
    public $pagId;
    public $campos;
    public $menu;
    private $child;
    
    public function __construct()
    {

    }

    public function getPagina()
    {
        $pagina = Page::findById($_GET['pagina']);
        $pag = Page::getPaginaById($_GET['pagina']);
        $this->pagId = $pag;
        $this->pagina = $pagina;
        $this->campos = $pag->campos;
        $this->menu = $pag->menu;
    }
    private function stringToArray()
    {
        $a=preg_split("/[\s]*[,][\s]*/", $this->campos);
        return($a);
    }
    private function getHref($id)
    {
        $h;
        if($this->pagId->parent>0)
        {
            $h='href='.URL_PUBLIC.ADMIN_DIR.'/plugin/paginas?pagina='.$this->pagina->id().'&it='.$this->child[$id]->id();
        }
        else
        {$h='';}
        return $h;
    }
    public function addModulo()
    {
        $idPage=$this->pagina->id;
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
        $pagina = Page::findById($id);
        return $pagina;
    }
    //-------------------prints----------------------------------------
    public function getListPag()
    {
        $paginasId = Page::getPaginas();
        $paginas = array();
        $html='';
        foreach($paginasId as $p)
            {
                if($p->parent != 0)
                {
                    $pagina = Page::findById($p->pagina_id);
                   $html.='<li><a class="newBigBtn" title="'.$pagina->title.'" href="'.URL_PUBLIC.ADMIN_DIR.'/plugin/paginas?pagina='.$pagina->id.'">'.$pagina->title.'</a></li>';
                }
                else
                {
                    $pagina = Page::findById($p->pagina_id);
                    $html.='<li><a class="newBigBtn" title="Editar '.$pagina->title.'" href="'.URL_PUBLIC.ADMIN_DIR.'/page/edit/'.$pagina->id.'">'.$pagina->title.'</a></li>';
                }
            }
        return($html);
    }
    public function printCamposLabel()
    {
        $html='';
         $html.=
            '<th>Título</th>';
        $camp = $this->stringToArray();
        foreach($camp as $cam)
        {
            if($cam!="")
            {
                $html.=
                '<th>'.$cam.'</th>';
            }
                    
        }
        if($this->pagId->menu==0)
        {
                $html.=
                    '<th>
                        Editar
                    </th>
                    <th>
                        Apagar
                    </th>
                    <th>
                        Estado
                     </th>';
        }
         return($html);
    }
    public function printCampos()
    {
        $this->child = $this->pagina->childrenAll();
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
                            <a '.$this->getHref($i).' title="'.$this->child[$i]->content($cam).'"> '.$this->cutStr($this->child[$i]->content($cam),150).'</a>
                        </td>';
                }
            }
            if(isset($_SESSION['pagina']))
                $p=$_SESSION['pagina'];
            else
                $p="#";
            if($this->pagId->menu==0)
            {
            $html.='
                    <td>
                        <a href="page/edit/'.$this->child[$i]->id.'" class="editIcon"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/edit.png" alt="edit"/></a>
                    </td>
                    <td>
                         <a href="'.$p.'" onClick="confirmDel('.$this->child[$i]->id.')"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/delete_icon.png" alt="X"/></a>
                    </td>
                    <td>
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
            '<th>Título</th>';
    /*    $camp = $this->stringToArray();
        foreach($camp as $cam)
        {
            $html.=
            '<th>'.$cam.'</th>';

        }*/
           $html.=
                    '<th>
                        Editar
                    </th>
                    <th>
                        Apagar
                    </th>
                    <th>
                        Estado
                     </th>';
         return($html);
    }
    public function printPaCampos($pa)
    {
        $paChild = $pa->childrenAll();
        $html='';
        for($i=0; $i<sizeOf($paChild); $i++)
        {
            $html.='<tr class="listagemLinha">';
            $html.='
                    <td>
                       <a title="'.$paChild[$i]->title.'"> '.$this->cutStr($paChild[$i]->title,150).'</a>
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
                    <td>
                        <a href="page/edit/'.$paChild[$i]->id.'" class="editIcon"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/edit.png" alt="edit"/></a>
                    </td>
                    <td>
                         <a href="'.$p.'" onClick="confirmDel('.$paChild[$i]->id.')"><img src="'.URL_PUBLIC.ADMIN_DIR.'/images/delete_icon.png" alt="X"/></a>
                    </td>
                    <td>
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
