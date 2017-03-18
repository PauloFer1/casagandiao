<?php
require_once SERVER_URL.'tarambola/app/shoppingcart/ModelCart.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cart
 *
 * @author paulofernandes
 */
class Cart {
    
    private $sessionId;
    private $modelCart;
    private $produtos;
    public $id;
    
    public function __construct() {
        if(!isset($_SESSION['id']))
            $_SESSION['id']=session_id();
        
        $this->sessionId = $_SESSION['id'];
        $this->modelCart = new ModelCart();
      /*  echo('</br>');
        var_dump($_SESSION['id']); 
        echo('</br>');
        var_dump(session_name());
        echo('</br>');
        var_dump($_SESSION);
        echo('</br>');
        var_dump(session_id());
        if(!isset($_COOKIE['session_id']))
        setcookie('session_id', session_id(), time()+3600);
        echo('</br>');
        var_dump($_COOKIE['session_id']);*/
    }
    public function create()
    {
        if(isset($_SESSION['isLoggin']) && $_SESSION['isLoggin'])
        {
             $this->modelCart->hasCartWithUser($this->sessionId, $_SESSION['user_id']);
             $this->id = $this->modelCart->id;
        }
        else
        {
            $this->modelCart->hasCartWithoutUser($this->sessionId);
            $this->id = $this->modelCart->id;
        }
    }
    public function regenerate()
    {
        session_regenerate_id(false);
        $_SESSION['id']=session_id();
        $this->sessionId = $_SESSION['id'];
        $this->modelCart = new ModelCart();
    }
    public function getItens()
    {
        $itens = $this->modelCart->getItens();
        return($itens);
    }
    public function getTotalValue()
    {
        return($this->modelCart->getTotalValue());
    }
    public function getTotalItens()
    {
        return($this->modelCart->getTotalItens());
    }
    public function getItemById($id)
    {
        return($this->modelCart->getItemById($id));
    }
    public function addItem($pageId, $title, $reference, $thumbPath, $qtd, $moduloId, $vatId, $valueUnity, $valueTotal, $discountUnity, $discountTotal, $attributes)
    {
        return($this->modelCart->addItem($pageId, $title, $reference, $thumbPath, $qtd, $moduloId, $vatId, $valueUnity, $valueTotal, $discountUnity, $discountTotal, $attributes));
    }
    public function removeItem($id)
    {
        return($this->modelCart->removeItem($id));
    }
    public function getItemAttributes($itemId)
    {
        return($this->modelCart->getItemAttributes($itemId));
    }
    public function getItemAttributesById($itemId, $atributeId)
    {
        return($this->modelCart->getItemAttributesById($itemId, $atributeId));
    }
    public function getItemAttributesValues($itemId)
    {
        return($this->modelCart->getItemAttributesValues($itemId));
    }
    public function getItemWithAttributes($page_id, $attributes)
    {
        return($this->modelCart->getItemWithAttributes($page_id, $attributes));
    }
    
     public function hasItemWithAttributes($page_id, $attributes)
    {
        return($this->modelCart->hasItemWithAttributes($page_id, $attributes));
    }
    public function getItemWithAttributesById($id, $attributes)
    {
        return($this->modelCart->getItemWithAttributesById($id, $attributes));
    }
    public function addQtd($id, $qtd)
    {
        return($this->modelCart->addQtd($qtd, $id));
    }
    public function updateQtd($id, $qtd)
    {
        return($this->modelCart->updateQtd($qtd, $id));
    }
    public function updateUserId($id)
    {
        return($this->modelCart->updateUserId($id));
    }
    public function closeCart()
    {
        $this->modelCart->closeCart();
        $this->regenerate();
    }
}


?>
