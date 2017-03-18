<?php

/**


  /**
 * class Page

 * -- TAGS --
 * id()
 * title()
 * breadcrumb()
 * author()
 * slug()
 * url()
 *
 * link([label], [class])
 * date([format])
 *
 * hasContent(part_name, [inherit])
 * content([part_name], [inherit])
 * breadcrumbs([between])
 *
 * children([arguments :limit :offset :order])
 * find(url)

  todo:

  <r:navigation />

  Renders a list of links specified in the urls attribute according to three states:

  normal specifies the normal state for the link
  here specifies the state of the link when the url matches the current page’s URL
  selected specifies the state of the link when the current page matches is a child of the specified url
  The between tag specifies what should be inserted in between each of the links.

  Usage:
  <r:navigation urls="[Title: url | Title: url | ...]">
  <r:normal><a href="<r:url />"><r:title /></a></r:normal>
  <r:here><strong><r:title /></strong></r:here>
  <r:selected><strong><a href="<r:url />"><r:title /></a></strong></r:selected>
  <r:between> | </r:between>
  </r:navigation>

 * */
class Page {

    const STATUS_DRAFT = 1;
    const STATUS_REVIEWED = 50;
    const STATUS_PUBLISHED = 100;
    const STATUS_HIDDEN = 101;
    const LOGIN_NOT_REQUIRED = 0;
    const LOGIN_REQUIRED = 1;
    const LOGIN_INHERIT = 2;

    public $id;
    public $title = '';
    public $subTitle = '';
    public $breadcrumb;
    public $author;
    public $author_id;
    public $updator;
    public $updator_id;
    public $slug = '';
    public $keywords = '';
    public $description = '';
    public $url = '';
    public $parent = false;
    public $level = false;
    public $tags = false;
    public $pai;
    public $lang = '';
    private $langAux;
    public $needs_login;

    public function __construct($object, $parent) {
        $this->parent = $parent;

        foreach ($object as $key => $value) {
            $this->$key = $value;
        }

        if ($this->parent) {
            $this->setUrl();
        }
        if (isset($_GET['lang'])) {
            $this->lang = $_GET['lang'];
        } elseif (isset($_SESSION['tara_lang'])) {
            $this->lang = $_SESSION['tara_lang'];
        } elseif (isset($_COOKIE['tara_lang'])) {
            $this->lang = $_COOKIE['tara_lang'];
        } else {
            $this->lang = '';
        }
    }

    protected function setUrl() {
        $this->url = trim($this->parent->url . '/' . $this->slug, '/');
    }

    public function id() {
        return $this->id;
    }

    public function title($lang = true) {
        if(!$lang) 
            return $this->title;
        else if ($this->lang == '' && $lang)
            return $this->title;
        else {
            return ($this->content('titulo'));
        }
    }

    public function pTitle($lang = true) {
        if ($this->lang == '' && $lang)
            echo $this->title;
        else {
            echo( $this->content('title') );
        }
    }

    public function subTitle() {
        return $this->subTitle;
    }

    public function breadcrumb() {
        return $this->breadcrumb;
    }

    public function author() {
        return $this->author;
    }

    public function authorId() {
        return $this->author_id;
    }

    public function updator() {
        return $this->updator;
    }

    public function updatorId() {
        return $this->updator_id;
    }

    public function slug() {
        return $this->slug;
    }

    public function keywords() {
        return $this->keywords;
    }

    public function description() {
        return $this->description;
    }

    public function url() {
        $url = BASE_URL.Language::getInstance()->url() . $this->url . ($this->url != '' ? URL_SUFFIX : '');
        //return BASE_URL. $this->url . ($this->url != '' ? URL_SUFFIX : '');
        return($url);
    }

    public function getStatus() {
        switch ($this->status_id) {
            case 1:
                return("Rascunho");
                break;
            case 50:
                return("Revisto");
                break;
            case 100:
                return("Publicado");
                break;
            case 101:
                return("Oculto");
                break;

            default:
                return("Verificar situação");
                break;
        }
    }

    public function level() {
        if ($this->level === false)
            $this->level = empty($this->url) ? 0 : substr_count($this->url, '/') + 1;

        return $this->level;
    }

    public function tags() {
        if (!$this->tags)
            $this->_loadTags();

        return $this->tags;
    }

    public function link($label = null, $options = '') {
        if ($label == null)
            $label = $this->title();

        return sprintf('<a href="%s" %s>%s</a>', $this->url(), $options, $label
        );
    }

    /**
     * http://php.net/strftime
     * exemple (can be useful):
     *  '%a, %e %b %Y'      -> Wed, 20 Dec 2006 <- (default)
     *  '%A, %e %B %Y'      -> Wednesday, 20 December 2006
     *  '%B %e, %Y, %H:%M %p' -> December 20, 2006, 08:30 pm

      1d 10h 23m (17 / 01 / 2009 - 23:55)

     */
    public function date($format = '%a, %e %b %Y', $which_one = 'created') {
        if ($which_one == 'update' || $which_one == 'updated')
            return strftime($format, strtotime($this->updated_on));
        else if ($which_one == 'publish' || $which_one == 'published')
            return strftime($format, strtotime($this->published_on));
        else
            return strftime($format, strtotime($this->created_on));
    }

    public function dateBar($format = '%a, %e %b %Y', $which_one = 'created') {
        if ($which_one == 'update' || $which_one == 'updated')
            return strftime($format, strtotime($this->updated_on));
        else if ($which_one == 'publish' || $which_one == 'published')
            return strftime($format, strtotime($this->published_on));
        else
            return strftime($format, strtotime($this->created_on));
    }

    public function breadcrumbs($separator = '&gt;') {
        $url = '';
        $path = '';
        $paths = explode('/', '/' . $this->slug);
        $nb_path = count($paths);

        $out = '<div class="breadcrumb">' . "\n";

        if ($this->parent)
            $out .= $this->parent->_inversedBreadcrumbs($separator);

        return $out . '<span class="breadcrumb-current" style="color:' . $this->content('cor', false, false) . '">' . $this->breadcrumb() . '</span></div>' . "\n";
    }

    public function hasContent($part, $inherit = false) {
        if ($this->lang != '') {
            $part = $part . '-' . $this->lang;
        }
        if (isset($this->part->$part)) {
            return true;
        } else if ($inherit && $this->parent) {
            return $this->parent->hasf($part, true);
        }
    }

    public function hasContent1($part, $inherit = false) {
        if (isset($this->part->$part)) {
            return true;
        } else if ($inherit && $this->parent) {
            return $this->parent->hasf($part, true);
        }
    }

    public function content($part = 'body', $inherit = false, $lang = true) {
        if ($this->lang != '' && $lang) {
            $part = $part . '-' . $this->lang;
        }
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            ob_start();
            eval('?>' . $this->part->$part->content_html);
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        } else if ($inherit && $this->parent) {
            return $this->parent->content($part, true);
        }
    }

    public function title_en($part = 'titulo-en', $inherit = false, $lang = true) {
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            ob_start();
            eval('?>' . $this->part->$part->content_html);
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        } else if ($inherit && $this->parent) {
            return $this->parent->content($part, true, $lang);
        }
    }

    public function pTitle_en($part = 'titulo-en', $inherit = false, $lang = true) {
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            ob_start();
            eval('?>' . $this->part->$part->content_html);
            $out = ob_get_contents();
            ob_end_clean();
            echo $out;
        } else if ($inherit && $this->parent) {
            echo $this->parent->content($part, true);
        }
    }

    public function pContent($part = 'body', $inherit = false, $lang = true) {
        if ($this->lang != '' && $lang) {
            $part = $part . '-' . $this->lang;
        }
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            ob_start();
            eval('?>' . $this->part->$part->content_html);
            $out = ob_get_contents();
            ob_end_clean();
            echo($out);
        } else if ($inherit && $this->parent) {
            echo($this->parent->content($part, true, $lang));
        }
    }

    public function toImgArray($text) {
        $imgs = Array();
        $i = 0;
        if ($text != "") {
            $temp = explode("/#imgsepara##/", $text);
            foreach ($temp as $img) {

                if ($img != "") {
                    $temp3 = preg_split("/\.([^\.]+)$/", $img);
                    preg_match("/\.([^\.]+)$/", $img, $temp2);
                    if (sizeOf($temp2) > 0 && sizeOf($temp3) > 0) {
                        $imgs[$i][0] = IMG_URL . $temp3[0];
                        $imgs[$i][1] = $temp2[0];
                    }
                }
                $i++;
            }
        } else {
            $imgs[0][0] = IMG_URL . "tara_default";
            $imgs[0][1] = ".jpg";
        }
        return $imgs;
    }

    public function toFileArray($text) {
        $imgs = Array();
        $i = 0;

        $temp = explode("/#imgsepara##/", $text);
        foreach ($temp as $img) {

            if ($img != "") {
                $temp3 = preg_split("/\.([^\.]+)$/", $img);
                preg_match("/\.([^\.]+)$/", $img, $temp2);
                if (sizeOf($temp2) > 0 && sizeOf($temp3) > 0) {
                    $imgs[$i][0] = FILE_URL . $temp3[0];
                    $imgs[$i][1] = $temp2[0];
                }
            }
            $i++;
        }

        return $imgs;
    }

    public function getFirstImage($crop = "", $part = null, $lang = false) {
        $p = ($part != null) ? $part : 'imagem';
        $imgs = $this->getImagesFor($p, $lang);
        $image = $imgs[0][0] . $crop . $imgs[0][1];
        return($image);
    }

    public function getImages($lang = false) {
        if (!$lang) {
            $this->langAux = $this->lang;
            $this->lang = '';
        }
        $imgs = $this->toImgArray($this->content('imagem'));
        if (!$lang)
            $this->lang = $this->langAux;
        return $imgs;
    }

    public function getImagesFor($part, $lang = false) {
        if (!$lang) {
            $this->langAux = $this->lang;
            $this->lang = '';
        }
        $imgs = $this->toImgArray($this->content($part));
        if (!$lang)
            $this->lang = $this->langAux;
        return array_reverse($imgs);
    }

    public function getFiles($lang = false) {
        if (!$lang) {
            $this->langAux = $this->lang;
            $this->lang = '';
        }
        $imgs = $this->toFileArray($this->content('file'));
        if (!$lang)
            $this->lang = $this->langAux;
        return $imgs;
    }

    public function getFilesFor($part, $lang = false) {
        if (!$lang) {
            $this->langAux = $this->lang;
            $this->lang = '';
        }
        $imgs = $this->toFileArray($this->content($part));
        if (!$lang)
            $this->lang = $this->langAux;
        return $imgs;
    }

    public function previous() {
        if ($this->parent)
            return $this->parent->children(array(
                        'limit' => 1,
                        'where' => 'page.id < ' . $this->id,
                        'order' => 'page.created_on DESC'
            ));
    }

    public function next() {
        if ($this->parent)
            return $this->parent->children(array(
                        'limit' => 1,
                        'where' => 'page.id > ' . $this->id,
                        'order' => 'page.created_on ASC'
            ));
    }

    /*     * *********************NE************************ */

    public function getPagesImgIndex() {
        global $__tarambola_CONN__;
        $pages = array();

        //$sql = 'SELECT page_id FROM ' . TABLE_PREFIX . 'page_part as tara WHERE tara.name="imagem_index" and tara.content != ""';
        //$sql = 'SELECT DISTINCT tara.page_id, page.status_id FROM  ' . TABLE_PREFIX . 'page_part as tara,  ' . TABLE_PREFIX . 'page as page WHERE tara.name="imagem_index" and tara.content != "" and page.status_id=100';

        $sql = 'SELECT DISTINCT tara.page_id, page.status_id
FROM tara_page_part AS tara
INNER JOIN tara_page AS 
page ON page.id = tara.page_id
WHERE tara.name =  "destaque" 
AND tara.content =  "Sim"
AND page.status_id =100';

        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            while ($object = $stmt->fetchObject()) {
                $modulos[] = $object;
            }
        }
        return($modulos);
    }

    public function getPageById($id) {
        global $__tarambola_CONN__;
        $pages = array();
        $page_class = 'Page';

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'page WHERE id = ' . $id;
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();
            $object = $stmt->fetchObject();
            $page = new $page_class($object, $this);
            $page->part = get_parts($page->id);
        }
        return($page);
    }

    public static function getPage($id) {
        global $__tarambola_CONN__;
        $pages = array();
        $page_class = 'Page';

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'page WHERE id = ' . $id;
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();
            $object = $stmt->fetchObject();
            $page = new $page_class($object, null);
            $page->part = get_parts($page->id);
        }
        return($page);
    }

    public function getPageById2($id) {
        return(Page::find('')->children(array('by_id' => $id)));
    }

    /*     * ******************************************************************** */

    public function children($args = null, $value = array(), $include_hidden = false) {
        global $__tarambola_CONN__;

        $page_class = 'Page';

        // Collect attributes...
        $where = isset($args['where']) ? $args['where'] : '';
        $order = isset($args['order']) ? $args['order'] : 'page.position, page.id';
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        $limit = isset($args['limit']) ? $args['limit'] : 0;
        $cond = isset($args['condition']) ? $args['condition'] : null;
        $func = isset($args['function']) ? $args['function'] : null;
        $byId = isset($args['by_id']) ? $args['by_id'] : null;
        $var = isset($args['var']) ? ', ' . $args['var'] : null;

        // auto offset generated with the page param
        if ($offset == 0 && isset($_GET['page']))
            $offset = ((int) $_GET['page'] - 1) * $limit;

        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND " . $where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        // Prepare SQL
        if (isset($args['by_id'])) {
            $sql = 'SELECT ' . $func . ' page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id ' . $var
                    . 'FROM ' . TABLE_PREFIX . 'page AS page '
                    . $cond
                    . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
                    . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
                    . 'WHERE page.id = ' . $byId . ' AND (status_id=' . Page::STATUS_REVIEWED . ' OR status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
                    . "$where_string ORDER BY $order $limit_string";
        } else {
            if (!isset($args['condition'])) {
                $sql = 'SELECT ' . $func . ' page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id ' . $var
                        . 'FROM ' . TABLE_PREFIX . 'page AS page '
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
                        . 'WHERE parent_id = ' . $this->id . ' AND (status_id=' . Page::STATUS_REVIEWED . ' OR status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
                        . "$where_string ORDER BY $order $limit_string";
            } else {
                $sql = 'SELECT ' . $func . ' page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id ' . $var
                        . 'FROM ' . TABLE_PREFIX . 'page AS page '
                        . $cond
                        . ' LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
                        . 'WHERE parent_id = ' . $this->id . ' AND (status_id=' . Page::STATUS_REVIEWED . ' OR status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
                        . "$where_string ORDER BY $order $limit_string";
            }
        }
        $pages = array();
        // hack to be able to redefine the page class with behavior
        if (!empty($this->behavior_id)) {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($this->behavior_id);
        }

        // Run!
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute($value);

            while ($object = $stmt->fetchObject()) {
                $page = new $page_class($object, $this);

                // assignParts
                $page->part = get_parts($page->id);
                $pages[] = $page;
            }
        } else {
            $pages = NULL;
        }
        if (isset($args['by_id'])) {
            $this->pai = $pages[0]->parent_id;
        }

        if ($limit == 1)
            return isset($pages[0]) ? $pages[0] : false;

        return $pages;
    }

    public function childrenAll($args = null, $value = array(), $include_hidden = false) {
        global $__tarambola_CONN__;

        $page_class = 'Page';

        // Collect attributes...
        $where = isset($args['where']) ? $args['where'] : '';
        $order = isset($args['order']) ? $args['order'] : 'page.position, page.id';
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        $limit = isset($args['limit']) ? $args['limit'] : 0;
        $cond = isset($args['condition']) ? $args['condition'] : null;
        $byId = isset($args['by_id']) ? $args['by_id'] : null;

        // auto offset generated with the page param
        if ($offset == 0 && isset($_GET['page']))
            $offset = ((int) $_GET['page'] - 1) * $limit;

        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND " . $where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        // Prepare SQL
        if (isset($args['by_id'])) {
            $sql = 'SELECT ' . $func . ' page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id ' . $var
                    . 'FROM ' . TABLE_PREFIX . 'page AS page '
                    . $cond
                    . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
                    . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
                    . 'WHERE page.id = ' . $byId . ' AND (status_id=' . Page::STATUS_REVIEWED . ' OR status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
                    . "$where_string ORDER BY $order $limit_string";
        } else {
            if (!isset($args['condition'])) {
                $sql = 'SELECT page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id '
                        . 'FROM ' . TABLE_PREFIX . 'page AS page '
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
                        . 'WHERE parent_id = ' . $this->id . ' AND status_id<>' . Page::STATUS_HIDDEN . ''
                        . "$where_string ORDER BY $order $limit_string";
            } else {
                $sql = 'SELECT page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id '
                        . 'FROM ' . TABLE_PREFIX . 'page AS page '
                        . $cond
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
                        . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
                        . 'WHERE parent_id = ' . $this->id . ' AND (status_id=' . Page::STATUS_REVIEWED . ' OR status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
                        . "$where_string ORDER BY $order $limit_string";
            }
        }
        $pages = array();

        // hack to be able to redefine the page class with behavior
        if (!empty($this->behavior_id)) {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($this->behavior_id);
        }

        // Run!
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute($value);

            while ($object = $stmt->fetchObject()) {
                $page = new $page_class($object, $this);

                // assignParts
                $page->part = get_parts($page->id);
                $pages[] = $page;
            }
        }

        if ($limit == 1)
            return isset($pages[0]) ? $pages[0] : false;

        return $pages;
    }

    public function getGroups($part, $lang = true) {
        $groupsId = $this->getCheckValues($part);
        $groups = array();
        foreach ($groupsId as $g) {
            $temp = Page::children(array('by_id' => $g));
            array_push($groups, $temp[0]->title($lang));
        }
        return($groups);
    }

    public function getCheckValues($part) {
        $values = array();
        $str = $this->content($part, false, false);
        $str = preg_replace('/"/', '', $str);
        $str = preg_replace('/\[|\]/', '', $str);
        $values = explode(',', $str);

        return($values);
    }

    public static function getModulos() {
        global $__tarambola_CONN__;
        $modulos = array();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'modulos';
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            while ($object = $stmt->fetchObject()) {
                $modulos[] = $object;
            }
        }
        return($modulos);
    }

    public static function getModuloById($id) {
        global $__tarambola_CONN__;
        $modulos = array();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'modulos WHERE modulo_id = ' . $id;
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }

    public static function getPaginas() {
        global $__tarambola_CONN__;
        $modulos = array();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'paginas';
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            while ($object = $stmt->fetchObject()) {
                $modulos[] = $object;
            }
        }
        return($modulos);
    }

    public function getPaginaById($id) {
        global $__tarambola_CONN__;
        $modulos = array();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'paginas WHERE pagina_id = ' . $id;
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }

    public static function getFormularios() {
        global $__tarambola_CONN__;
        $modulos = array();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'formularios';
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            while ($object = $stmt->fetchObject()) {
                $modulos[] = $object;
            }
        }
        return($modulos);
    }

    public function getFormularioById($id) {
        global $__tarambola_CONN__;
        $modulos = array();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'formularios WHERE formulario_id = ' . $id;
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }

    public function childrenCount($args = null, $value = array(), $include_hidden = false) {
        global $__tarambola_CONN__;

        // Collect attributes...
        $cond = isset($args['condition']) ? $args['condition'] : null;
        $where = isset($args['where']) ? $args['where'] : '';
        $order = isset($args['order']) ? $args['order'] : 'page.position, page.id';
        $limit = isset($args['limit']) ? $args['limit'] : 0;
        $offset = 0;

        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND " . $where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        // Prepare SQL
        $sql = 'SELECT COUNT(*) AS nb_rows FROM ' . TABLE_PREFIX . 'page AS page '
                . $cond
                . ' WHERE page.parent_id = ' . $this->id . ' AND (page.status_id=' . Page::STATUS_REVIEWED . ' OR page.status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
                . "$where_string ORDER BY $order $limit_string";
        $stmt = $__tarambola_CONN__->prepare($sql);
        $stmt->execute($value);
        return (int) $stmt->fetchColumn();
    }

    public static function find($uri) {
        return find_page_by_uri($uri);
    }

    public static function findById($id) {
        return find_page_by_id($id);
    }

    public function parent($level = null) {
    if($level === null)









    
        return $this->parent;

        if ($level > $this->level)
        return false;
    else if ($this->level == $level)
        return $this;
    else
        return $this->parent($level);
}

public function includeSnippet($name) {
    global $__tarambola_CONN__;

    $sql = 'SELECT content_html FROM ' . TABLE_PREFIX . 'snippet WHERE name LIKE ?';

    $stmt = $__tarambola_CONN__->prepare($sql);
    $stmt->execute(array($name));

    if ($snippet = $stmt->fetchObject()) {
        eval('?>' . $snippet->content_html);
    }
}

public function executionTime() {
    return execution_time();
}

public function wr() {
    echo(this);
}

public function getById($id, $args = null, $value = array(), $include_hidden = false) {
    global $__tarambola_CONN__;

    $page_class = 'Page';

    // Prepare SQL
    $sql = 'SELECT page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id '
            . 'FROM ' . TABLE_PREFIX . 'page AS page '
            . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS author ON author.id = page.created_by_id '
            . 'LEFT JOIN ' . TABLE_PREFIX . 'user AS updator ON updator.id = page.updated_by_id '
            . 'WHERE page.id = ' . $id . ' AND (status_id=' . Page::STATUS_REVIEWED . ' OR status_id=' . Page::STATUS_PUBLISHED . ($include_hidden ? ' OR status_id=' . Page::STATUS_HIDDEN : '') . ') '
    ;


    // hack to be able to redefine the page class with behavior
    if (!empty($this->behavior_id)) {
        // will return Page by default (if not found!)
        $page_class = Behavior::loadPageHack($this->behavior_id);
    }

    // Run!
    if ($stmt = $__tarambola_CONN__->prepare($sql)) {
        $stmt->execute($value);

        while ($object = $stmt->fetchObject()) {
            $page = new $page_class($object, $this);

            // assignParts
            $page->part = get_parts($page->id);
        }
    }

    return $page;
}

// MONTANEA --------------------------------------------------------------

public function data($dataIn, $op = '') {
    $ano = substr($dataIn, 0, 4);
    $mes = substr($dataIn, 5, 2);
    $dia = substr($dataIn, 8, 2);

    switch ($mes) {
        case '01':
            $mesExt = "Jan";
            break;
        case '02':
            $mesExt = "Fev";
            break;
        case '03':
            $mesExt = "Mar";
            break;
        case '04':
            $mesExt = "Abr";
            break;
        case '05':
            $mesExt = "Mai";
            break;
        case '06':
            $mesExt = "Jun";
            break;
        case '07':
            $mesExt = "Jul";
            break;
        case '08':
            $mesExt = "Ago";
            break;
        case '09':
            $mesExt = "Set";
            break;
        case '10':
            $mesExt = "Out";
            break;
        case '11':
            $mesExt = "Nov";
            break;
        case '12':
            $mesExt = "Dez";
            break;
        default:
            $mesExt = "";
            break;
    }

    $dataOut = $dia . ' ' . $mesExt . ' ' . $ano;

    if ($op == "dia") {
        return $dia;
    }
    if ($op == "mes") {
        return $mesExt;
    }
    if ($op == "ano") {
        return $ano;
    } else {
        return $dataOut;
    }
}

// Private --------------------------------------------------------------

private function _inversedBreadcrumbs($separator) {
    $out = '<a href="' . $this->url() . '" title="' . $this->breadcrumb . '">' . $this->breadcrumb . '</a> <span class="breadcrumb-separator">' . $separator . '</span> ' . "\n";

    if ($this->parent)
        return $this->parent->_inversedBreadcrumbs($separator) . $out;

    return $out;
}

public function _executeLayout() {
    global $__tarambola_CONN__;

    $sql = 'SELECT content_type, content FROM ' . TABLE_PREFIX . 'layout WHERE id = ?';

    $stmt = $__tarambola_CONN__->prepare($sql);
    $stmt->execute(array($this->_getLayoutId()));

    if ($layout = $stmt->fetchObject()) {
        // if content-type not set, we set html as default
        if ($layout->content_type == '')
            $layout->content_type = 'text/html';

        // set content-type and charset of the page
        header('Content-Type: ' . $layout->content_type . '; charset=UTF-8');

        // execute the layout code
        eval('?>' . $layout->content);
    }
}

/**
 * find the layoutId of the page where the layout is set
 */
private function _getLayoutId() {
    if ($this->layout_id)
        return $this->layout_id;
    else if ($this->parent)
        return $this->parent->_getLayoutId();
    else
        exit('You need to set a layout!');
}

/**
 * Finds the "login needed" status for the page.
 *
 * @return int Integer corresponding to one of the LOGIN_* constants.
 */
public function getLoginNeeded() {
    if ($this->needs_login == Page::LOGIN_INHERIT && $this->parent)
        return $this->parent->getLoginNeeded();
    else
        return $this->needs_login;
}

private function _loadTags() {
    global $__tarambola_CONN__;
    $this->tags = array();

    $sql = "SELECT tag.id AS id, tag.name AS tag FROM " . TABLE_PREFIX . "page_tag AS page_tag, " . TABLE_PREFIX . "tag AS tag " .
            "WHERE page_tag.page_id={$this->id} AND page_tag.tag_id = tag.id";

    if (!$stmt = $__tarambola_CONN__->prepare($sql))
        return;

    $stmt->execute();

    // Run!
    while ($object = $stmt->fetchObject())
        $this->tags[$object->id] = $object->tag;
}

public function cutString($text, $maxchar, $end = '...') {
    if (strlen($text) > $maxchar) {
        $words = explode(" ", $text);
        $output = '';
        $i = 0;
        while (1) {
            $length = (strlen($output) + strlen($words[$i]));
            if ($length > $maxchar) {
                break;
            } else {
                $output = $output . " " . $words[$i];
                ++$i;
            };
        };
    } else {
        $output = $text;
    }
    return $output . $end;
}

public function pageViews($id) {
    global $__tarambola_CONN__;
    $views = 1;
    $sql = "SELECT id, views FROM pageViews WHERE id ={$id} ";
    $stmt = $__tarambola_CONN__->prepare($sql);
    $stmt->execute();
    $object = $stmt->fetchObject();

    if ($object != Null) {
        $views = $object->views;
        $views = $views + 1;
        $stmt2 = $__tarambola_CONN__->prepare("UPDATE pageViews SET views ={$views}  WHERE id = {$id}");
        $stmt2->execute();
    } else {
        $stmt3 = $__tarambola_CONN__->prepare("INSERT INTO pageViews VALUES ({$id},1)");
        $stmt3->execute();
    }
}

public function pageViewsId($id) {
    global $__tarambola_CONN__;
    $views = 1;
    $sql = "SELECT id, views FROM pageViews WHERE id ={$id} ";
    $stmt = $__tarambola_CONN__->prepare($sql);
    $stmt->execute();
    $object = $stmt->fetchObject();
    if ($object != Null) {
        $views = $object->views;
    } else {
        $views = 0;
    }

    return $views;
}

public function urlTotal($idItem, $idPaiTotal, $caminho = array()) {
    $aux = $this->getPageById2($idItem);
    $pg = $aux[0];

    if (($pg->parent_id)) {
        array_push($caminho, $pg->url);
        return $this->urlTotal($pg->parent_id, $idPaiTotal, $caminho);
    }

    array_push($caminho, $pg->url);
    $caminhoUrl = '';

    for ($i = sizeof($caminho) - 1; $i >= 1; $i--) {
        if ($i == 0)
            $caminhoUrl .= trim($caminho[$i]);
        else
            $caminhoUrl .= trim($caminho[$i] . '/');
    }

    return $caminhoUrl;
}

public function montaSelect() {
    $sql = "SELECT `idCliente`, `nome` FROM `cliente` ";
    $query = mysql_query($sql);

    if (mysql_num_rows($query) > 0) {
        while ($dados = mysql_fetch_assoc($query)) {
            $opt .= '<option value="' . $dados['idCliente'] . '">' . $dados['nome'] . '</option>';
        }
    }
    return $opt;
}

public function getPagesScroll() {
    global $__tarambola_CONN__;
    $pages = array();

    //$sql = 'SELECT page_id FROM ' . TABLE_PREFIX . 'page_part as tara WHERE tara.name="imagem_index" and tara.content != ""';
    //$sql = 'SELECT DISTINCT tara.page_id, page.status_id FROM  ' . TABLE_PREFIX . 'page_part as tara,  ' . TABLE_PREFIX . 'page as page WHERE tara.name="imagem_index" and tara.content != "" and page.status_id=100';

    $sql = 'SELECT DISTINCT tara.page_id, page.status_id
FROM tara_page_part AS tara
INNER JOIN tara_page AS 
page ON page.id = tara.page_id
WHERE parent_id =  23
AND page.status_id =100
';

    if ($stmt = $__tarambola_CONN__->prepare($sql)) {
        $stmt->execute();

        while ($object = $stmt->fetchObject()) {
            $modulos[] = $object;
        }
    }
    return($modulos);
}

}

// end Page class
