<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagination
 *
 * @author paulofernandesMoca
 */
require_once(SERVER_URL."tarambola/app/classes/Page.php");

class PaginationArr
{
    //Class Página
    //private $page;
    //nr de itens a visulaizar por pagina
    private $limit;
    //nr a começar a query
    private $offset;
    //resultado da query
    private $results;
    //nr de paginas da paginação
    private $nPages;
    //pagina actual
    private $actPage;
    //array para a query
    private $arr;
    //nr total de resultados
    private $totalNrResults;

    public function __construct($arr)
    {
        $this->arr = $arr;
        //$this->page = $page;
    }
    public function init($limit)
    {
        //$this->totalNrResults=$this->page->childrenCount($this->arrQuery);
        $this->totalNrResults=sizeOf($this->arr);
        if($this->totalNrResults<$limit)
            $this->limit=$this->totalNrResults;
        else
            $this->limit=$limit;

        $this->nPages = ceil( $this->totalNrResults / $limit );

        $this->begin();
    }
    private function begin()
    {
        if (isset($_GET['pagina']))
        {
            $this->actPage = $_GET['pagina'];
        }
        else
        {
            $this->actPage=1;
        }
        if($this->actPage != 1 && $this->actPage != $this->nPages){
            $this->offset=($this->actPage-1)*($this->limit-1);
            $this->limit = $this->limit-1;
        }
        else{
            $this->offset=($this->actPage-1)*$this->limit;
        }
        //$a1 = array('limit'=>$this->limit , 'offset'=>$this->offset);
        //$this->results=$this->page->children( $this->arrQuery+$a1 );
        $this->results=array_slice($this->arr,$this->offset,$this->limit);
    }
    //-----------------GETS--------------------------
    public function getResults()
    {
        return($this->results);
    }
    public function getNextNrPage()
    {
        return($this->actPage+1);
    }
    public function getPreviousNrPage()
    {
        return($this->actPage-1);
    }
    public function getNrPages()
    {
        return($this->nPages);
    }
    public function getActPage()
    {
        return($this->actPage);
    }
    public function getTotalNrResults()
    {
        return($this->totalNrResults);
    }
    //------builders-----
    public function getPagination($link)
    {
        $html='<div id="paginationDiv">
                   <ul id="paginationUl">';
                   for($i=1; $i<=$this->nPages; $i++)
                   {
                       if($i==$this->actPage)
                       {
                           $html.='<li class=paginationLi>
                                        <span id="actPage">'.$i.'</span>
                                   </li>';
                       }
                       else
                       {
                           $html.='<li class=paginationLi>
                                       <a href="?pagina='.$i.$link.'" class="pagInact" title="Página '.$i.'">'.$i.'</a>
                                   </li>';
                       }
                   }
        $html.='</ul></div>';
        return($html);
    }
    public function getNextBtn($text='', $link='', $id='')
    {
        if($this->actPage!=$this->nPages && $this->nPages>1)
        {
            $html='<div id="nextBtnDiv"><a href="?pagina='.$this->getNextNrPage().$link.'" title="Página Seguinte" id="'.$id.'">'.$text.' </a></div>';
            return($html);
        }
    }
    public function getPreviousBtn($text='', $link='', $id='')
    {
        if($this->actPage>1)
        {
            $html='<div id="prevBtnDiv"><a href="?pagina='.$this->getPreviousNrPage().$link.'" title="Página Anterior" id="'.$id.'">'.$text.'</a></div>';
            return($html);
        }
    }
}
?>
