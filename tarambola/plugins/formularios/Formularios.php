<?php
require_once(SERVER_URL.'tarambola/app/main_plug.php');
require_once(SERVER_URL.'tarambola/app/classes/Page.php');
require_once 'Mensagem.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Formularios
 *
 * @author paulofernandes
 */
class Formularios
{
    public $formulario;
    public $formId;
    public $campos;
    private $child;
    
    public function __construct()
    {

    }

    public function getFormulario()
    {
        $formulario = Page::findById($_GET['formulario']);
        $form = Page::getFormularioById($_GET['formulario']);
        $this->formId = $form;
        $this->formulario = $formulario;
        $this->campos = $form->campos;
    }
    private function stringToArray()
    {
        $a=preg_split("/[\s]*[,][\s]*/", $this->campos);
        return($a);
    }
    private function getHref($id)
    {
        $h;
        if($this->formId->parent>0)
        {
            $h='href='.URL_PUBLIC.ADMIN_DIR.'/plugin/formularios?formulario='.$this->formulario->id().'&it='.$this->child[$id]->id();
        }
        else
        {$h='';}
        return $h;
    }
    public function addFormulario()
    {
        $idPage=$this->formulario->id;
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
        $formulariosId = Page::getFormularios();
        $formularios = array();
        $html='';
        foreach($formulariosId as $p)
            {
                $formulario = Page::findById($p->formulario_id);
                $un=Mensagem::getUnreadByParent($formulario);
                $total=Mensagem::getTotalByParent($formulario);
                $u = count($un);
                $to = count($total);
                $read=$to-$u;
               // $html.='<li><a title="Editar '.$formulario->title.'" href="'.URL_PUBLIC.ADMIN_DIR.'/page/edit/'.$formulario->id.'">'.$formulario->title.'</a></li>';
                 $html.='<li><a title="'.$formulario->title.'" href="'.URL_PUBLIC.ADMIN_DIR.'/plugin/formularios?formulario='.$formulario->id.'">'.$formulario->title.' <span class="totalNr"> ('.$to.') </span><span class="readNr">'.$read.'  </span><span class="unreadNr">'.$u.' | </span></a></li>';
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
        if($this->formId->menu==0)
        {
                $html.=
                    '<th>
                        Ver
                    </th>
                    <th>
                        Apagar
                    </th>
                    <th>
                        Estado
                     </th>
                     <th>
                        Publicado
                     </th>';
        }
         return($html);
    }
    public function printCampos()
    {
        $this->child = $this->formulario->childrenAll();
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
            if($this->formId->menu==0)
            {
            $html.='
                    <td>
                        <a href="page/edit/'.$this->child[$i]->id.'"><img alt="edit" src="'.URL_PUBLIC.ADMIN_DIR.'/images/edit.png"></a>
                    </td>
                    <td>
                         <a href="'.$p.'"  onclick="confirmDel('.$this->child[$i]->id.'); return false"><img alt="X" src="'.URL_PUBLIC.ADMIN_DIR.'/images/delete_icon.png"></a>
                    </td>
                    <td>
                        <span>
                             '.$this->getCheck($this->child[$i]).'
                        </span>
                    </td>
                    <td>
                        <span>
                             '.$this->getPublished($this->child[$i]).'
                        </span>
                    </td>';
            }
            $html.='</tr>';
        }
        return($html);
    }
    public function getCheck($p)
    {
        if($p->description=="tara_read")
           return("Lida");
        else
           return("Não Lida");
    }
    public function getPublished($p)
    {
        if($p->status_id==100)
           return("Sim");
        else
           return("Não");
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
                        <a href="page/edit/'.$paChild[$i]->id.'">-></a>
                    </td>
                    <td>
                         <a href="'.$p.'"  onclick="confirmDel('.$paChild[$i]->id.'); return false">X</a>
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
