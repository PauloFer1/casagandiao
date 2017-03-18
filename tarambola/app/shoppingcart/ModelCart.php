<?php

class ModelCart
{
    public $id;
    public $userId;
    public $session;
    public $qtd;
    public $value;
    
    public function __construct() {
        
    }
    public function getCartWithoutUser($session)
    {
      global $__tarambola_CONN__;

        $sql="SELECT * From tara_cart WHERE session = '".$session."'";  
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
            $this->id=$object->id;
        }
    }
    public function getCartWithUser($session)
    {
      global $__tarambola_CONN__;

        $sql="SELECT * From tara_cart WHERE session = '".$session."'";  
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
            $this->id=$object->id;
        }
    }
    public function create($userId, $session)
    {
        $this->session = $session;
        $this->userId = $userId;
        
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."cart ( session, user_id)
                VALUES ('".$session."', '".$userId."')";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
            {
                $this->id = $__tarambola_CONN__->lastInsertId ();
                $flag=1;
            }
            else
                $flag=0;

        }
        return($flag);
    }
    public function hasCart($userId, $session)
    {
        global $__tarambola_CONN__;

        $sql="SELECT COUNT(*) AS count From tara_cart WHERE user_id='".$userId."' AND session = '".$session."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        if($object->count==0)
        {
            $this->create ($userId, $session);
            return false;
        }
        else
        {
            $this->session = $session;
            $this->userId = $userId;
            return(true);
        }
    }
    public function createWithUser($session, $userId)
    {
        $this->session = $session;
        $this->userId = 0;
        
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."cart ( session, user_id, data)
                VALUES ('".$session."', '".$userId."', NOW())";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
            {
                $this->id = $__tarambola_CONN__->lastInsertId ();
                $flag=1;
            }
            else
                $flag=0;

        }
        return($flag);
    }
    public function createWithoutUser($session)
    {
        $this->session = $session;
        $this->userId = 0;
        
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."cart ( session, user_id, data)
                VALUES ('".$session."', '0', NOW())";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
            {
                $this->id = $__tarambola_CONN__->lastInsertId ();
                $flag=1;
            }
            else
                $flag=0;

        }
        return($flag);
    }
    public function hasCartWithUser($session, $userId)
    {
        global $__tarambola_CONN__;

        $sql="SELECT COUNT(*) AS count From tara_cart WHERE session = '".$session."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        if($object->count==0)
        {
            $this->createWithUser($session, $userId);
            return false;
        }
        else
        {
            $this->session = $session;
            $this->getCartWithUser($this->session);
            $this->updateUserId($userId);
            return(true);
        }
    }
    public function hasCartWithoutUser($session)
    {
        global $__tarambola_CONN__;

        $sql="SELECT COUNT(*) AS count From tara_cart WHERE session = '".$session."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        if($object->count==0)
        {
            $this->createWithoutUser($session);
            return false;
        }
        else
        {
            $this->session = $session;
            $this->getCartWithoutUser($this->session);
            return(true);
        }
    }
    //################################# ADD/INSERT ######################################//
    public function addUserToCart($userId)
    {
        global $__tarambola_CONN__;
        

        $sql="UPDATE tara_cart SET user_id='".$userId."' WHERE session='".$this->session."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=true;
           else
            $flag=false;
        }
        return($flag);
    }
    public function addItem($pageId, $title, $reference, $thumbPath, $qtd, $moduloId, $vatId, $valueUnity, $valueTotal, $discountUnity, $discountTotal, $listaAtributos)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."encomenda_produto ( page_id, qtd, value_unity, value_total, discount_unity, discount_total, modulo_id, thumb_path, title, reference, vat_id)
                VALUES ('".$pageId."', '".$qtd."', '".$valueUnity."', '".$valueTotal."', '".$discountUnity."', '".$discountTotal."', '".$moduloId."', '".$thumbPath."', '".$title."', '".$reference."', '".$vatId."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
            {
                $lastId=$__tarambola_CONN__->lastInsertId ();
                if($this->assocItemCart($lastId))
                {
                    for($i=0; $i<sizeof($listaAtributos); $i++)
                    {
                        $this->assocAtributos($listaAtributos[$i]->id, $listaAtributos[$i]->value, $lastId);
                    }
                    $flag=1;
                }
                else
                    $flag=0;
            }
            else
                $flag=0;

        }
        return($flag);
    }
    public function assocItemCart($produto_id)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO tara_cart_produto (produto_id, cart_id) VALUES ('".$produto_id."', '".$this->id."')";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
            {
                $flag=1;
            }
            else
                $flag=0;

        }
        return($flag);
    }
    public function assocAtributos($id, $value, $itemId)
    {
         global $__tarambola_CONN__;

        $sql="INSERT INTO tara_encomendas_atributos_produtos (produto_id, atributo_id, value) VALUES ('".$itemId."', '".$id."', '".$value."')";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
            {
                $flag=1;
            }
            else
                $flag=0;

        }
        return($flag);
    }
    //################################# GETS ######################################//
    public function getItens()
    {
        global $__tarambola_CONN__;

        $sql="SELECT p.id, p.page_id, p.qtd, p.value_unity, p.value_total, p.discount_unity, p.discount_total, p.modulo_id, p.thumb_path, p.title, p.reference, p.vat_id, p.qtd_description FROM tara_encomenda_produto AS p
                INNER JOIN tara_cart_produto AS cp ON cp.produto_id=p.id
                INNER JOIN tara_cart AS c ON c.id = cp.cart_id
                WHERE c.session = '".$this->session."' AND c.is_encomenda=0";

        $itens = array();
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $itens[] = $object;
            }
        }
        return($itens);
    }
    public function getItemById($id)
    {
        global $__tarambola_CONN__;

        $sql="SELECT p.id, p.page_id, p.qtd, p.value_unity, p.value_total, p.discount_unity, p.discount_total, p.modulo_id, p.thumb_path, p.title, p.reference, p.vat_id, p.qtd_description FROM tara_encomenda_produto AS p
                INNER JOIN tara_cart_produto AS cp ON cp.produto_id=p.id
                INNER JOIN tara_cart AS c ON c.id = cp.cart_id
                WHERE c.session = '".$this->session."' AND c.is_encomenda=0 AND p.id='".$id."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }
    public function getTotalValue()
    {
       global $__tarambola_CONN__;

        $sql="SELECT SUM(value_total*qtd) AS sum FROM tara_encomenda_produto AS p
                INNER JOIN tara_cart_produto AS cp ON cp.produto_id=p.id
                INNER JOIN tara_cart AS c ON c.id = cp.cart_id
                WHERE c.session = '".$this->session."' AND c.is_encomenda=0";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object->sum); 
    }
    public function getTotalItens()
    {
       global $__tarambola_CONN__;

        $sql="SELECT COUNT(value_total) AS count FROM tara_encomenda_produto AS p
                INNER JOIN tara_cart_produto AS cp ON cp.produto_id=p.id
                INNER JOIN tara_cart AS c ON c.id = cp.cart_id
                WHERE c.session = '".$this->session."' AND c.is_encomenda=0";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object->count); 
    }
    public function deleteCartBySession()
    {
        
    }
    public function getItemAttributes($itemId)
    {
         global $__tarambola_CONN__;

        $sql="SELECT produto_id AS item_id, atributo_id, value AS valor FROM tara_encomendas_atributos_produtos WHERE produto_id='".$itemId."'";

        $itens = array();
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $itens[] = $object;
            }
        }
        return($itens);
    }
    public function getItemAttributesById($itemId, $attributeId)
    {
         global $__tarambola_CONN__;

        $sql="SELECT a.id, a.atributo_id, a.value, v.descricao FROM tara_encomendas_atributos_produtos AS a 
                LEFT JOIN tara_encomendas_atributos_valores AS v ON v.id = a.value
                WHERE a.produto_id ='".$itemId."' AND a.atributo_id = '".$attributeId."'";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }
    public function getItemAttributesValues($itemId)
    {
         global $__tarambola_CONN__;

        $sql="SELECT value AS valor FROM tara_encomendas_atributos_produtos WHERE produto_id='".$itemId."'";

        $itens = array();
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $itens[] = $object;
            }
        }
        return($itens);
    }
    public function getItemWithAttributes($page_id, $attributes)
    {
         global $__tarambola_CONN__;

    /*    $sql="SELECT it.id, assoc.atributo_id, assoc.value FROM tara_encomenda_produto AS it
                INNER JOIN tara_encomendas_atributos_produtos AS assoc ON assoc.produto_id=it.id
                INNER JOIN tara_cart_produto AS cp ON it.id=cp.produto_id
                INNER JOIN tara_cart AS cart on cart.id = cp.cart_id
                WHERE cart.id ='".$this->id."' AND cart.is_encomenda=0 AND it.page_id='".$page_id."' AND (";
                for($i=0; $i<sizeof($attributes); $i++)
                {
                    if($i>0)
                        $sql.=" AND ";
                    
                    $sql.="assoc.atributo_id='".$attributes[$i]->id."' AND assoc.value='".$attributes[$i]->value."'";
                }
                $sql.=")";
             */
                $sql="";
         for($i=0; $i<sizeof($attributes); $i++)
            {
             if($i>0)
                $sql.=" UNION ";
             
             $sql.="SELECT it.id, assoc.atributo_id, assoc.value FROM tara_encomenda_produto AS it
                INNER JOIN tara_encomendas_atributos_produtos AS assoc ON assoc.produto_id=it.id
                INNER JOIN tara_cart_produto AS cp ON it.id=cp.produto_id
                INNER JOIN tara_cart AS cart on cart.id = cp.cart_id
                WHERE cart.id ='".$this->id."' AND cart.is_encomenda=0 AND it.page_id='".$page_id."' AND (
                 assoc.atributo_id='".$attributes[$i]->id."' AND assoc.value='".$attributes[$i]->value."')";
                    
            }       

            echo($sql);     
        $itens = array();
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $itens[] = $object;
            }
        }
        return($itens);
    }
    public function hasItemWithAttributes($page_id, $attributes)
    {
         global $__tarambola_CONN__;

    /*    $sql="SELECT it.id, assoc.atributo_id, assoc.value FROM tara_encomenda_produto AS it
                INNER JOIN tara_encomendas_atributos_produtos AS assoc ON assoc.produto_id=it.id
                INNER JOIN tara_cart_produto AS cp ON it.id=cp.produto_id
                INNER JOIN tara_cart AS cart on cart.id = cp.cart_id
                WHERE cart.id ='".$this->id."' AND cart.is_encomenda=0 AND it.page_id='".$page_id."' AND (";
                for($i=0; $i<sizeof($attributes); $i++)
                {
                    if($i>0)
                        $sql.=" AND ";
                    
                    $sql.="assoc.atributo_id='".$attributes[$i]->id."' AND assoc.value='".$attributes[$i]->value."'";
                }
                $sql.=")";
             */
                $sql="SELECT id, count(id) AS count FROM (";
         for($i=0; $i<sizeof($attributes); $i++)
            {
             if($i>0)
                $sql.=" UNION ";
             
             $sql.="SELECT it.id, assoc.atributo_id, assoc.value FROM tara_encomenda_produto AS it
                INNER JOIN tara_encomendas_atributos_produtos AS assoc ON assoc.produto_id=it.id
                INNER JOIN tara_cart_produto AS cp ON it.id=cp.produto_id
                INNER JOIN tara_cart AS cart on cart.id = cp.cart_id
                WHERE cart.id ='".$this->id."' AND cart.is_encomenda=0 AND it.page_id='".$page_id."' AND (
                 assoc.atributo_id='".$attributes[$i]->id."' AND assoc.value='".$attributes[$i]->value."')";
                    
            }       
            $sql.=") AS table1
                    WHERE id = table1.id 
                    GROUP BY id
                    ORDER BY count DESC
                    LIMIT 1";
        $itens = array();
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $itens[] = $object;
            }
        }
        return($itens);
    }
    public function getItemWithAttributesById($id, $attributes)
    {
         global $__tarambola_CONN__;

        $sql="SELECT it.id, assoc.atributo_id, assoc.value FROM tara_encomenda_produto AS it
                INNER JOIN tara_encomendas_atributos_produtos AS assoc ON assoc.produto_id=it.id
                INNER JOIN tara_cart_produto AS cart ON it.id=cart.produto_id
                WHERE cart.cart_id ='".$this->id."' AND cart.is_encomenda=0 AND it.id='".$id."' AND (";
                for($i=0; $i<sizeof($attributes); $i++)
                {
                    if($i>0)
                        $sql.=" OR ";
                    
                    $sql.="assoc.atributo_id='".$attributes[$i]->id."' AND assoc.value='".$attributes[$i]->value."'";
                }
                $sql.=")";
                
        $itens = array();
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $itens[] = $object;
            }
        }
        return($itens);
    }
    //############################### UPDATES ######################################//
    public function updateUserId($userId)
    {
        global $__tarambola_CONN__;

        $sql="UPDATE tara_cart SET user_id = ".$userId." WHERE session = '".$this->session."'";
       
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
                return(1);
        }
        return(0);
    }
    public function addQtd($qtd, $id)
    {
         global $__tarambola_CONN__;

        $sql="UPDATE tara_encomenda_produto SET qtd = qtd+".$qtd." WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
                return(1);
        }
        return(0);
    }
    public function updateQtd($qtd, $id)
    {
         global $__tarambola_CONN__;

        $sql="UPDATE tara_encomenda_produto SET qtd = ".$qtd." WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
                return(1);
            else
                return(0);
        }
        return(0);
    }
    public function closeCart()
    {
          global $__tarambola_CONN__;

        $sql="UPDATE tara_cart SET is_encomenda = '1' WHERE id = ".$this->id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

        }
        return($stmt);
    }
    //################################# REMOVE ######################################//
    public function removeItem($item)
    {
        global $__tarambola_CONN__;

        $sql="DELETE FROM tara_cart_produto WHERE produto_id = ".$item;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
           {
               if($this->removeItemAux($item))
                    $flag=true;
               else
                   $flag=false;
           }
           else
            $flag=false;
        }
        return($flag);
    }
    public function removeItemAux($item)
    {
        global $__tarambola_CONN__;

        $sql="DELETE FROM tara_encomenda_produto WHERE id = ".$item;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=true;
           else
            $flag=false;
        }
        return($flag); 
    }
    public function removeQtd($qtd, $id)
    {
         global $__tarambola_CONN__;

        $sql="SELECT qtd FROM tara_encomenda_produto WHERE id = '".$id."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           $stmt->execute();

            if($object = $stmt->fetchObject())
            {
                if($object->qtd>=$qtd)
                {
                    $this->removeQtdAux($qtd, $id);
                    return(1);
                }
            }
            else
            {
                return(0);
            }

        }
        return(0);
    }
    public function removeQtdAux($qtd, $id)
    {
         global $__tarambola_CONN__;

        $sql="UPDATE tara_encomenda_produto SET qtd = qtd-".$qtd." WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

        }
        return($stmt); 
    }

}

?>
