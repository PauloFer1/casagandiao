<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comments
 *
 * @author paulofernandes
 */
require_once(SERVER_URL."tarambola/Framework.php");
require_once(SERVER_URL."tarambola/app/controllers/PageController.php");

class Comments 
{
    const TABLE_NAME = 'page';
    //variáveis referentes ao array da pagina (data) para inserir na BD
    public $title;
    public $slug;
    public $breadcrumb;
    public $keywords;
    public $description;
    public $parent_id;
    public $status_id;
    public $created_on;
    public $published_on;

    //nome do comentador
    public $nome;
    //mail do comentador
    public $mail;
    //mensagem
    public $mensagem;
    //id da pagina associada ao comentário
    public $idPageRel;

    //array da pagina a inserir
    private $data;
    //array do page part da página a inserir
    private $page_part;
    //id do comentario
    private $id;

    public function __construct()
    {
        $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        Record::connection($__tarambola_CONN__);
        Record::getConnection()->exec("set names 'utf8'");
      
        $this->keywords = "tara_formulario_alt";
        $this->description = "tara_unread";
    }

    public function insertComment()
    {
        $this->created_on = date('Y-m-d H:i:s');
        $count=0;
        $this->constructObject();
        if(Record::insert('page', $this->data))
            $count++;
        
        $this->id = Record::lastInsertId();
        
        $metadata=Array('page_id'=>$this->id, 'keyword'=>'page_part_forms', 'value'=>14);
        if(Record::insert('page_metadata', $metadata))
            $count++;

        if($this->insertpagePart('nome', $this->nome, $this->id))
            $count++;
        if($this->insertpagePart('email', $this->mail, $this->id))
            $count++;
        if($this->insertpagePart('mensagem', $this->mensagem, $this->id))
            $count++;
        if($this->insertpagePart('id_page_rel', $this->idPageRel, $this->id))
            $count++;

        if($count==6)
            return 1;
        else
            return 0;
    }
    private function insertpagePart($name, $content, $page_id)
    {
        $this->constructPagePart($name, $content, $page_id);
        return(Record::insert('page_part', $this->page_part));
    }
    private function constructPagePart($name, $content, $page_id)
    {
        $this->page_part=Array('name'=>$name, 'content'=>$content, 'content_html'=>$content, 'page_id'=>$page_id);
    }
    private function constructObject()
    {
        $this->data=Array('title'=>$this->title, 'slug'=>$this->slug, 'breadcrumb'=>$this->breadcrumb, 'keywords'=>$this->keywords, 'description'=>$this->description, 'parent_id'=>$this->parent_id, 'status_id'=>$this->status_id, 'created_on'=>$this->created_on);
    }
}
?>
