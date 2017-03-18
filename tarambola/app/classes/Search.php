<?php
 require_once(SERVER_URL."tarambola/app/classes/Page.php");
class Search extends Page
{


     public function __construct()
    {
        
    }

     public function getSearch($key)
    {
        $prods=Page::find('produtos')->children();
        $all = array();
        for($i=0; $i<sizeOf($prods); $i++)
        {
            $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_page_part AS part ON part.page_id=page.id AND part.content_html LIKE "%'.$key.'%" OR page.title LIKE "%'.$key.'%"', 'function'=>'DISTINCT'));
           foreach($results as $res)
           {
                array_push($all, $res);
           }
        }
        return($all);
    }
     public function getSearchByModuleByTitle($key, $module)
    {
        $prods=Page::find($module)->children();
        $all = array();
        for($i=0; $i<sizeOf($prods); $i++)
        {
            $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_page_part AS part ON part.page_id=page.id AND page.title LIKE "%'.$key.'%"', 'function'=>'DISTINCT'));
           foreach($results as $res)
           {
                array_push($all, $res);
           }
        }
        return($all);
    }
    public function getSearchByModule($key, $module)
    {
        $prods=Page::find($module)->children();
        $all = array();
        for($i=0; $i<sizeOf($prods); $i++)
        {
            $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_page_part AS part ON part.page_id=page.id AND part.content_html LIKE "%'.$key.'%" OR page.title LIKE "%'.$key.'%"', 'function'=>'DISTINCT'));
           foreach($results as $res)
           {
                array_push($all, $res);
           }
        }
        return($all);
    }
    public function getSearchByModulePag($key, $module, $start, $limit)
    {
        $counter = 0;
        $prods=Page::find($module)->children();
        $all = array();
        for($i=0; $i<sizeOf($prods); $i++)
        {
            $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_page_part AS part ON part.page_id=page.id AND part.content_html LIKE "%'.$key.'%" OR page.title LIKE "%'.$key.'%"', 'function'=>'DISTINCT'));

         foreach($results as $res)
           {
               if($counter<=$limit)
               {
                   
                    if($counter>=$start)
                    {
                        array_push($all, $res);
                    }
               }
               else
               {
                   return($all);
               }
                $counter++;

           }
        }
        return($all);
    }

}

?>
