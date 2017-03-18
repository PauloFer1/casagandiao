<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagination
 *
 * @author paulofernandes
 */
require_once(SERVER_URL . "tarambola/app/classes/Page.php");

class Pagination {

//Class PÃ¡gina
    private $page;
//nr de itens a visulaizar por pagina
    private $limit;
//nr a comeÃ§ar a query
    private $offset;
//resultado da query
    private $results;
//nr de paginas da paginaÃ§Ã£o
    private $nPages;
//pagina actual
    private $actPage;
//array para a query
    private $arrQuery;
//nr total de resultados
    private $totalNrResults;

    public function __construct($page, $arr = array()) {
        $this->arrQuery = $arr;
        $this->page = $page;
    }

    public function init($limit) {
        $this->totalNrResults = $this->page->childrenCount($this->arrQuery);
        if ($this->totalNrResults < $limit)
            $this->limit = $this->totalNrResults;
        else
            $this->limit = $limit;

        $this->nPages = ceil($this->totalNrResults / $limit);

        $this->begin();
    }

    private function begin() {
        if (isset($_GET['pagina'])) {
            $this->actPage = $_GET['pagina'];
        } else {
            $this->actPage = 1;
        }
        $this->offset = ($this->actPage - 1) * $this->limit;
        $a1 = array('limit' => $this->limit, 'offset' => $this->offset);
        $this->results = $this->page->children($this->arrQuery + $a1);
    }

//-----------------GETS--------------------------
    public function getResults() {
        return($this->results);
    }

    public function getNextNrPage() {
        return($this->actPage + 1);
    }

    public function getPreviousNrPage() {
        return($this->actPage - 1);
    }

    public function getNrPages() {
        return($this->nPages);
    }

    public function getActPage() {
        return($this->actPage);
    }

    public function getTotalNrResults() {
        return($this->totalNrResults);
    }

//------builders-----
    public function getPagination($link) {
        $html = '';
        for ($i = 1; $i <= $this->nPages; $i++) {
            if ($i == $this->actPage) {
                $html.='<div style="float:left;width:100%;"><div style="float:right"><li class=paginationLiCont>
                      <span id="actPage" class="paginationNumberSelected">' . $this->actPage . '</span>
                </li>
                <li class=paginationLiCont>
                      <span id="actPage" class="paginationNumberSelected">de</span>
                </li>
                <li class=paginationLiCont>
                      <span id="actPage" class="paginationNumberSelected">' . $this->nPages . '</span>
                </li>
                </div></div>';
            }
        }
        return($html);
    }

    public function getNextBtn($text = '', $link = '', $id = '') {

        if (isset($_GET['cat'])) {
            if ($this->actPage != $this->nPages && $this->nPages > 1) {
                $html = '<a href="?cat=' . $_GET['cat'] . '&pagina=' . $this->getNextNrPage() . $link . '" title="' . $text . '" id="' . $id . '" class="paginationRightArrow">' . $text . ' </a>';
            } else {
                $html = '<a title="' . $text . '" id="' . $id . '" class="paginationRightArrow">' . $text . ' </a>';
            }
            return($html);
        } else {
            if ($this->actPage != $this->nPages && $this->nPages > 1) {
                $html = '<a href="?pagina=' . $this->getNextNrPage() . $link . '" title="' . $text . '" id="' . $id . '" class="paginationRightArrow">' . $text . ' </a>';
            } else {
                $html = '<a id="' . $id . '" class="paginationRightArrow">' . $text . ' </a>';
            }return($html);
        }
    }

    public function getPreviousBtn($text = '', $link = '', $id = '') {

        if (isset($_GET['cat'])) {
            if ($this->actPage > 1) {
                $html = '<a href="?cat=' . $_GET['cat'] . '&pagina=' . $this->getPreviousNrPage() . $link . '" title="' . $text . '" id="' . $id . '" class="paginationLeftArrow">' . $text . '</a>';
            } else {
                $html = '<a title="' . $text . '" id="' . $id . '" class="paginationLeftArrow">' . $text . '</a>';
            }
            return($html);
        } else {
            if ($this->actPage > 1) {
                $html = '<a href="?pagina=' . $this->getPreviousNrPage() . $link . '" title="' . $text . '" id="' . $id . '" class="paginationLeftArrow">' . $text . '</a>';
            } else {
                $html = '<a  title="' . $text . '" id="' . $id . '" class="paginationLeftArrow">' . $text . '</a>';
            }
            return($html);
        }
    }

    public function getNextLink($text = '', $link = '', $id = '') {
        if ($this->actPage != $this->nPages && $this->nPages > 1) {
            if (isset($_GET['cat'])) {
                $link = '?cat=' . $_GET['cat'] . '&pagina=' . $this->getNextNrPage() . $link;
                return($link);
            } else {
                $link = '?cat=' . $_GET['cat'] . '&pagina=' . $this->getNextNrPage() . $link;
                return($link);
            }
        }
    }

    public function getPreviousLink($link = '') {
        if ($this->actPage > 1) {
            if (isset($_GET['cat'])) {
                $link = '?cat=' . $_GET['cat'] . '&pagina=' . $this->getPreviousNrPage() . $link;
                return($link);
            } else {
                $link = '?cat=' . $_GET['cat'] . '&pagina=' . $this->getPreviousNrPage() . $link;
                return($link);
            }
        }
    }

    public function hasPagination() {
        if ($this->nPages > 1)
            return true;
        else
            return false;
    }

}
?>