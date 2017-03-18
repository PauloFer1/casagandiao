<?php
 require_once(SERVER_URL."tarambola/app/classes/Page.php");

class Licitacao extends Page
{
 private  $userId;// = $_SESSION['login'];

     public function __construct()
    {

    }

    //parametro status pode ser "all" para todas "finish" para as k acabaram e "current" para apenas as k não acabaram
    public function getLicitacoesByUser($userId, $status="all")
    {
        $prods=Page::find('produtos')->children();
        $all = array();
        if($status!="finish" && $status!="checkout")
        {
            for($i=0; $i<sizeOf($prods); $i++)
            {
                $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_licitacoes AS lici ON lici.page_id=page.id INNER JOIN tara_front_users AS front_user ON front_user.id = lici.user_id ', 'function'=>'DISTINCT', 'where'=>'front_user.id = '.$userId ));
               foreach($results as $res)
               {
                    array_push($all, $res);
               }
            }
            if($status=="all")
            {
                return $all;
            }
            else
            {
                if($status == "current")
                {
                    $current = array();
                    foreach($all as $prod)
                    {
                        $dataFinal=$prod->content('data_final')." ".$prod->date('%H:%M:%S', 'published');
                       if( $this->dateCompare($dataFinal, $this->getDate()) )
                       {
                            array_push($current, $prod);
                       }
                    }
                    return($current);
                }
            }
        }
        elseif($status=="finish")
        {
             for($i=0; $i<sizeOf($prods); $i++)
            {
                $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_licitacoes AS lici ON lici.page_id=page.id INNER JOIN tara_front_users AS front_user ON front_user.id = lici.user_id ', 'function'=>'DISTINCT', 'where'=>'front_user.id = '.$userId.'  AND lici.valor=(Select MAX(valor) FROM tara_licitacoes AS li WHERE li.page_id = page.id)'));
               foreach($results as $res)
               {
                    array_push($all, $res);
               }
            }
                $finish = array();
                foreach($all as $prod)
                {
                    $dataFinal=$prod->content('data_final')." ".$prod->date('%H:%M:%S', 'published');
                   if( !$this->dateCompare($dataFinal, $this->getDate()) )
                   {
                        array_push($finish, $prod);
                   }
                }
                return($finish);
            }
            elseif($status=="checkout")
            {
                 for($i=0; $i<sizeOf($prods); $i++)
            {
                $results = $prods[$i]->children(array('condition'=>'INNER JOIN tara_licitacoes AS lici ON lici.page_id=page.id INNER JOIN tara_front_users AS front_user ON front_user.id = lici.user_id ', 'function'=>'DISTINCT', 'where'=>'front_user.id = '.$userId.'  AND lici.valor=(Select MAX(valor) FROM tara_licitacoes AS li WHERE li.page_id = page.id) AND lici.checkout=1'));
               foreach($results as $res)
               {
                    array_push($all, $res);
               }
            }
                $finish = array();
                foreach($all as $prod)
                {
                    $dataFinal=$prod->content('data_final')." ".$prod->date('%H:%M:%S', 'published');
                   if( !$this->dateCompare($dataFinal, $this->getDate()) )
                   {
                        array_push($finish, $prod);
                   }
                }
                return($finish);
            }
}


    public function deleteLicitacaoByPageId($id)
    {
        global $__tarambola_CONN__;


        $sql="DELETE FROM ".TABLE_PREFIX."licitacoes WHERE page_id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=true;
           else
            $flag=false;
        }
        return($flag);
    }

 /*      public function deleteLicitacaoByPageId($id)
    {
        global $__tarambola_CONN__;


        $sql="DELETE FROM ".TABLE_PREFIX."licitacoes WHERE page_id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=true;
           else
            $flag=false;
        }
        return($flag);
    }
*/

    public function setCheckout($licId)
    {
        global $__tarambola_CONN__;
        $sql="UPDATE ".TABLE_PREFIX."licitacoes SET checkout = 1 WHERE id = ".$licId;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
                $flag=true;
             else
                $flag=false;
        }
        echo($sql);
        return($flag);
    }


    public function getLicitacoes()
    {
        global $__tarambola_CONN__;

                $sql="SELECT lici.*, user.username AS username, prod.title AS produto
                From ".TABLE_PREFIX."licitacoes AS lici
                INNER JOIN ".TABLE_PREFIX."front_users AS user ON user.id = lici.user_id
                INNER JOIN ".TABLE_PREFIX."page AS prod ON prod.id = lici.page_id";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $licitacoes[] = $object;
            }
        }
        return($licitacoes);

    }
        public function getLastLicitacao($pageId)
    {
        global $__tarambola_CONN__;
        $sql="SELECT * From ".TABLE_PREFIX."licitacoes WHERE page_id=".$pageId." AND valor=( SELECT MAX(valor) FROM ".TABLE_PREFIX."licitacoes WHERE page_id=".$pageId.")";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }

    public function getLicitacoesByProduto($prodId)
    {
        global $__tarambola_CONN__;

                $sql="SELECT lici.*, user.username AS username, prod.title AS produto
                From ".TABLE_PREFIX."licitacoes AS lici
                INNER JOIN ".TABLE_PREFIX."front_users AS user ON user.id = lici.user_id
                INNER JOIN ".TABLE_PREFIX."page AS prod ON prod.id = lici.page_id
                WHERE lici.page_id =".$prodId;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $licitacoes[] = $object;
            }
        }
        return($licitacoes);

    }

    public function getLicitacoesCount($pageId)
    {
        global $__tarambola_CONN__;

        $sql="SELECT COUNT(id) FROM ".TABLE_PREFIX."licitacoes WHERE page_id=".$pageId;

        $result =mysql_query($sql);
        return(mysql_result($result,0));
    }


    public function getDate()
    {
        $data=date("Y-m-d G:i:s");
        return $data;
    }

    //$dateF - data final
    //$dateI - data inicial
    //retorna verdadeiro caso ainda estaja dentro do prazo de licitação
    public function dateCompare($dateF, $dateI)
    {
        if( (strtotime($dateF) - strtotime($dateI)) <= 0 )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function getFinalDate($obj)
    {
        return $obj->content('data_final')." ".$obj->date('%H:%M:%S', 'published');
    }


// se o valor inserido for maior ou igual que o valor da licitação minima insere licitação e retorna true, caso contrario retorna false
    public function licitar($valor, $pageId, $space)
    {
        $flag = false;
        $lic = $this->getLastLicitacao($pageId);
        $val;
        $prod = Page::children(array('by_id'=>$pageId));

        $dataFinal=$prod[0]->content('data_final')." ".$prod[0]->date('%H:%M:%S', 'published');

      //  echo($dataFinal>$this->getDate());

        if($lic)
        {
            $val=$lic->valor;
        }
        else
        {
            $val = 0;
        }
            if($valor>=($val+$space) && $this->dateCompare($dataFinal, $this->getDate()))
            {
                $flag = $this->insertLicitacao($valor, $pageId);
            }
            else
            {
               $flag = false;
            }
        return( $flag );
    }

    public function insertLicitacao($valor, $pageId)
    {
        global $__tarambola_CONN__;
        $this->userId =  $_SESSION['tara_login'];

        $sql="INSERT INTO ".TABLE_PREFIX."licitacoes ( user_id, page_id, data, valor)
                VALUES ('".$this->userId."', '".$pageId."', '".$this->getDate()."', '".$valor."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

        }
        return($stmt);
    }

}


?>
