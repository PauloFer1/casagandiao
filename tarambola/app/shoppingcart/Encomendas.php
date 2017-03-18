<?php

class Encomendas
{

    public function __construct() {
        
    }
    
    //*********************** DB QUERYS *********************//
    public static function create($cartId, $nome, $morada, $cod_postal, $cidade, $pais, $email, $telefone, $nif)
    {
        $result=0;
        global $__tarambola_CONN__;
        $sql="SELECT id, COUNT(id) AS count FROM tara_encomendas WHERE cart_id='".$cartId."'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
            if($object->count>0)
                $result = Encomendas::updateInvoiceAddress($object->id, $nome, $morada, $cod_postal, $cidade, $pais, $email, $telefone, $nif);
            else
                $result = Encomendas::insertInvoiceAddress($cartId, $nome, $morada, $cod_postal, $cidade, $pais, $email, $telefone, $nif);
        }
        return($result);
    }
    //************************* GETS ************************//
    public static function getEncomendaByCart($cartId)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT enc.* FROM tara_encomendas AS enc
                WHERE enc.cart_id='".$cartId."' AND enc.estado='0'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getEncomendas()
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT enc.* FROM tara_encomendas AS enc
              INNER JOIN tara_cart AS cart ON cart.id = enc.cart_id
              WHERE enc.estado=1 ORDER BY date DESC";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $enc[] = $object;
            }
        }
        return($enc);
    }
     public static function getAtributos()
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT * FROM tara_encomendas_atributos";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $enc[] = $object;
            }
        }
        return($enc);
    }
      public static function getAtributosValoresById($id)
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT *  FROM tara_encomendas_atributos_valores
              WHERE atributos_id='".$id."'";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $enc[] = $object;
            }
        }
        return($enc);
    }
    public static function getValorById($id)
    {
        global $__tarambola_CONN__;
        $sql="SELECT *  FROM tara_encomendas_atributos_valores
              WHERE id='".$id."'";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           $stmt->execute();

           $object = $stmt->fetchObject();
        }
        return($object);
    }
      public static function getAtributoById($id)
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT *  FROM tara_encomendas_atributos
              WHERE id='".$id."'";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           $stmt->execute();

           $object = $stmt->fetchObject();
        }
        return($object);
    }
     public static function getEncomendasEnvio()
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT enc.* FROM tara_encomendas AS enc
              INNER JOIN tara_cart AS cart ON cart.id = enc.cart_id
              WHERE enc.estado=2";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $enc[] = $object;
            }
        }
        return($enc);
    }
    public static function getEncomendasArquivo()
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT enc.* FROM tara_encomendas AS enc
              INNER JOIN tara_cart AS cart ON cart.id = enc.cart_id
              WHERE enc.estado=3";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $enc[] = $object;
            }
        }
        return($enc);
    }
    public static function getProdutosByEncomenda($id)
    {
        $prod = array();
        global $__tarambola_CONN__;
        $sql="SELECT p.*, vat.value AS vat_val, vat.description AS vat_desc FROM tara_encomenda_produto AS p
              INNER JOIN tara_cart_produto AS assoc ON assoc.produto_id = p.id
              INNER JOIN tara_cart AS cart on cart.id = assoc.cart_id
              INNER JOIN tara_encomendas AS e ON e.cart_id = assoc.cart_id
              INNER JOIN tara_encomendas_vat AS vat ON vat.id = p.vat_id
              WHERE cart.is_encomenda='1' AND e.id = '".$id."'";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $prod[] = $object;
            }
        }
        return($prod); 
    }
    public static function getAtributosByProduto($id)
    {
        $attr = array();
        global $__tarambola_CONN__;
        $sql="SELECT * FROM tara_encomendas_atributos AS a
              INNER JOIN tara_encomendas_atributos_produtos as assoc ON assoc.atributo_id = a.id
              INNER JOIN tara_encomendas_atributos_valores AS val ON val.id = assoc.value
              WHERE assoc.produto_id='".$id."'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $attr[] = $object;
            }
        }
        return($attr);
    }
    public static function getEncomendasByUser($id)
    {
        $enc = array();
        global $__tarambola_CONN__;
        $sql="SELECT * FROM ".TABLE_PREFIX."encomendas WHERE user_id='".$id."'";
        
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $enc[] = $object;
            }
        }
        return($enc);
    }
    public static function getEncomendaById($id)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT enc.*, est.id AS estado_id, est.descricao AS estado_desc FROM tara_encomendas AS enc
                INNER JOIN tara_encomendas_estado AS est ON est.id = enc.estado
                WHERE enc.id='".$id."'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getUserByEncomenda($id)
    {
       global $__tarambola_CONN__;
         
         $sql="SELECT users.* FROM tara_front_users AS users
            INNER JOIN tara_cart AS cart ON cart.id = users.id
            INNER JOIN tara_encomendas AS enc ON enc.cart_id = cart.id
            WHERE enc.id = '".$id."'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getEstados()
    {
         global $__tarambola_CONN__;
         
         $est = array();
         $sql="SELECT * FROM tara_encomendas_estado";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $est[] = $object;
            }
         }
         return($est);
    }
    public static function getVat()
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomendas_vat WHERE id ='1'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getPortes()
    {
        global $__tarambola_CONN__;
         
         $portes = array();
         $sql="SELECT * FROM tara_encomenda_portes";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $portes[] = $object;
            }
         }
         return($portes);
    }
    public static function getPortesByPais($pais)
    {
        global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomenda_portes WHERE pais='".$pais."'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getPortesById($id)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomenda_portes WHERE id='".$id."'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getPortesAssocById($id)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomenda_portes_valores WHERE portes_id='".$id."' ORDER BY unidade_max";
         
         $est=array();
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $est[] = $object;
            }
         }
         return($est);
    }
    public static function getPortesValorById($id)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomenda_portes_valores WHERE id='".$id."'";
         
         $est=array();
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getPortesValorByPais($valor, $pais)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT v.id, v.unidade_max, v.valor  FROM tara_encomenda_portes_valores AS v
                INNER JOIN tara_encomenda_portes AS p ON p.id = v.portes_id
                WHERE p.pais='".$pais."'
                AND unidade_max >= '".$valor."' ORDER BY unidade_max ASC LIMIT 1";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    public static function getMaxPortesAssoc()
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomenda_portes_valores  ORDER BY unidade_max DESC LIMIT 1";
         
         $est=array();
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $est[] = $object;
            }
         }
         return($est);
    }
    public static function getPaises()
    {
       global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_paises";
         
         $est=array();
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $est[] = $object;
            }
         }
         return($est); 
    }
    public static function getMetodosPagamento()
    {
       global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomendas_metodos_pagamento";
         
         $est=array();
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $est[] = $object;
            }
         }
         return($est);   
    }
    public static function getMetodoPagamentoById($id)
    {
         global $__tarambola_CONN__;
         
         $sql="SELECT * FROM tara_encomendas_metodos_pagamento WHERE id='".$id."'";
         
         if ($stmt = $__tarambola_CONN__->prepare($sql))
         {
            $stmt->execute();

            $object = $stmt->fetchObject();
         }
         return($object);
    }
    //************************** INSERT **********************//
    public static function insertInvoiceAddress($cartId, $nome, $morada, $cod_postal, $cidade, $pais, $email, $telefone, $nif)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO tara_encomendas (cart_id, invoice_address, invoice_postal, invoice_country, invoice_city, invoice_name, invoice_nif, invoice_email, invoice_phone)
                VALUES ('".$cartId."', '".$morada."', '".$cod_postal."', '".$pais."', '".$cidade."', '".$nome."', '".$nif."', '".$email."', '".$telefone."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag); 
    }
    public static function insertEncomenda($userId, $reference, $invoiceAddress, $invoicePostal, $invoiceCountry, $invoiceCity, $shipAddress, $shipPostal, $shipCountry, $shipCity, $valueShipping, $discount="", $clientDiscount="")
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."encomendas ( reference, discount, user_id, client_discount, value_shipping, invoice_adress, invoice_postal, invoice_country, invoice_city, shipping_address, shipping_postal, shipping_country, shipping_city)
                VALUES ('".$reference."', '".$discount."', '".$userId."', '".$clientDiscount."', '".$valueShipping."', '".$invoiceAddress."', '".$invoicePostal."', '".$invoiceCountry."', '".$invoiceCity."', '".$shipAddress."', '".$shipPostal."', '".$shipCountry."', '".$shipCity."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public static function insertProduct($pageId, $title, $reference, $thumbPath, $qtd, $moduloId, $vatId, $valueUnity, $valueTotal, $discountUnity, $discountTotal)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."encomenda_produto ( page_id, qtd, value_unity, value_total, discount_unity, discount_total, modulo_id, thumb_path, title, reference, vat_id)
                VALUES ('".$pageId."', '".$qtd."', '".$valueUnity."', '".$valueTotal."', '".$discountUnity."', '".$discountTotal."', '".$moduloId."', '".$thumbPath."', '".$title."', '".$reference."', '".$vatId."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public function assocProdEnc($prodId, $encId)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."encomenda_produto_assoc ( encomenda_id, produto_id)
                VALUES ('".$prodId."', '".$encId."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public static function insertAtributo($descricao)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO tara_encomendas_atributos (description)
                VALUES ('".$descricao."')";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public static function insertAtributoValor($idAttr, $descricao)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO tara_encomendas_atributos_valores (atributos_id, descricao)
                VALUES ('".$idAttr."','".$descricao."')";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public static function insertPortes($codigo, $pais, $valor, $valor_gratis)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO tara_encomenda_portes (codigo, pais, valor, valor_gratis)
                VALUES ('".$codigo."','".$pais."','".$valor."','".$valor_gratis."')";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public static function insertPortesAssoc($portesId, $max, $valor)
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO tara_encomenda_portes_valores (portes_id, unidade_max, valor)
                VALUES ('".$portesId."','".$max."','".$valor."')";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    //************************** UPDATE **********************//
    public static function updateInvoiceAddress($id, $nome, $morada, $cod_postal, $cidade, $pais, $email, $telefone, $nif)
    {
        global $__tarambola_CONN__;

        $sql = "UPDATE tara_encomendas SET invoice_address='".$morada."', invoice_postal='".$cod_postal."', invoice_country='".$pais."', invoice_city='".$cidade."', invoice_name='".$nome."', invoice_nif='".$nif."', invoice_email='".$email."', invoice_phone='".$telefone."' WHERE id = '".$id."'";
        $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateShipping($id, $nome, $morada, $cod_postal, $cidade, $pais, $email, $telefone, $nif)
    {
        global $__tarambola_CONN__;

        $sql = "UPDATE tara_encomendas SET shipping_address='".$morada."', shipping_postal='".$cod_postal."', shipping_country='".$pais."', shipping_city='".$cidade."', shipping_name='".$nome."', shipping_nif='".$nif."', shipping_email='".$email."', shipping_phone='".$telefone."' WHERE id = '".$id."'";
        $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateObs($id, $obs)
    {
        global $__tarambola_CONN__;

        $sql = "UPDATE tara_encomendas SET observacoes='".$obs."' WHERE id = '".$id."'";
        $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updatePayMethod($id, $method)
    {
        global $__tarambola_CONN__;

        $sql = "UPDATE tara_encomendas SET metodo_pagamento='".$method."' WHERE id = '".$id."'";
        
        $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateState($id, $state)
    {
       global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomendas SET estado='".$state."' WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateDate($id)
    {
       global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomendas SET date=NOW() WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    
    public static function updateQtd($atributos, $idPage, $partName="atributos")
    {
       global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_page_part SET content='".$atributos->toJSonString()."', content_html='".$atributos->toJSonString()."' WHERE page_id = '".$idPage."' AND name = '".$partName."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateIva($valor, $use)
    {
        global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomendas_vat SET value='".$valor."', tara_encomendas_vat.use='".$use."' WHERE id = '1'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
     public static function updateShippingCosts($id, $portes)
    {
        global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomendas SET shipping_costs='".$portes."' WHERE id='".$id."'";
       
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateShippingValue($id, $value)
    {
        global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomendas SET value_shipping='".$value."' WHERE id='".$id."'";
       
       
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updatePortes($id, $codigo, $pais, $valor, $valor_gratis)
    {
        global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomenda_portes SET codigo='".$codigo."',  pais='".$pais."', valor='".$valor."', valor_gratis='".$valor_gratis."' WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updatePortesAssoc($id, $unidadeMax, $valor)
    {
        global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomenda_portes_valores SET unidade_max='".$unidadeMax."', valor='".$valor."' WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
     public static function updateAtributoValor($id, $descricao)
    {
        global $__tarambola_CONN__; 
       
       $sql = "UPDATE tara_encomendas_atributos_valores SET descricao='".$descricao."' WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function deleteAtributoValor($id)
    {
        global $__tarambola_CONN__; 
       
       $sql = "DELETE FROM tara_encomendas_atributos_valores WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function deletePortesAssoc($id)
    {
        global $__tarambola_CONN__; 
       
       $sql = "DELETE FROM tara_encomenda_portes_valores WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
     public static function deletePortes($id)
    {
        global $__tarambola_CONN__; 
       
       $sql = "DELETE FROM tara_encomenda_portes WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
     public static function deleteEncomenda($id)
    {
        global $__tarambola_CONN__; 
       
       $sql = "DELETE FROM tara_encomendas WHERE id = '".$id."'";
       $flag=0;
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
}



?>
