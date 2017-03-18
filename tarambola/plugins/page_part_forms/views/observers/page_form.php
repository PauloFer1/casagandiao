<?php 
// Javascript is used to remove the existing page_parts interface provided by the tarambola core.
$replace_with_id = $css_id_prefix . "Form";
//echo'
//                        <script type="text/javascript" language="javascript" src="'.URL_PUBLIC.'tarabackend/javascripts/jquery.dataTables2.js"></script>'
//#################### FUNCTIONS
// //função para dividir o texto (caminho das imagens), para aparecerem no fieldset
    function apply($text, $name, $idImg) {
        $result = "<ul class ='imgs'>";
        $temp = explode("/#imgsepara##/", $text);
        foreach ($temp as $img) {
            if($img!="") {
                $temp3 = preg_split("/\.([^\.]+)$/", $img);
                preg_match("/\.([^\.]+)$/", $img, $temp2);
                $result.="<li class='imgCont' style='float:left; margin-left:5px' id='".$img."'>
                        <a href='".IMG_URL.$temp3[0].$temp2[0]."'><img src='".IMG_URL.$temp3[0].".64c".$temp2[0]."' class='imgTh'/></a>
                            <input class='makeThumbBtn' type='button' onclick=\"showWindow('".$temp3[0].".500x500".$temp2[0]."', '".URL_PUBLIC."', '".SERVER_URL."')\" value='th' title='Make Thumb'>
                        <input class='deleteBtn' type='button' onclick=\"deleteImg('".$img."', '".$name."', '".$idImg."')\" value='X' title='Apagar'/>
                    </li>";
            }
        }
        $result.="</ul>";
        return $result;
    }
    function isSel($ord, $val)
    {
                           if($ord==$val)
                               return("selected='selected'");
                           else 
                               return("");
    }
    function getIcon($extension)
    {
            switch (strtolower($extension)) {
                case '.pdf':
                    $retval = 'images/pdf.png';
                    break;
                case '.mpg':
                case '.mov':
                case '.avi':
                case '.swf':
                case '.flv':
                    $retval = 'images/video.png';
                    break;
                case '.mp2':
                case '.mp3':
                case '.mpga':
                case '. wav':
                    $retval = 'images/audio.png';
                    break;
                default:
                    $retval = 'images/doc.png';
                    break;
            }
            return '../tarambola/plugins/page_part_forms/' . $retval;
      }
 
     function applyFile($text, $name, $idFile) {
        $result = "<ul class ='imgs'>";
        $temp = explode("/#imgsepara##/", $text);
        foreach ($temp as $img) {
            if($img!="") {
                $temp3 = preg_split("/\.([^\.]+)$/", $img);
                preg_match("/\.([^\.]+)$/", $img, $temp2);
 $result.="<li class='imgCont' style='float:left; margin-left:5px' id='".$img."'>
                       <img src='".getIcon($temp2[0])."' class='imgTh' title='".$img."'/>
                        <input class='deleteBtn' type='button' onclick=\"deleteFile('".$img."', '".$name."', '".$idFile."')\" value='X' title='Apagar'/>
                    </li>";
            }
        }
        $result.="</ul>";
        return $result;

    }


    //função para buscar grupos de downloads para popular combobox
    //---------funçoes para finds------
    function explode_uri($uri)
{
    return preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
}

function find_page_by_uri($uri)
{
    global $__tarambola_CONN__;

    $uri = trim($uri, '/');

    $has_behavior = false;

    // adding the home root
    $urls = array_merge(array(''), explode_uri($uri));
    $url = '';

    $page = new stdClass;
    $page->id = 0;

    $parent = false;

    foreach ($urls as $page_slug)
    {
        $url = ltrim($url . '/' . $page_slug, '/');

        if ($page = find_page_by_slug($page_slug, $parent))
        {
            // check for behavior
            if ($page->behavior_id != '')
            {
                // add a instance of the behavior with the name of the behavior
                $params = explode_uri(substr($uri, strlen($url)));
                $page->{$page->behavior_id} = Behavior::load($page->behavior_id, $page, $params);

                return $page;
            }
        }
        else
        {
            break;
        }

        $parent = $page;

    } // foreach

    return ( ! $page && $has_behavior) ? $parent: $page;
} // find_page_by_slug

function find_page_by_slug($slug, &$parent)
{
    global $__tarambola_CONN__;

    $page_class = 'Page_Plug';

    $parent_id = $parent ? $parent->id: 0;

    $sql = 'SELECT page.*, author.name AS author, updator.name AS updator '
         . 'FROM '.TABLE_PREFIX.'page AS page '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
         . 'WHERE slug = ? AND parent_id = ? AND (status_id='.Page_Plug::STATUS_REVIEWED.' OR status_id='.Page_Plug::STATUS_PUBLISHED.' OR status_id='.Page_Plug::STATUS_HIDDEN.')';

    $stmt = $__tarambola_CONN__->prepare($sql);

    $stmt->execute(array($slug, $parent_id));

    if ($page = $stmt->fetchObject())
    {
        // hook to be able to redefine the page class with behavior
        if ( ! empty($parent->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($parent->behavior_id);
        }

        // create the object page
        $page = new $page_class($page, $parent);

        // assign all is parts
        $page->part = get_parts($page->id);

        return $page;
    }
    else return false;
}

function get_parts($page_id, $cond=null)
{
    if($cond==null)
        {
            global $__tarambola_CONN__;

            $objPart = new stdClass;

            $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE page_id=?';

            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {
                $stmt->execute(array($page_id));

                while ($part = $stmt->fetchObject())
                    $objPart->{$part->name} = $part;
           }

        }
    else
        {
            global $__tarambola_CONN__;

            $objPart = new stdClass;

            $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE page_id=? AND '.$cond;

            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {
                $stmt->execute(array($page_id));

                while ($part = $stmt->fetchObject())
                    $objPart->{$part->name} = $part;
           }
        }
    return $objPart;
}


function url_match($url)
{
    $url = trim($url, '/');

    if (CURRENT_URI == $url)
        return true;

    return false;
}

function url_start_with($url)
{
    $url = trim($url, '/');

    if (CURRENT_URI == $url)
        return true;

    if (strpos(CURRENT_URI, $url) === 0)
        return true;

    return false;
}
    //-----------------------------
    include_once(CORE_ROOT."/app/classes/Page_Plug.php");
    function getGroups($module)
    {
        $groups = Page_Plug::find($module)->children();
        return $groups;
    }
//#################### @@ FUNCTIONS

?>

<script type="text/javascript">
    //<![CDATA[
    var multiple_content = new Array();
    var editor_filters = new Array();

    (function($) {
        // Document load
if($.browser.msie)
{
	document.ondragstart = function() {
	window.event.dataTransfer.effectAllowed = "copyLink";
	}
}
        $(function() {
            // Replace the existing page_part editors with the page_part_forms editors.
            $('#tab-control').replaceWith(
            $('#<?php echo $replace_with_id; ?>').remove() // jQuery copies the nodes, so delete the origin

        );

            // Apply the text filters
            $.each(editor_filters, function(index, item) {
             //   setTextAreaToolbar(item["textarea"], item["filter"]);
            });

            // Add a handler to the form submission to prepare the page_parts (e.g. multiple values and limit check)
            $form = $('form:first'); // XXX: this is fragile!

            $form.submit(function() {
                submit_form = true;
                
                // Walk through all definitions
                $.each(multiple_content, function(index, item) {
                    // Collect the selected values from checkboxes or lists that have multiple contents
                    values = new Array();

                    $('input[name="<?php echo $plugin_id; ?>_values_for_' + item["name"] + '"]:checked,'+
                        'select[name="<?php echo $plugin_id; ?>_values_for_' + item["name"] + '"] option:selected', $form).each(function() {
                        values.push( $(this).val() );
                    });

                    // Check if the limit is reached
                    if (values.length <= item["limit"]) {
                        // Encode the values as JSON string and set it as content
                        $('textarea[name="' + item["content"] + '"]', $form).val( $.json.encode(values) );
                    }
                    else {
                        // I18n in javascript by replacing placeholders
                        alert(
                        '<?php echo __('Too many options are selected for "##TITLE##". Currently there are "##SELECTED##" selected, but only "##LIMIT##" are allowed.'); ?>'
                        .replace("##TITLE##", item["title"])
                        .replace("##SELECTED##", values.length)
                        .replace("##LIMIT##", item["limit"])
                    );

                        // Do not submit the form
                        submit_form = false;
                        // This stops only the current loop, not the submission of the form
                        return false;
                    }
                    
                    //
                    // -- Methods for sequence (ordem) --
                    if(jQuery('#Page-part-forms-Page-Part-ordem').length>0)
                    {
                        var val = '[';
                        jQuery('.ordemLi').each(function(index){
                            //val+='"'+jQuery('.in1-'+jQuery(this).attr('id')).val()+'"=>"'+jQuery('.sel-'+jQuery(this).attr('id')).val()+'"';
                          //  val+='"[\'id\'=>'+jQuery('.in1-'+jQuery(this).attr('id')).val()+', \'ord\'=>'+jQuery('.sel-'+jQuery(this).attr('id')).val()+']"';
                            val+='"'+jQuery('.in1-'+jQuery(this).attr('id')).val()+'=>'+jQuery('.sel-'+jQuery(this).attr('id')).val()+'"';
                            if( index<(jQuery('.ordemLi').length-1))
                                {
                                    val+=',';
                                }
                        });
                        val+=']';
                        //alert(val);
                        jQuery('#Page-part-forms-Page-Part-ordem').val(val);
                       // alert(jQuery('#Page-part-forms-Page-Part-ordem').val());
                    }
                    // -- @Methods for sequence (ordem) --
                });

                return submit_form;
            });
        });
    })(jQuery);
    //]]>
   
</script>
<?
    $langs = array();
    for($ln=0; $ln<sizeof($language); $ln++)
    {
        array_push($langs, $language[$ln]->lang);
    }
?>
<div id="<?php echo $replace_with_id; ?>" class="tab-container">
    <ul class='tabs tabs-lang'>
        <? for($ln = 0; $ln<sizeof($langs); $ln++): ?>
        <li class='tab lang-tab'><a href="#<? echo($css_id_prefix."-".$langs[$ln]); ?>"><? echo(strtoupper($langs[$ln])); ?></a></li>
        <? endfor; ?>
    </ul>
    <div id="<? echo($css_id_prefix); ?>">
    <div id="pages" class="pages">
        <div id ="page-none" class ="page">
            
        </div>
    </div>
    <!-- ######################################################################## PAGE PART FOR ALT LANGS ################################################################################################## -->    
    <!-- begin langs -->
    <? 
        // Number all page parts
        $index = 0;
    ?>
    <? for($langIt=0; $langIt<sizeof($langs); $langIt++): ?>
    <?
        $langSuffix="";
        if($langs[$langIt]=="pt")
            $langSuffix="";
        else
            $langSuffix="-".$langs[$langIt];
           
        $page_part_id  = array_key_exists("title", $page_parts) ? $page_parts["title".$langSuffix]->id : null;
        $content = array_key_exists("title".$langSuffix, $page_parts) ? $page_parts["title".$langSuffix]->content : '';
    ?>
    <div id="<? echo($css_id_prefix."-".$langs[$langIt]); ?>">
        <? if($langs[$langIt]!="pt"): ?>
        <div class="page-part-forms-row">
            <input type="hidden" value="title<? echo($langSuffix); ?>" name="part[<? echo($index); ?>][name]" id="part_<? echo($index); ?>_name<? echo($langSuffix); ?>">
                        <input type="hidden" value="<?php echo $page_part_id; ?>" name="part[<? echo($index); ?>][id]" id="part_<? echo($index); ?>_id">
                    <label for="Page-part-forms-Page-Part-title<? echo($langSuffix); ?>" class="page-part-forms-label">Título:</label>

            <div class="page-part-forms-input">
            <input type="text" maxlength="255" value="<? echo($content); ?>" name="part[<? echo($index); ?>][content]" class="page-part-forms-part-text " id="Page-part-forms-Page-Part-title<? echo($langSuffix); ?>" label="Título:">        </div> <!-- INPUT -->
        </div>
        <? $index++; ?>
        <? endif; ?>
    <?php
   


    // This page parts have multiple values/options
    $multiple_content = array();
    // Apply the filters after the form is replaced
    $editor_filters = array();
    
    // Process all structural elements from page_part_forms defnition and create the page_part_form.
    
    foreach ($structure as $name => $element) {
        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
        {
    // Data from this page part
        $structure_name_css_id = page_part_forms_name_to_id($name).$langSuffix;
        $page_part_id          = array_key_exists($name.$langSuffix, $page_parts) ? $page_parts[$name.$langSuffix]->id : null;
        $content               = array_key_exists($name.$langSuffix, $page_parts) ? $page_parts[$name.$langSuffix]->content : '';
        // Common fields from the structure
        $limit                 = array_key_exists(PagePartFormsController::PROPERTY_LIMIT, $element) ? $element[PagePartFormsController::PROPERTY_LIMIT] : 1;
        $title                 = $element[PagePartFormsController::PROPERTY_TITLE];
        // Name of the field
        $field_name_prefix     = 'part['.$index.']';
        $field_name_content    = $field_name_prefix.'[content]';
        ?>
        <!-- #### IF PAGE PART #### -->
        <?php if($element[PagePartFormsController::PROPERTY_TYPE] ==PagePartFormsController::TYPE_PAGE_PART) : ?>
        <div class="<?php echo $css_class_prefix; ?>row_page_part">
            <input id="part_<?php echo $index; ?>_name<? echo($langSuffix); ?>" name="<?php echo $field_name_prefix; ?>[name]" type="hidden" value="<?php echo ($name.$langSuffix); ?>" />
            <?php if ($page_part_id): ?>
                <input id="part_<?php echo $index; ?>_id<? echo($langSuffix); ?>" name="<?php echo $field_name_prefix; ?>[id]" type="hidden" value="<?php echo $page_part_id; ?>" />
            <?php endif; ?>
            <label class="<?php echo $css_class_prefix;?>label" for="<?php echo $structure_name_css_id; ?>"><?php echo $title; ?></label>

            <div class="<?php echo $css_class_prefix; ?>input">
        <!-- #### ELSE #### -->
        <?php else : ?>
                <div class="<?php echo $css_class_prefix; ?>row">
            <input id="part_<?php echo $index; ?>_name<? echo($langSuffix); ?>" name="<?php echo $field_name_prefix; ?>[name]" type="hidden" value="<?php echo $name.$langSuffix; ?>" />
            <?php if ($page_part_id): ?>
                <input id="part_<?php echo $index; ?>_id" name="<?php echo $field_name_prefix; ?>[id]" type="hidden" value="<?php echo $page_part_id; ?>" />
            <?php endif; ?>
            <label class="<?php echo $css_class_prefix;?>label" for="<?php echo $structure_name_css_id; ?>"><?php echo $title; ?></label>

            <div class="<?php echo $css_class_prefix; ?>input">
        <?php endif ?>
    <?php
    switch($element[PagePartFormsController::PROPERTY_TYPE]) {
        case PagePartFormsController::TYPE_PAGE_PART:
             if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                            $filter_id = array_key_exists($name.$langSuffix, $page_parts) ? $page_parts[$name.$langSuffix]->filter_id : null;

                            echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;

                            // Take care of page filters for page parts
                            echo '<p class="pFiltroTexto">';
                            echo '<select class="filtroTexto" id="part_'.$index.'_filter_id" name="'.$field_name_prefix.'[filter_id]" onchange="setTextAreaToolbar(\''.$structure_name_css_id.'\', this[this.selectedIndex].value)">';
                            echo('<option value=""'.(empty($filter_id) ? ' selected="selected"' : '').'>&#8212; '.__('none').' &#8212;</option>');
                            foreach (Filter::findAll() as $filter) {
                              /*  if($filter=="tinymce")
                                    {echo '<option selected="selected "value="'.$filter.'"'. ($filter_id == $filter ? ' selected="selected"' : '') .'>';}
                                 else*/
                                if($filter!="imagens")
                                {
                                   // if($filter==)
                                   // echo '<option value="'.$filter.'"'. ($filter_id == $filter ? ' selected="selected"' : '') .'>';
                                   // echo Inflector::humanize($filter);
                                }
                                //echo '</option>';
                            }
                            echo('<option selected="selected" value="tinymce">Tinymce</option>');
                            echo '</select>'.PHP_EOL;
                             echo '<label class="labelTexto" for="part_'.$index.'_filter_id">'.__('Filter').'</label></p>'.PHP_EOL;
                            // Show the page part
                            echo '<textarea class="textarea" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%" rows="20" cols="40"';
                            echo ' onkeydown="return allowTab(event, this);" onkeyup="return allowTab(event,this);" onkeypress="return allowTab(event,this);">';
                            echo htmlentities($content, ENT_COMPAT, 'UTF-8');
                            echo '</textarea>';
                        //  echo('<script>alert(jQuery("#Page-part-forms-Page-Part-texto_parent").length);if(!jQuery("#Page-part-forms-Page-Part-texto_parent").length){setTextAreaToolbar(\''.$structure_name_css_id.'\', "tinymce"); alert(jQuery("#Page-part-forms-Page-Part-texto_parent").length);}</script>');
                          echo('<script>
                                  jQuery(window).load(function(){
                                    if(!jQuery("#'.$structure_name_css_id.'_parent").length)
                                    {
                                        setTextAreaToolbar(\''.$structure_name_css_id.'\', "tinymce");
                                    }
                                  });
                                </script>');

                            // Collect the filters for this editor, apply it later
                            array_push($editor_filters, array(
                                'textarea' => $structure_name_css_id,
                                'filter'   => $filter_id,
                            ));

                            echo '</div>'.PHP_EOL;
                        }
                        break;
//***************************************************** TEXT *******************************************************************
                    case PagePartFormsController::TYPE_TEXT:
                         if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                            {
                            $class="";
                            $html="";
                            if($structure[$name][PagePartFormsController::PROPERTY_VALIDATION][0]==1 )
                            {
                                $class="required";
                                $html="<span id='".$structure_name_css_id."_msg' class='requiredMsg'>Mandatory Field</span>";
                            }
                            echo($html);
                            echo '<input type="text" label="'.$title.'" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text '.$class.'" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                            }
                        break;
//***************************************************** SPACE *******************************************************************
                    case PagePartFormsController::TYPE_SPACE:
                         if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                     //   echo '<input type="text" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                         }
                        break;
//***************************************************** HIDDEN INPUT *******************************************************************
                    case PagePartFormsController::TYPE_HIDDEN:
                         if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                            {
                            echo '<input type="hidden" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                            }
                        break;
//***************************************************** READ ONLY INPUT *******************************************************************
                    case PagePartFormsController::TYPE_READ_ONLY:
                        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                            echo '<input type="text" readonly="readonly" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                        }
                        break;
                    //--------------------------draw-------------------------------------------------------------
              case PagePartFormsController::TYPE_DRAW:
                   if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                  echo('<script>jQuery.noConflict();</script>');
                  require_once SERVER_URL.'/tarambola/app/shoppingcart/Atributos/Atributos.php';
                  echo('<link href="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/css/draw.css" media="screen" rel="stylesheet" type="text/css" />');
                  echo('<script src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/jsDraw2D.js" type="text/javascript"></script>');
                        echo('<div id="drawContainer">');
                        if($page->id==122)
                            echo('<img src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/images/floor.jpg" style="position: absolute;top: 0;left: 0" />');
                        else
                            echo('<img src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/images/walls.jpg" style="position: absolute;top: 0;left: 0" />');
                            
                                echo('<div id="canvasDraw"  style="position: absolute;top: 0;left: 0 ;overflow:hidden;position:relative;width:800px;height:800px;">
                                </div>
                                <div id="canvas2" style="position: absolute;top: 0;left: 0 ;"></div></div>');
                        echo '<input type="hidden" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" />';
                        echo('<button id="drawBtn">DRAW MASK</button>');
                        echo('<button id="clearBtn">CLEAR</button>');
                        echo('<script>');
                        echo('var json = "'.$content.'"; 
                                if(json!="")
                                {
                                    json = json.replace(/\'/g, "\""); 
                                    var obj = JSON.parse(json); 
                                }
                                if(json=="" || obj==null || obj.Polygon==null)
                                    var points = new Array();
                                else
                                    var points = obj.Polygon;');
                        echo('</script>');
                        echo('<script src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/draw.js" type="text/javascript"></script>');
                        echo('<script>drawContent("'.$content.'");'
                                .'setDrawEvent("'.$structure_name_css_id.'");'
                                . '</script>');
                        }
                        break;

                    //---------------------------@draw----------------------------------------------------
                    //--------------------------attribute-------------------------------------------------------------
              case PagePartFormsController::TYPE_ATTRIBUTES:
                   if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                  require_once SERVER_URL.'/tarambola/app/shoppingcart/Encomendas.php';
                  require_once SERVER_URL.'/tarambola/app/shoppingcart/Atributos/Atributos.php';
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/attributes_functions.js"></script>');
                  $atributos = Encomendas::getAtributos();
                  echo('<div id="allAttributes">');
                  
                        $attrBoxHid='<div class="attributesBox">';
                        foreach ($atributos as $att):    
                                $attrBoxHid.='<select id="attribute_'.$att->id.'" value="'.$att->id.'" class="attributeSelect"><option value="0" selected disabled>'.$att->description.'</option>';
                                $valores = Encomendas::getAtributosValoresById($att->id);
                                foreach($valores as $val):
                                    $attrBoxHid.='<option value="'.$val->id.'">'.$val->descricao.'</option>';
                                endforeach;
                                $attrBoxHid.='</select>';
                        endforeach;
                        $attrBoxHid.='<input class="quantidadeAtt" type="number" value="0"/>
                                <span class="qtdLabel">Qtd</span>
                                <a id="removeAttr_'.$att->id.'" href="" class="removeAttrBtn"> X </a>
                            </div>';
                        
                  if($content==""){
                        echo($attrBoxHid);
                  }
                  else
                  {
                      $at = new Atributos();
                      $o = json_decode($content);
                      //var_dump($o->atributos[1]);
                      $at->decodeJSon($content);
                      //var_dump($at);
                      $attrBox='';
                      for($i=0; $i<sizeof($at->listaAtributosQtd); $i++)
                      {
                          $attrBox.='<div class="attributesBox">';
                          $atribut;
                          for($k=0; $k<sizeof($at->listaAtributosQtd[$i]->listaValoresAtributos); $k++)
                          {
                            $atribut = Encomendas::getAtributoById($at->listaAtributosQtd[$i]->listaValoresAtributos[$k]->id);
                            $valores = Encomendas::getAtributosValoresById($at->listaAtributosQtd[$i]->listaValoresAtributos[$k]->id);
                            $attrBox.='<select id="attribute_'.$at->listaAtributosQtd[$i]->listaValoresAtributos[$k]->id .'" value="'.$at->listaAtributosQtd[$i]->listaValoresAtributos[$k]->id.'" class="attributeSelect">';
                            $attrBox.='<option value="0" selected disabled>'.$atribut->description.'</option>';
                            for($j=0; $j<sizeof($valores); $j++)
                            {
                                $sel = ($valores[$j]->id==$at->listaAtributosQtd[$i]->listaValoresAtributos[$k]->valor)?'selected="selected"':'';
                               $attrBox.='<option value="'.$valores[$j]->id.'" '.$sel.' >'.$valores[$j]->descricao.'</option>'; 
                            }
                            $attrBox.='</select>';
                          }
                          $attrBox.='<input class="quantidadeAtt" type="number" value="'.$at->listaAtributosQtd[$i]->qtd.'"/>
                                <span class="qtdLabel">Qtd</span>
                                <a id="removeAttr_" href="" class="removeAttrBtn"> X </a>';
                          $attrBox.='</div>';
                      }
                      echo($attrBox);
                  }
                            echo'</div>
                                <a href="" class="addNewBtn" >+</a>';
                        echo('<div id="hiddenDivAttr" style="display: none;">'.$attrBoxHid.'</div>');
                        echo '<input type="hidden" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.str_replace('"', '\'', $content).'" />';
                        }
                        break;

     //---------------------------@attributes----------------------------------------------------
                        
     //--------------------------colors-------------------------------------------------------------
              case PagePartFormsController::TYPE_COLORS:
                   if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                  require_once SERVER_URL.'/tarambola/app/shoppingcart/Atributos/Cores.php';
                  echo('<link href="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/css/colpick.css" media="screen" rel="Stylesheet" type="text/css" />');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/jquery-1.11.0.js"></script>');
                  echo('<script>jQuery.noConflict();</script>');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/colpick.js"></script>');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/cores_functions.js"></script>');
                  echo('<div id="allColors">');
                  
                        $colorBoxHid='<div class="colorBox">';
                        $colorBoxHid.='<input type="text" class="pickerColor"/>';
                        $colorBoxHid.='<a id="removeColor_" href="" class="removeColorBtn"> X </a>';
                        $colorBoxHid.='</div>';
                        
                  if($content==""){
                        echo($colorBoxHid);
                  } 
                 else
                  {
                      $cor = new Cores();
                      $o = json_decode($content);
                      //var_dump($o->atributos[1]);
                      $cor->decodeJSon($content);
                      //var_dump($at);
                      $colorBox='';
                      for($i=0; $i<sizeof($cor->lista); $i++)
                      {
                          $colorBox.='<div class="colorBox">';
                          $colorBox.='<input type="text" class="pickerColor" value="'.$cor->getCodeN($i).'" style="border-color: #'.$cor->getCodeN($i).'"></input>';
                          $colorBox.=' <a id="removeColor_" href="" class="removeColorBtn"> X </a>';
                          $colorBox.='</div>';
                      }
                      echo($colorBox);
                  }
                        echo'</div>
                                <a href="" class="addNewColorBtn" >+</a>';
                        echo('<div id="hiddenDivColor" style="display: none;">'.$colorBoxHid.'</div>');
                        echo '<input type="hidden" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" />';
                        }
                        break;

     //---------------------------@colors----------------------------------------------------
                        
  //-------------------------------data-----------------------------------------------------
             /*           case PagePartFormsController::TYPE_TEXT:
                        echo '<input type="text" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                        break;*/
  //-------------------------@data------------------------------------------------------------------

  //--------------------------imagem-------------------------------------------------------------
              case PagePartFormsController::TYPE_IMAGE:
                   if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                  require_once 'javascript.php';
                  echo('
                   <style type="text/css" title="currentStyle">
                                    @import "'.URL_PUBLIC.'tarambola/plugins/page_part_forms/css/jquery.window.css";
                           </style>
                           <style type="text/css" title="currentStyle">
                                    @import "'.URL_PUBLIC.'tarambola/plugins/page_part_forms/css/jquery.window.css";
                           </style>
                           <style type="text/css" title="currentStyle">
                                    @import "'.URL_PUBLIC.'tarambola/plugins/page_part_forms/css/cropper.css";
                           </style>');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/jquery-1.11.0.js"></script>');
                   echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/jquery-ui-1.10.4.custom.js"></script>');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/cropper.js"></script>');
                  //echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/croppic.js"></script>');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/jquery.window.js"></script>');
                  echo('<script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'tarambola/plugins/page_part_forms/js/image_thumb.js"></script>');

                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;
                        $maxsize = $structure[$name][PagePartFormsController::PROPERTY_MAXSIZE];

                        echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;

                        // Take care of page filters for page parts
                        echo '<p style="display:none">';
                        echo '<label for="part_'.$index.'_filter_id">'.__('Filter').'</label>'.PHP_EOL;
                        echo '<select id="part_'.$index.'_filter_id" name="'.$field_name_prefix.'[filter_id]" onchange="setTextAreaToolbar(\''.$structure_name_css_id.'\', this[this.selectedIndex].value)">
           <option value=""'.(empty($filter_id) ? ' selected="selected"' : '').'>&#8212; '.__('none').' &#8212;</option>';
                        foreach (Filter::findAll() as $filter) {
                            if($filter == "imagens") {
                                echo '<option value="'.$filter.'"'. ("imagens" == $filter ? ' selected="selected"' : '') .'>';
                                echo Inflector::humanize($filter);
                                echo '</option>';
                            }
                        }

                        echo '</select></p>'.PHP_EOL;
                        echo'<div id="imgDiv"><fieldset id="imgListContainer">';
                        echo apply(htmlentities($content, ENT_COMPAT, 'UTF-8'), $field_name_content, $structure_name_css_id);
                        echo'</fieldset></div>';
                        // Show the page part
                       echo'
       <script>
        jQuery.noConflict();
        </script>
    <a href="#" id="getReadyBtnImg_'.str_replace("-","_",$structure_name_css_id).'" class="uploadImgBtn">'.__('Upload Images').'</a>
     <script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'/tarambola/plugins/page_part_forms/js/uploadImage.js"></script>
    <script>
    initUploader("'.URL_PUBLIC.'", "getReadyBtnImg_'.str_replace("-","_",$structure_name_css_id).'", "uploadFileBlockImg_'.str_replace("-","_",$structure_name_css_id).'","'.$structure_name_css_id.'", "'.$maxsize[0].'");
     function cancelDeleteImgs()
        {
            document.cookie =\'tara_images=\';
        }
        function deleteImgs()
        {
                var img="";
                var ca = document.cookie.split(\';\');
                for(var i=0; i<ca.length; i++)
                {
                      var c = ca[i];
                      if(c.search("tara_images")!=-1)
                      {
                            var sub = c.split(\'=\');
                            img=sub[1];
                        }
                }
            if(img!="")
            {
                document.cookie =\'tara_images=\';
                new Ajax.Request(\''.URL_PUBLIC.'tarabackend/scripts/delete_imgs.php\', {method: \'get\',parameters: {array: img},onFailure: function(){ alert(\'Impossível apagar imagem do servidor.\')}});
            }
        }
       document.observe("dom:loaded",function(){var btn = $(\'pageContinueBtn\'); var btn2=$(\'pageCommitBtn\'); var btn3=$(\'cancelarBtn\'); btn.onclick = deleteImgs();btn2.onclick = deleteImgs(); btn3.onclick = cancelDeleteImgs();})
    </script>
    <div id="uploadFileBlockImg_'.str_replace("-","_",$structure_name_css_id).'"></div>
    ';
         echo '<input type="hidden" value="'.htmlentities($content, ENT_COMPAT, 'UTF-8').'" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';
  
                        echo '</input>';

                        // Collect the filters for this editor, apply it later
                        array_push($editor_filters, array(
                            'textarea' => $structure_name_css_id,
                            'filter'   => $filter_id,
                        ));

                        echo '</div>'.PHP_EOL;
                        }
                        break;

     //---------------------------@imagem----------------------------------------------------


    //---------------------------FILE----------------------------------------------------

                      case PagePartFormsController::TYPE_FILE:
                       if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
require_once 'javascript.php';
                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;


                        echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;

                        // Take care of page filters for page parts
                        echo '<p style="display:none">';
                        echo '<label for="part_'.$index.'_filter_id">'.__('Filter').'</label>'.PHP_EOL;
                        echo '<select id="part_'.$index.'_filter_id" name="'.$field_name_prefix.'[filter_id]" onchange="setTextAreaToolbar(\''.$structure_name_css_id.'\', this[this.selectedIndex].value)">
           <option value=""'.(empty($filter_id) ? ' selected="selected"' : '').'>&#8212; '.__('none').' &#8212;</option>';
                        foreach (Filter::findAll() as $filter) {
                            if($filter == "ficheiros") {
                                echo '<option value="'.$filter.'"'. ("ficheiros" == $filter ? ' selected="selected"' : '') .'>';
                                echo Inflector::humanize($filter);
                                echo '</option>';
                            }
                        }

                        echo '</select></p>'.PHP_EOL;
                       echo'<div id="imgDiv"><fieldset id="imgListContainer">';
                        echo applyFile(htmlentities($content, ENT_COMPAT, 'UTF-8'), $field_name_content, $structure_name_css_id);
                        echo'</fieldset></div>';
                        // Show the page part
                       echo'
       <script>
        jQuery.noConflict();
    </script>
    <script type="text/javascript" charset="utf-8" src="'.URL_PUBLIC.'/tarambola/plugins/page_part_forms/js/uploadFile.js"></script>
<script type="text/javascript">
initUploader("'.URL_PUBLIC.'", "getReadyBtnFile_'.str_replace("-","_",$structure_name_css_id).'", "uploadFileBlockFile_'.str_replace("-","_",$structure_name_css_id).'","'.$structure_name_css_id.'");
function cancelDeleteFiles()
        {
            document.cookie =\'tara_files=\';
        }
        function deleteFiles()
        {
                var img="";
                var ca = document.cookie.split(\';\');
                for(var i=0; i<ca.length; i++)
                {
                      var c = ca[i];
                      if(c.search("tara_files")!=-1)
                      {
                            var sub = c.split(\'=\');
                            img=sub[1];
                        }
                }
            if(img!="")
            {
                document.cookie =\'tara_files=\';
                new Ajax.Request(\''.URL_PUBLIC.'tarabackend/scripts/delete_files.php\', {method: \'get\',parameters: {array: img},onFailure: function(){ alert(\'Impossível apagar ficheiro do servidor.\')}});
            }
        }

        document.observe("dom:loaded",function(){var btn = $(\'pageContinueBtn\'); var btn2=$(\'pageCommitBtn\'); var btn3=$(\'cancelarBtn\'); btn.onclick = deleteFiles();btn2.onclick = deleteFiles(); btn3.onclick = cancelDeleteFiles();});
</script>';
echo'
    <a href="#" id="getReadyBtnFile_'.str_replace("-","_",$structure_name_css_id).'" class="uploadImgBtn">'.__('Upload Files').'</a>
    <div id="uploadFileBlockFile_'.str_replace("-","_",$structure_name_css_id).'"></div>
    ';

                        echo '<input type="hidden" value="'.htmlentities($content, ENT_COMPAT, 'UTF-8').'" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';

                        echo '</input>';

                        // Collect the filters for this editor, apply it later
                        array_push($editor_filters, array(
                            'textarea' => $structure_name_css_id,
                            'filter'   => $filter_id,
                        ));

                        echo '</div>'.PHP_EOL;
                        }
                        break;

//---------------------------@File----------------------------------------------------//

                        
   //---------------------------Formulario----------------------------------------------------

                      case PagePartFormsController::TYPE_FORM:
                        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;

                        echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;

                        // Take care of page filters for page parts
                        echo '<p style="display:none">';
                       
                        echo '<input type="hidden" value="unread" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';
                        echo '</input>';

                        
                        echo '</div>'.PHP_EOL;
                        }
                        break;

//---------------------------@Formulario----------------------------------------------------
//---------------------------SEQUENCE----------------------------------------------------

                      case PagePartFormsController::TYPE_SEQUENCE:
                        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;
                          
                        echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;
                        echo'<ul id="ul-'.$structure_name_css_id.'" name="'.$field_name_content.'">';
                        $values = PagePartFormsController::Get_multiple_values($content);
                        $a = array_keys($values);
                        for($i=0; $i<sizeof($a); $i++)
                        {
                            $vars=explode("=>", $a[$i]);
                            $page = Page_Plug::find('banners')->children(array('by_id'=>$vars[0]));
                            echo("<li class='checkId".$vars[0]." ordemLi' id='orderLi-".$vars[0]."'><input class='in1-orderLi-".$vars[0]."' type='hidden' value='".$vars[0]."' id='Page-part-forms-Page-Part-ordem-id-".$vars[0]."'/><input readonly='readonly' value='".$page[0]->title."' id='Page-part-forms-Page-Part-ordem-name-".$vars[0]."' style='width: 50%; font-size:10px'></input><select id='sel-Page-part-forms-Page-Part-ordem' class='sel-orderLi-".$vars[0]."'><option value='1' ".  isSel($vars[1], 1).">1</option><option value='2' ".  isSel($vars[1], 2).">2</option><option value='3' ".  isSel($vars[1], 3).">3</option><option value='4' ".  isSel($vars[1], 4).">4</option><option value='5' ".  isSel($vars[1], 5).">5</option><option value='6' ".  isSel($vars[1], 6).">6</option><option value='7' ".  isSel($vars[1], 7).">7</option><option value='8' ".  isSel($vars[1], 8).">8</option><option value='9' ".  isSel($vars[1], 9).">9</option><option value='10' ".  isSel($vars[1], 10).">10</option></select></li>");
                        }
                        //echo '<input type="hidden" value="" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';
                        
                        echo'</ul>';
                        
                        echo '<input type="hidden" value="'.htmlentities($content, ENT_COMPAT, 'UTF-8').'" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';
                        echo '</input>';
                        echo '</div>'.PHP_EOL;
                        }
                        break;
                       
//---------------------------@SEQUENCE----------------------------------------------------

//-------------------------------SELECT-----------------------------------------------------

                    case PagePartFormsController::TYPE_SELECT:
                        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                        echo '<div class="'.$css_class_prefix.'select">'.PHP_EOL;

                        // Get type based on limit and number of choices
                        $expand      = false;
                        $select_many = false;

                                $class="";
                                $html="";
                                if($structure[$name][PagePartFormsController::PROPERTY_VALIDATION][0]==1)
                                {
                                    $class="required_radiobox";
                                    $html="<span label='".$field_name_content."' class='requiredMsg'>Mandatory Field</span>";
                                }
                                echo($html);
                        switch(PagePartFormsController::Get_select_type($element)) {
                            case PagePartFormsController::SELECT_TYPE_LIST_MORE:
                            // Same as list one but with multiple="multiple"
                                $expand      = true;
                                $select_many = true;
                            case PagePartFormsController::SELECT_TYPE_LIST_ONE:
                            // Same as dropdown but with size
                                $expand      = true;
                            case PagePartFormsController::SELECT_TYPE_DROPDOWN:
                                $values = $structure[$name][PagePartFormsController::PROPERTY_VALUES];
                                $multiple_values = PagePartFormsController::Get_multiple_values($content);

                                // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items.
                                if ($select_many) {
                                    echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';
                                }

                                // Select widget
                                echo '<select id="'.$structure_name_css_id.'"';

                                // Show all values at once
                                if ($expand) {
                                    echo ' size="'.count($values).'"';
                                }

                                // Select multiple
                                if ($select_many) {
                                    echo ' name="'.$plugin_id.'_values_for_'.$name.'"';
                                    echo ' multiple="multiple"';

                                    // Add node information for multiple options
                                    array_push($multiple_content, array(
                                        'title'    => $title,
                                        'content'  => $field_name_content,
                                        'name'     => $name,
                                        'limit'    => $limit,
                                    ));
                                }
                                else {
                                    echo ' name="'.$field_name_content.'"';
                                }

                                echo '>';

                                // Add the values
                                foreach ($values as $value) {
                                    echo '<option value="'.$value.'"'.(array_key_exists($value, $multiple_values) ? 'selected="selected"' : '').'>'.$value.'</option>';
                                }

                                echo '</select>';
                                break;
                               case PagePartFormsController::SELECT_TYPE_RADIO:
                                foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value) {
                                    echo '<span class="'.$css_class_prefix.'part-radio" label="'.$field_name_content.'">';
                                    // NOTE: if changed back from many to one for this page_part, no value will be selected.
                                    echo '<input type="radio" '.($content == $value ? 'checked="checked"' : '').' name="'.$field_name_content.'" value="'.$value.'" class="'.$class.'" label="'.$title.'" />'.$value;
                                    echo '</span>'.PHP_EOL;
                                }
                                break;
                            case PagePartFormsController::SELECT_TYPE_CHECKBOX:
                                echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';

                                $multiple_values = PagePartFormsController::Get_multiple_values($content);

                                foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value) {
                                    echo '<span class="'.$css_class_prefix.'part-checkbox">';
                                    // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items
                                    echo '<input type="checkbox" '.(array_key_exists($value, $multiple_values) ? 'checked="checked"' : '').' name="'.$plugin_id.'_values_for_'.$name.'" value="'.$value.'" class="'.$class.'" />'.$value;
                                    echo '</span>'.PHP_EOL;
                                }

                                // Add node information for multiple options
                                array_push($multiple_content, array(
                                    'title'   => $title,
                                    'content' => $field_name_content,
                                    'name'    => $name,
                                    'limit'   => $limit,
                                ));
                                break;
                        }
                        echo '</div>';
                        }
                        break;
//_---------------------------------DATE--------------------------------------------------
                    case PagePartFormsController::TYPE_DATE:
                        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                        echo '<span class="'.$css_class_prefix.'part-date">';
                        echo '<input type="text" name="'.$field_name_content.'" size="10" maxlength="10" value="'.$content.'" />';
                        echo '<img onclick="displayDatePicker(\''.$field_name_content.'\');" src="images/icon_cal.gif" alt="'.__('Show Calendar').'" />';
                        echo '</span>'.PHP_EOL;
                        }
                        break;
//_---------------------------------populate--------------------------------------------------
                    case PagePartFormsController::TYPE_POPULATE:
                        if($structure[$name][PagePartFormsController::PROPERTY_TRANSLATION][0]==1 || $langs[$langIt]=="pt")
                        {
                        echo '<div class="'.$css_class_prefix.'select">'.PHP_EOL;

                        // Get type based on limit and number of choices
                        $expand      = false;
                        $select_many = false;
                        switch(PagePartFormsController::Get_select_type($element)) {
                            case PagePartFormsController::SELECT_TYPE_LIST_MORE:
                            // Same as list one but with multiple="multiple"
                                $expand      = true;
                                $select_many = true;
                            case PagePartFormsController::SELECT_TYPE_LIST_ONE:
                            // Same as dropdown but with size
                                $expand      = true;
                            case PagePartFormsController::SELECT_TYPE_DROPDOWN:
                                $values = $structure[$name][PagePartFormsController::PROPERTY_VALUES];
                                $multiple_values = PagePartFormsController::Get_multiple_values($content);

                                // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items.
                                if ($select_many) {
                                    echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';
                                }
                                
                                 $class="";
                        $html="";
                        if($structure[$name][PagePartFormsController::PROPERTY_VALIDATION][0]==1)
                        {
                            $class="required";
                            $html="<span id='".$structure_name_css_id."_msg' class='requiredMsg'>Mandatory Field</span>";
                        }
                        echo($html);
                                // Select widget
                                echo '<select id="'.$structure_name_css_id.'"';

                                // Show all values at once
                                if ($expand) {
                                    echo ' size="'.count($values).'"';
                                }

                                // Select multiple
                                if ($select_many) {
                                    echo ' name="'.$plugin_id.'_values_for_'.$name.'"';
                                    echo ' multiple="multiple"';

                                    // Add node information for multiple options
                                    array_push($multiple_content, array(
                                        'title'    => $title,
                                        'content'  => $field_name_content,
                                        'name'     => $name,
                                        'limit'    => $limit,
                                    ));
                                }
                                else {
                                    echo ' name="'.$field_name_content.'"';
                                }

                                echo '>';

                                // Add the values
                                $groups = getGroups($structure[$name][PagePartFormsController::PROPERTY_VALUES][0]);

                                //foreach ($values as $value) {
                                foreach($groups as $value)
                                {
                                    echo '<option value="'.$value->id.'"'.(array_key_exists($value->id, $multiple_values) ? 'selected="selected"' : '').'>'.$value->title.'</option>';
                                }

                                echo '</select>';
                                break;
                             case PagePartFormsController::SELECT_TYPE_RADIO:
                                 $groups = getGroups();
                              //  foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value)
                              foreach($groups as $value)
                              {
                                    echo '<span class="'.$css_class_prefix.'part-radio">';
                                    // NOTE: if changed back from many to one for this page_part, no value will be selected.
                                    echo '<input type="radio" '.($content == $value->id ? 'checked="checked"' : '').' name="'.$field_name_content.'" value="'.$value->id.'" />'.$value->title;
                                    echo '</span>'.PHP_EOL;
                                }
                                break;
                            case PagePartFormsController::SELECT_TYPE_CHECKBOX:
                                echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';

                                $multiple_values = PagePartFormsController::Get_multiple_values($content);

                                $opts=getGroups($structure[$name][PagePartFormsController::PROPERTY_VALUES][0]);
                                //foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value) {
                                $i=0;
                                require_once 'javascript.php';
                                echo"<script type='text/javascript'>
        jQuery.noConflict();
    </script>";
                                foreach ($opts as $value) {
                                    echo '<span class="'.$css_class_prefix.'part-checkbox">';
                                    // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items
                                    echo '<input class="checkSquare orderC'.$i.'" type="checkbox" '.(array_key_exists($value->id, $multiple_values) ? 'checked="checked"' : '').' name="'.$plugin_id.'_values_for_'.$name.'" value="'.$value->id.'" />';
                                    
                                  /*  if($value->hasContent("imagem"))
                                    {
                                        echo '<img class="checkImg" src="'.$value->getFirstImage(null, false, ".60x25c").'" alt="'.$value->title.'" />';
                                    }
                                   * 
                                   */
                                    echo '<span class="checkTitle">'.$value->title.'</span>';
                                    echo '</span>'.PHP_EOL;
                                    if(isset($structure[$name][PagePartFormsController::PROPERTY_ORDER]) && $structure[$name][PagePartFormsController::PROPERTY_ORDER]==1)
                                    {
                                        echo"
                                            <script type='text/javascript'>
                                                jQuery('.orderC".$i."').bind('change',function() { jQuery('.orderC".$i."').unbind('change');
                                                    
                                                        if(jQuery('.orderC".$i."').is(':checked'))
                                                            jQuery('#ul-Page-part-forms-Page-Part-ordem').append('<li class=\'checkId".$opts[$i]->id." ordemLi\' id=\'orderLi-".$opts[$i]->id."\'><input class=\'in1-orderLi-".$opts[$i]->id."\' type=\'hidden\' value=\'".$opts[$i]->id."\' id=\'Page-part-forms-Page-Part-ordem-id-".$opts[$i]->id."\'/><input readonly=\'readonly\' value=\'".$opts[$i]->title."\' id=\'Page-part-forms-Page-Part-ordem-name-".$opts[$i]->id."\' style=\'width: 50%; font-size:10px\'></input><select id=\'sel-Page-part-forms-Page-Part-ordem\' class=\'sel-orderLi-".$opts[$i]->id."\'><option value=\'1\'>1</option><option value=\'2\'>2</option><option value=\'3\'>3</option><option value=\'4\'>4</option><option value=\'5\'>5</option><option value=\'6\'>6</option><option value=\'7\'>7</option><option value=\'8\'>8</option><option value=\'9\'>9</option><option value=\'10\'>10</option></select></li>');
                                                        else
                                                            jQuery('.checkId".$opts[$i]->id."').remove();
                                                    });
                                            </script>
                                            ";
                                    }
                                    $i++;
                                }

                                // Add node information for multiple options
                                array_push($multiple_content, array(
                                    'title'   => $title,
                                    'content' => $field_name_content,
                                    'name'    => $name,
                                    'limit'   => $limit,
                                ));
                                break;
                        }
                        echo '</div>';
                        }
                        break;

//_---------------------------------table--------------------------------------------------
                    case PagePartFormsController::TYPE_TABLE:
                        

                        

                        
                        echo '<div class="'.$css_class_prefix.'table">'.PHP_EOL;
                            echo'
                           <style type="text/css" title="currentStyle">
                                    @import "'.URL_PUBLIC.'tarabackend/stylesheets/style.css";
                           </style>
                          <script type="text/javascript" src="'.URL_PUBLIC.'tarabackend/javascripts/fabtabulous.js"></script>
                            <script type="text/javascript" src="'.URL_PUBLIC.'tarabackend/javascripts/tablekit.js"></script>
                            <script type="text/javascript">
                                TableKit.Editable.init("'.$css_class_prefix.'listagem");
                                TableKit.options.editAjaxURI = "/cmc/tarabackend/scripts/test.php";

                            </script>

';
                         /*     <script type="text/javascript" charset="utf-8">

                                                $(document).ready(function() {
                                                        $("'.$css_class_prefix.'#listagem").dataTable( );
                                                } );
                                        </script> */
                        // Get type based on limit and number of choices
                        $expand      = false;
                        $select_many = false;

                        switch(PagePartFormsController::Get_select_type($element)) {
                            case PagePartFormsController::SELECT_TYPE_LIST_MORE:
                            // Same as list one but with multiple="multiple"
                                $expand      = true;
                                $select_many = true;
                            case PagePartFormsController::SELECT_TYPE_LIST_ONE:
                            // Same as dropdown but with size
                                $expand      = true;
                            case PagePartFormsController::SELECT_TYPE_DROPDOWN:
                                $values = $structure[$name][PagePartFormsController::PROPERTY_VALUES];
                                $multiple_values = PagePartFormsController::Get_multiple_values($content);

                                // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items.
                                if ($select_many) {
                                    echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';
                                }

                                // Select widget
                                echo '<select id="'.$structure_name_css_id.'"';

                                // Show all values at once
                                if ($expand) {
                                    echo ' size="'.count($values).'"';
                                }

                                // Select multiple
                                if ($select_many) {
                                    echo ' name="'.$plugin_id.'_values_for_'.$name.'"';
                                    echo ' multiple="multiple"';

                                    // Add node information for multiple options
                                    array_push($multiple_content, array(
                                        'title'    => $title,
                                        'content'  => $field_name_content,
                                        'name'     => $name,
                                        'limit'    => $limit,
                                    ));
                                }
                                else {
                                    echo ' name="'.$field_name_content.'"';
                                }

                                echo '>';

                                // Add the values
                                $groups = getGroups();

                                //foreach ($values as $value) {
                                foreach($groups as $value)
                                {
                                    echo '<option value="'.$value->slug.'"'.(array_key_exists($value->slug, $multiple_values) ? 'selected="selected"' : '').'>'.$value->title.'</option>';
                                }

                                echo '</select>';
                                break;
                             case PagePartFormsController::SELECT_TYPE_RADIO:
                                 $groups = getGroups();
                              //  foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value)
                              foreach($groups as $value)
                              {
                                    echo '<span class="'.$css_class_prefix.'part-radio">';
                                    // NOTE: if changed back from many to one for this page_part, no value will be selected.
                                    echo '<input type="radio" '.($content == $value->slug ? 'checked="checked"' : '').' name="'.$field_name_content.'" value="'.$value->slug.'" />'.$value->title;
                                    echo '</span>'.PHP_EOL;
                                }
                                break;
//***************************************************** CHECKBOX *******************************************************************
                            case PagePartFormsController::SELECT_TYPE_CHECKBOX:
                                echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';

                                $multiple_values = PagePartFormsController::Get_multiple_values($content);


                                $opts=$structure[$name][PagePartFormsController::PROPERTY_VALUES];
echo'<table id="'.$css_class_prefix.'listagem" class="sortable resizable editable" cellpadding="0" cellspacing="0" border="0" class="display">
                                         <thead>
                                            <tr>';
                                //foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value) {
                                foreach ($opts as $value) {
                                    echo'
                                            <th id="'.$value.'">
                                                    '.$value.'
                                            </th>';
                                }
                                echo'</tr>
            </thead><tbody>';
                                            $table = getTable($name);
                                            for($i=0; $i<sizeOf($table); $i++)
                                            {
                                                echo'<tr id="'.$table[$i]->id.'">';
                                                 foreach ($opts as $value) {
                                                        echo'
                                                                 <td >'.$table[$i]->$value.' </td>';
                                                    }
                                                       
                                               echo'</tr>';
                                               
                                            }
                                            echo'<tr id="new">';
                                                        foreach ($opts as $value) {
                                                        echo'
                                                             <td > </td>
                                                        ';}
                                                        echo'
                                                </tr>';
                                            echo'</tbody>
</table>';

                                // Add node information for multiple options
                                array_push($multiple_content, array(
                                    'title'   => $title,
                                    'content' => $field_name_content,
                                    'name'    => $name,
                                    'limit'   => $limit,
                                ));
                                break;
                        }
                        echo '</div>';
                        break;
//***************************************************** @CHECKBOX *******************************************************************
                }
                ?>
        </div> <!-- INPUT -->
    </div> <!-- ROW -->
    <?php $index++; }
    else
    {
        echo(" ");
    }
                                                        } ?>
</div>
 <!--   </div>
    </div>-->
<? endfor; ?>
        <!-- ######################################################################## @@PAGE PART FOR ALT LANGS ################################################################################################## -->  
</div>
<script type="text/javascript">

  //  triggerDrop();
    var target;

    function triggerDrop()
    {
        document.onmousedown = OnMouseDown;
        document.onmouseup = OnMouseUp;
    }

    function OnMouseUp(e)
    {
        alert(target);
    }
    function OnMouseDown(e)
    {
        target=e.target;
    }

    function deleteImg(path, name, idImg)
    {
        document.cookie='tara_images='+path+'/#imgsepara##/';
        var htm=document.getElementById(idImg);
        var imgs = htm.value.split("/#imgsepara##/");
        if(document.all)
        {
             htm.innerText = "";
        }
        else
        {
            htm.textContent = "";
        }
        var imgList ="";
        for(i = 0; i < imgs.length; i++)
        {
            var full = imgs[i]+"/#imgsepara##/";
            if(imgs[i]!=path && full != "/#imgsepara##/")
              {
                  
                  /*  if(document.all)
                    {
                         htm.innerText+=full;
                    }
                     else
                    {
                        htm.textContent+=full;
                    }*/
                imgList+=imgs[i] + "/#imgsepara##/";
              }
              else
                  {
                      full = "";
                  }
          
           
        }
        $(idImg).value= imgList;
        document.getElementById(path).parentNode.removeChild(document.getElementById(path));
        //jQuery("#pageContinueBtn").trigger('click');
        jQuery('form:first').submit();
    }
        function deleteFile(path, name, idFile)
    {
         document.cookie='tara_files='+path+'/#imgsepara##/';
        var htm=document.getElementById(idFile);
        var imgs = htm.value.split("/#imgsepara##/");
        if(document.all)
        {
             htm.innerText = "";
        }
        else
        {
            htm.textContent = "";
        }
        var imgList ="";
        for(i = 0; i < imgs.length; i++)
        {
            var full = imgs[i]+"/#imgsepara##/";
            if(imgs[i]!=path && full != "/#imgsepara##/")
              {
                //  alert(full+path);
                  /*  if(document.all)
                    {
                         htm.innerText+=full;
                    }
                     else
                    {
                        htm.textContent+=full;
                    }*/
                imgList+=imgs[i];
              }
              else
                  {
                      full = "";
                  }


        }
        $(idFile).value= imgList;
        document.getElementById(path).parentNode.removeChild(document.getElementById(path));
    }
</script>
<script type="text/javascript">
     jQuery(document).ready(function(){
        jQuery('ul.tabs').each(function(){
    // For each set of tabs, we want to keep track of
    // which tab is active and it's associated content
    var $active, $content, $links = jQuery(this).find('a');

    // If the location.hash matches one of the links, use that as the active tab.
    // If no match is found, use the first link as the initial active tab.
    $active = jQuery($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
    $active.addClass('active');

    $content = jQuery($active[0].hash);

    // Hide the remaining content
    $links.not($active).each(function () {
      jQuery(this.hash).hide();
    });
    jQuery(this).find('a').click(function(e){
      $active.removeClass('active');
      $content.hide();

      // Update the variables with the new link and content
      $active = jQuery(this);
      $content = jQuery(this.hash);

      // Make the tab active.
      $active.addClass('active');
      $content.show();

      // Prevent the anchor's default click action
      e.preventDefault();
    });
  });
    });
    <?php
foreach ($multiple_content as $e) {
    echo 'multiple_content.push( {';
    echo implode(", ", array_map("page_part_forms_dump_hash_delegate", array_keys($e), array_values($e)));
    echo '});'.PHP_EOL;
}

foreach ($editor_filters as $e) {
    echo 'editor_filters.push( {';
    echo implode(", ", array_map("page_part_forms_dump_hash_delegate", array_keys($e), array_values($e)));
    echo '});'.PHP_EOL;
}
?>
</script>