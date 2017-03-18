<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AtributosQtd
 *
 * @author paulofernandes
 */
require_once(SERVER_URL."tarambola/app/shoppingcart/Atributos/ValoresAtributos.php");

class AtributosQtd {
    public $qtd;
    public $listaValoresAtributos; // lista de ValoresAtributos {id, valor}
    
    public function __construct($qtd) {
        $this->listaValoresAtributos = array();
        $this->qtd=$qtd;
    }
    public function addLista($lista)
    {
        array_push($this->listaValoresAtributos, $lista);
    }
    public function toJSonString(){
        $lista='[';
        for($i=0; $i<sizeof($this->listaValoresAtributos); $i++)
        {
            if($i>0)
                $lista.=',';
            $lista.=$this->listaValoresAtributos[$i]->toJSonString();
        }
        $lista.=']';
        $str='{"qtd":'.$this->qtd.', "lista":'.$lista.'}';
        
        return($str);
    }
    public function getQtd($values)
    {
        if($this->qtd!=0)
        {
            for($i=0; $i<sizeof($values); $i++)
            {
               if(!$this->existValue($values[$i])) 
                   return(0);
            }
        }
        return($this->qtd);
    }
    public function existValue($value)
    {
        for($i=0; $i<sizeof($this->listaValoresAtributos); $i++)
        {
            if($value ==$this->listaValoresAtributos[$i]->valor)
                return(true);
        }
        return(false);
    }
    public function existsAttr($id)
    {
        if($this->qtd>0 || $this->qtd==-1)
        {
            for($i=0; $i<sizeof($this->listaValoresAtributos); $i++)
            {
                if($id==$this->listaValoresAtributos[$i]->id && $this->listaValoresAtributos[$i]->valor>0 )
                    return(true);
            }
        }
        return false;
    }
    public function getValueById($id)
    {
        if($this->qtd>0 || $this->qtd==-1)
        {
            for($i=0; $i<sizeof($this->listaValoresAtributos); $i++)
            {
                if($id==$this->listaValoresAtributos[$i]->id && $this->listaValoresAtributos[$i]->valor>0 )
                    return($this->listaValoresAtributos[$i]->valor);
            }
        }
        return -1; 
    }
    public function getByAtributeValue($id, $value)
    {
        if($this->qtd>0 || $this->qtd==-1)
        {
            for($i=0; $i<sizeof($this->listaValoresAtributos); $i++)
            {
                if($this->listaValoresAtributos[$i]->id==$id && $this->listaValoresAtributos[$i]->valor==$value)
                    return($this);
            }
        }
        return(null);
    }
}

?>
