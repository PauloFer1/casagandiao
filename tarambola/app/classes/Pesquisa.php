<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pesquisa
 *
 * @author paulofernandes
 */

class Pesquisa {

    private $results;
    private $key;
    private $module;
    private $nResults;
    
    public function __construct($key, $module)
    {
        $this->module=$module;
        $this->key=$key;
        $this->results= array();
        $this->nResults=0;
    }
    public function getAllSearch($key, $module, $notSearch=null)
    {
        $pa=Page::find($module);
        $pages=$pa->children();
        for($i=0; $i<sizeof($pages); $i++)
        {
            if( $notSearch==null || !($this->exists($pages[$i], $notSearch)) )
            {
                if($pages[$i]->childrenCount()>0)
                    $this->getAllSearch($key, $module."/".$pages[$i]->slug(), $notSearch);
                else
                {
                    if($i==sizeof($pages)-1)
                        {
                            $results = $pa->children(array('condition'=>'INNER JOIN tara_page_part AS part ON part.page_id=page.id AND ((page.title LIKE "%'.$key.'%") OR (part.content LIKE "%'.$key.'%"))', 'function'=>'DISTINCT'));
                            foreach($results as $res)
                            {
                               array_push($this->results, $res);
                            }
                        }
                }
            }
        }
        $this->nResults=count($this->results);
        return($this->results);
    }
    public function getAllSearchWithHtml($key, $module, $notSearch=null)
    {
        $res = $this->getAllSearch($key, $module, $notSearch);
    }
    private function exists($page, $pages)
    {
        foreach($pages as $p)
        {
            if($p==$page->id())
            {
                return(true);
            }
        }
        return(false);
    }
    public function getNumberResults()
    {
        return($this->nResults);
    }
}

?>
