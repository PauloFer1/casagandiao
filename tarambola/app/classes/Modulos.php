<?php
class Modulos
{
    public $mods = array();
    public $modulos;

     public function __construct()
    {         
         $max = $this->getMaxLevel()->max;
         
         for($i=0; $i<$max;$i++)
         {
             $modulos = $this->getModulosDBByNivel($i+1);
             $modArray = array();
             foreach($modulos as $m)
             {
                 $modArray[$m->modulo_id]=$m->page_part_form;
             }
             $this->addModulo($modArray);
         }
         $this->modulos = $modulos;
    }
    public function addModulo($array)
    {
        array_push($this->mods,$array);
    }

    public function getMods()
    {
        return($this->mods);
    }
    public function getModulos()
    {
        return($this->modulos);
    }
    public function isModulo($id)
    {
       global $__tarambola_CONN__; 
       $isModulo=0;
       $sql="SELECT COUNT(*) AS count FROM tara_modulos WHERE modulo_id='".$id."' AND is_modulo='1'";
       if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object->count); 
    }
    public function getModulosDB()
    {
        global $__tarambola_CONN__;

        $sql="SELECT * From ".TABLE_PREFIX."modulos ORDER BY nivel";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $modulos[] = $object;
            }
        }
        return($modulos);
    }
    public function getMaxLevel()
    {
       global $__tarambola_CONN__; 
       $sql="SELECT MAX(nivel) AS max FROM ".TABLE_PREFIX."modulos";
       if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }
    public function getModulosDBByNivel($nivel)
    {
        global $__tarambola_CONN__;

        $sql="SELECT * From tara_modulos WHERE nivel=".$nivel;

         if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $modulos[] = $object;
            }
        }
        return($modulos);
    }
    public function getForms()
    {
         global $__tarambola_CONN__;

        $sql="SELECT name AS page_part_form FROM ".TABLE_PREFIX."page_part_forms";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $modulos[] = $object;
            }
        }
        return($modulos);
    }
     public function insertModulo($modulo_id, $nivel, $page_form)
    {
        global $__tarambola_CONN__;
        
        $sql1="SELECT SUM(id) AS sum FROM ".TABLE_PREFIX."modulos WHERE modulo_id=".$modulo_id;
        
        if ($stmt = $__tarambola_CONN__->prepare($sql1))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        if($object->sum>0)
        {
            return($this->updateModulo($modulo_id, $nivel, $page_form));
        }
        else
        {
            $sql="INSERT INTO ".TABLE_PREFIX."modulos ( modulo_id, nivel, page_part_form)
                    VALUES ('".$modulo_id."', '".$nivel."', '".$page_form."' )";

            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {

                if($stmt->execute())
                    $flag=1;
                else
                    $flag=0;

            }
            return($flag);
        }
    }
    public function updateModulo($modulo_id, $nivel, $page_form)
    {
        global $__tarambola_CONN__;
          
        $sql="UPDATE ".TABLE_PREFIX."modulos SET nivel='".$nivel."', page_part_form='".$page_form."' WHERE modulo_id='".$modulo_id."' AND nivel='".$nivel."'";
        
          if ($stmt = $__tarambola_CONN__->prepare($sql))
            {

                if($stmt->execute())
                    $flag=1;
                else
                    $flag=0;

            }
            return($flag);
    }
    function hasFormModule($module_id, $formName)
        {
            foreach($this->modulos as $m)
            {
                if($m->page_part_form==$formName)
                {
                    return(true);
                }
            }
            return(false);
        }
        function getFormByChild($id)
        {
            foreach($this->modulos as $m)
            {
                if($id==$m->modulo_id)
                {
                    return($m->page_part_form);
                }
            }
            return("nenhum");
        }
}
?>

