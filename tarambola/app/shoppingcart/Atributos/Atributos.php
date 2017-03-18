<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Atributos
 *
 * @author paulofernandes
 */
require_once(SERVER_URL."tarambola/app/shoppingcart/Atributos/AtributosQtd.php");

class Atributos {
    public $listaAtributosQtd; //lista de QTD por lista de valoresatributos [{qtd, [{atributo, valor}]}];
    
    public function __construct() {
        $this->listaAtributosQtd = array();
    }
    public function addLista($lista)
    {
        array_push($this->listaAtributosQtd, $lista);
    }
    
    public function decodeJSon($str)
    {
        $str = str_replace('\'', '"', $str);
        $obj = json_decode($str);
        for($i=0; $i<sizeof($obj->atributos); $i++)
        {
            $atributosQtd = new AtributosQtd($obj->atributos[$i]->qtd);
            for($k=0; $k<sizeof($obj->atributos[$i]->lista); $k++)
            {
                $valoresAtributos = new ValoresAtributos($obj->atributos[$i]->lista[$k]->atributo, $obj->atributos[$i]->lista[$k]->valor);
                $atributosQtd->addLista($valoresAtributos);
            }
            $this->addLista($atributosQtd);
        }
        
    }
    
    public function toJSonString()
    {
        $lista='[';
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            if($i>0)
                $lista.=',';
            $lista.=$this->listaAtributosQtd[$i]->toJSonString();
        }
        $lista.=']';
        $str='{"atributos":'.$lista.'}';
        
        return($str);
    }
    public function getQtdByAtributos($atributos)
    {
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            $qtd = $this->listaAtributosQtd[$i]->getQtd($atributos);
            if($qtd==-1 || $qtd>0)
                return($qtd);
        }
        return(0);
    }
    public function outStock($atributos, $qtd)
    {
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            $qtdTemp = $this->listaAtributosQtd[$i]->getQtd($atributos);
            if($qtdTemp>-1){
                if($qtdTemp>=$qtd){
                    $this->listaAtributosQtd[$i]->qtd-=$qtd;
                    return(1);
                }
            }             
        }
        return(0);
    }
    public function getAtributos()
    {
        $atributos = array();
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            if($this->listaAtributosQtd[$i]->qtd!="" && $this->listaAtributosQtd>0)
            {
                
            }
        }
    }
    public function getAtributosAux($id)
    {
        
    }
    public function existsAttribute($id)
    {
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            if($this->listaAtributosQtd[$i]->existsAttr($id))
                return(true);
        }
        return(false);
    }
    public function getAttributeValues($id)
    {
        $values = array();
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            $val = $this->listaAtributosQtd[$i]->getValueById($id);
            if($val!=-1 && !$this->exixtsInArray($values, $val))
            {
                array_push($values, $val);
            }
        }
        return($values);
    }
    public function exixtsInArray($array, $val)
    {
        for($i=0; $i<sizeof($array); $i++)
        {
            if($array[$i]==$val)
                return(true);
        }
        return(false);
    }
    // GET ALL ATTRIBUTES/QTD WITH ID AND VALUE 
    // EX: ALL RED ITENS 
    // RED IS VALUE 
    // ID IS RED ATTRIBUTE 
    public function getListByAttributeValue($id, $value)
    {
        $list = array();
        for($i=0; $i<sizeof($this->listaAtributosQtd); $i++)
        {
            if( $this->listaAtributosQtd[$i]->getByAtributeValue($id, $value)!=null )
                array_push ($list, $this->listaAtributosQtd[$i]);
        }
        return($list);
    }
    // GET ALL VALUES FROM ONE ATTRIBUTE ASSOCIATED TO A FIRST ATTRIBUTE
    // EX: ALL SIZE S WITCH ARE RED
    // RED IS VALUE1
    // ID1 IS RED ATTRIBUTE
    // ID2 IS SIZE S ATTRIBUTE
    public function getListValuesByAttributeValue($id1, $value1, $id2)
    {
        $list = array();
        $array = $this->getListByAttributeValue($id1, $value1);
        for($i=0; $i<sizeof($array); $i++)
        {
            $aux = $array[$i]->getValueById($id2);
            if(!$this->exixtsInArray($list, $aux))
                array_push ($list, $aux);
        }
        return($list);
    }
    public function getListValuesByListAttributeValue($list, $id2)
    {
        
    }
    public function saveAtributes($pageId)
    {
         global $__tarambola_CONN__;
         
         $sql = "UPDATE tara_page_part SET content='".$this->toJSonString()."', content_html='".$this->toJSonString()."' WHERE page_id='".$pageId."' AND name='atributos'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

        }
        return($stmt); 
    }
}

?>
