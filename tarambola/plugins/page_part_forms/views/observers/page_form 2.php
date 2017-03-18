<?php 
// Javascript is used to remove the existing page_parts interface provided by the tarambola core.
$replace_with_id = $css_id_prefix . "Form";
//echo'
//                        <script type="text/javascript" language="javascript" src="'.URL_PUBLIC.'tarabackend/javascripts/jquery.dataTables2.js"></script>'

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
                setTextAreaToolbar(item["textarea"], item["filter"]);
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
                });

                return submit_form;
            });
        });
    })(jQuery);
    //]]>
</script>


<div id="<?php echo $replace_with_id; ?>">
    <?php
    //função para dividir o texto (caminho das imagens), para aparecerem no fieldset
    function apply($text, $name, $idImg) {
        $result = "<ul class ='imgs'>";
        $temp = explode("/#imgsepara##/", $text);
        foreach ($temp as $img) {
            if($img!="") {
                $temp3 = preg_split("/\.([^\.]+)$/", $img);
                preg_match("/\.([^\.]+)$/", $img, $temp2);
                $result.="<li class='imgCont' style='float:left; margin-left:5px' id='".$img."'>
                        <a href='".IMG_URL.$temp3[0].$temp2[0]."'><img src='".IMG_URL.$temp3[0].".64c".$temp2[0]."' class='imgTh'/></a>
                        <input class='deleteBtn' type='button' onclick=\"deleteImg('".$img."', '".$name."', '".$idImg."')\" value='X' title='Apagar'/>
                    </li>";
            }
        }
        $result.="</ul>";
        return $result;
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
            return '../tarambola/plugins/assets/' . $retval;
      }
 
     function applyFile($text, $name, $idFile) {
        $result = "<ul class ='imgs'>";
        $temp = explode("/#imgsepara##/", $text);
        foreach ($temp as $img) {
            if($img!="") {
                var_dump($img);
                $temp3 = preg_split("/\.([^\.]+)$/", $img);
                preg_match("/\.([^\.]+)$/", $img, $temp2);
 $result.="<li class='imgCont' style='float:left; margin-left:5px' id='".$img."'>
                       <img src='".getIcon($temp2[0])."' class='imgTh'/>
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
    include_once(SERVER_URL."tarambola/app/classes/Page_Plug.php");
    function getGroups($module)
    {
        $groups = Page_Plug::find($module)->children();
        return $groups;
    }

    // Number all page parts
    $index = 0;
    // This page parts have multiple values/options
    $multiple_content = array();
    // Apply the filters after the form is replaced
    $editor_filters = array();
    echo('<div id="pages" class="pages">
        <div id ="page-none" class ="page">
            
        </div>
    </div>');
    // Process all structural elements from page_part_forms defnition and create the page_part_form.
    foreach ($structure as $name => $element) {
    // Data from this page part
        $structure_name_css_id = page_part_forms_name_to_id($name);
        $page_part_id          = array_key_exists($name, $page_parts) ? $page_parts[$name]->id : null;
        $content               = array_key_exists($name, $page_parts) ? $page_parts[$name]->content : '';
        // Common fields from the structure
        $limit                 = array_key_exists(PagePartFormsController::PROPERTY_LIMIT, $element) ? $element[PagePartFormsController::PROPERTY_LIMIT] : 1;
        $title                 = $element[PagePartFormsController::PROPERTY_TITLE];
        // Name of the field
        $field_name_prefix     = 'part['.$index.']';
        $field_name_content    = $field_name_prefix.'[content]';

        ?>
    <?php if($element[PagePartFormsController::PROPERTY_TYPE] ==PagePartFormsController::TYPE_PAGE_PART) : ?>
    <div class="<?php echo $css_class_prefix; ?>row_page_part">
        <input id="part_<?php echo $index; ?>_name" name="<?php echo $field_name_prefix; ?>[name]" type="hidden" value="<?php echo $name; ?>" />
        <?php if ($page_part_id): ?>
        <input id="part_<?php echo $index; ?>_id" name="<?php echo $field_name_prefix; ?>[id]" type="hidden" value="<?php echo $page_part_id; ?>" />
    <?php endif; ?>
        <label class="<?php echo $css_class_prefix;?>label" for="<?php echo $structure_name_css_id; ?>"><?php echo $title; ?></label>

        <div class="<?php echo $css_class_prefix; ?>input">
    <?php else : ?>
            <div class="<?php echo $css_class_prefix; ?>row">
        <input id="part_<?php echo $index; ?>_name" name="<?php echo $field_name_prefix; ?>[name]" type="hidden" value="<?php echo $name; ?>" />
        <?php if ($page_part_id): ?>
        <input id="part_<?php echo $index; ?>_id" name="<?php echo $field_name_prefix; ?>[id]" type="hidden" value="<?php echo $page_part_id; ?>" />
    <?php endif; ?>
        <label class="<?php echo $css_class_prefix;?>label" for="<?php echo $structure_name_css_id; ?>"><?php echo $title; ?></label>

        <div class="<?php echo $css_class_prefix; ?>input">
    <?php endif ?>
    <?php
    switch($element[PagePartFormsController::PROPERTY_TYPE]) {
        case PagePartFormsController::TYPE_PAGE_PART:
                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;

                        echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;

                        // Take care of page filters for page parts
                        echo '<p class="pFiltroTexto">';
                        echo '<select class="filtroTexto" id="part_'.$index.'_filter_id" name="'.$field_name_prefix.'[filter_id]" onchange="setTextAreaToolbar(\''.$structure_name_css_id.'\', this[this.selectedIndex].value)">
           <option value=""'.(empty($filter_id) ? ' selected="selected"' : '').'>&#8212; '.__('none').' &#8212;</option>';
                        foreach (Filter::findAll() as $filter) {
                           /* if($filter=="tinymce")
                                {echo '<option selected="selected "value="'.$filter.'"'. ($filter_id == $filter ? ' selected="selected"' : '') .'>';}
                             else*/
                                echo '<option value="'.$filter.'"'. ($filter_id == $filter ? ' selected="selected"' : '') .'>';
                            echo Inflector::humanize($filter);
                            echo '</option>';
                        }

                        echo '</select>'.PHP_EOL;
                         echo '<label class="labelTexto" for="part_'.$index.'_filter_id">'.__('Filter').'</label></p>'.PHP_EOL;
                        // Show the page part
                        echo '<textarea class="textarea" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%" rows="20" cols="40"';
                        echo ' onkeydown="return allowTab(event, this);" onkeyup="return allowTab(event,this);" onkeypress="return allowTab(event,this);">';
                        echo htmlentities($content, ENT_COMPAT, 'UTF-8');
                        echo '</textarea>';

                        // Collect the filters for this editor, apply it later
                        array_push($editor_filters, array(
                            'textarea' => $structure_name_css_id,
                            'filter'   => $filter_id,
                        ));

                        echo '</div>'.PHP_EOL;
                        break;
                    case PagePartFormsController::TYPE_TEXT:
                        echo '<input type="text" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                        break;
                        
  //-------------------------------data-----------------------------------------------------
             /*           case PagePartFormsController::TYPE_TEXT:
                        echo '<input type="text" id="'.$structure_name_css_id.'" class="'.$css_class_prefix.'part-text" name="'.$field_name_content.'" value="'.$content.'" maxlength="'.$limit.'" />';
                        break;*/
  //-------------------------@data------------------------------------------------------------------

  //--------------------------imagem-------------------------------------------------------------
              case PagePartFormsController::TYPE_IMAGE:
                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;

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
<script type="text/javascript" src="../tarambola/plugins/page_part_forms/js/ajaxupload.js"></script>

<script type="text/javascript">
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

	document.observe("dom:loaded",function(){new AjaxUpload(\'upload_button_'.$structure_name_css_id.'\', {action: \'../tarambola/plugins/page_part_forms/scripts/upload.php\',  name: \'userfile\', onComplete: function(file, response) {  if(response=="Erro ao introduzir Imagem!"){alert(\'Erro ao introduzir Imagem!\')}else{ $("'.$structure_name_css_id.'").value = response + "/#imgsepara##/" + $("'.$structure_name_css_id.'").value ; alert(\'Imagem introduzida com sucesso!\'); }},  onSubmit : function(file , ext){if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){alert(\'Erro: Ficheiro Inválido para upload\');return false;}}});});
       document.observe("dom:loaded",function(){var btn = $(\'pageContinueBtn\'); var btn2=$(\'pageCommitBtn\'); var btn3=$(\'cancelarBtn\'); btn.onclick = deleteImgs();btn2.onclick = deleteImgs(); btn3.onclick = cancelDeleteImgs();})
</script>';
echo'
<div id="upload_button_'.$structure_name_css_id.'"><img src="../tarambola/plugins/page_part_forms/images/upload.jpg" alt="upload" style="wi"/></div>';
         echo '<input type="hidden" value="'.htmlentities($content, ENT_COMPAT, 'UTF-8').'" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';
  
                        echo '</input>';

                        // Collect the filters for this editor, apply it later
                        array_push($editor_filters, array(
                            'textarea' => $structure_name_css_id,
                            'filter'   => $filter_id,
                        ));

                        echo '</div>'.PHP_EOL;
                        break;

     //---------------------------@imagem----------------------------------------------------


    //---------------------------FILE----------------------------------------------------

                      case PagePartFormsController::TYPE_FILE:

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

<script type="text/javascript" src="../tarambola/plugins/page_part_forms/js/ajaxupload.js"></script>

<script type="text/javascript">
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
	document.observe("dom:loaded",function(){ new AjaxUpload(\'upload_file_button_'.$structure_name_css_id.'\', {action: \'../tarambola/plugins/page_part_forms/scripts/upload_file.php\',  name: \'userfile\', onComplete: function(file, response) {  if(response=="Erro ao introduzir Ficheiro!"){alert(\'Erro ao introduzir Ficheiro!\')}else{ $("'.$structure_name_css_id.'").value = response + "/#imgsepara##/" + $("'.$structure_name_css_id.'").value ; alert(\'Ficheiro introduzido com sucesso!\'); }} });});
        document.observe("dom:loaded",function(){var btn = $(\'pageContinueBtn\'); var btn2=$(\'pageCommitBtn\'); var btn3=$(\'cancelarBtn\'); btn.onclick = deleteFiles();btn2.onclick = deleteFiles(); btn3.onclick = cancelDeleteFiles();});
</script>';
echo'
<div id="upload_file_button_'.$structure_name_css_id.'"><img src="../tarambola/plugins/page_part_forms/images/upload.jpg" alt="upload" style="wi"/></div>';
                        echo '<input type="hidden" value="'.htmlentities($content, ENT_COMPAT, 'UTF-8').'" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';

                        echo '</input>';

                        // Collect the filters for this editor, apply it later
                        array_push($editor_filters, array(
                            'textarea' => $structure_name_css_id,
                            'filter'   => $filter_id,
                        ));

                        echo '</div>'.PHP_EOL;
                        break;

//---------------------------@File----------------------------------------------------
   //---------------------------Formulario----------------------------------------------------

                      case PagePartFormsController::TYPE_FORM:

                        $filter_id = array_key_exists($name, $page_parts) ? $page_parts[$name]->filter_id : null;

                        echo '<div class="'.($css_class_prefix.'part-page-part').'">'.PHP_EOL;

                        // Take care of page filters for page parts
                        echo '<p style="display:none">';
                       
                        echo '<input type="hidden" value="unread" id="'.$structure_name_css_id.'" name="'.$field_name_content.'" style="width: 100%; font-size:0px">';
                        echo '</input>';

                        
                        echo '</div>'.PHP_EOL;
                        break;

//---------------------------@Formulario----------------------------------------------------

//-------------------------------SELECT-----------------------------------------------------

                    case PagePartFormsController::TYPE_SELECT:
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
                                    echo '<span class="'.$css_class_prefix.'part-radio">';
                                    // NOTE: if changed back from many to one for this page_part, no value will be selected.
                                    echo '<input type="radio" '.($content == $value ? 'checked="checked"' : '').' name="'.$field_name_content.'" value="'.$value.'" />'.$value;
                                    echo '</span>'.PHP_EOL;
                                }
                                break;
                            case PagePartFormsController::SELECT_TYPE_CHECKBOX:
                                echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';

                                $multiple_values = PagePartFormsController::Get_multiple_values($content);

                                foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value) {
                                    echo '<span class="'.$css_class_prefix.'part-checkbox">';
                                    // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items
                                    echo '<input type="checkbox" '.(array_key_exists($value, $multiple_values) ? 'checked="checked"' : '').' name="'.$plugin_id.'_values_for_'.$name.'" value="'.$value.'" />'.$value;
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
                        break;
                    case PagePartFormsController::TYPE_DATE:
                        echo '<span class="'.$css_class_prefix.'part-date">';
                        echo '<input type="text" name="'.$field_name_content.'" size="10" maxlength="10" value="'.$content.'" />';
                        echo '<img onclick="displayDatePicker(\''.$field_name_content.'\');" src="images/icon_cal.gif" alt="'.__('Show Calendar').'" />';
                        echo '</span>'.PHP_EOL;
                        break;

//_---------------------------------populate--------------------------------------------------
                    case PagePartFormsController::TYPE_POPULATE:
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
                            case PagePartFormsController::SELECT_TYPE_CHECKBOX:
                                echo '<textarea style="display: none;" name="'.$field_name_content.'" rows="1" cols="1">'.$content.'</textarea>';

                                $multiple_values = PagePartFormsController::Get_multiple_values($content);


                                $opts=getGroups($structure[$name][PagePartFormsController::PROPERTY_VALUES][0]);

                                //foreach ($structure[$name][PagePartFormsController::PROPERTY_VALUES] as $value) {
                                foreach ($opts as $value) {
                                    echo '<span class="'.$css_class_prefix.'part-checkbox">';
                                    // NOTE: tarambola can only handle simple strings as values instead of arrays. Use javascript to create a string out of the selected items
                                    echo '<input type="checkbox" '.(array_key_exists($value->title, $multiple_values) ? 'checked="checked"' : '').' name="'.$plugin_id.'_values_for_'.$name.'" value="'.$value->title.'" />'.$value->title;
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
                        break;

//_---------------------------------table--------------------------------------------------
                    case PagePartFormsController::TYPE_TABLE:


                        function getTable($name)
                        {
                            global $__tarambola_CONN__;

                             $sql="SELECT * FROM tara_".$name."";
                             if ($stmt = $__tarambola_CONN__->prepare($sql))
                                {
                                    $stmt->execute();

                                    while ($object = $stmt->fetchObject())
                                    {

                                        $tables[] = $object;
                                    }
                                }
                                return($tables);    
                        }



                        
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
                }
                ?>
        </div> <!-- INPUT -->
    </div> <!-- ROW -->
                <?php $index++; } ?>
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
                imgList+=imgs[i];
              }
              else
                  {
                      full = "";
                  }
          
           
        }
        $(idImg).value= imgList;
        document.getElementById(path).parentNode.removeChild(document.getElementById(path))
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
        document.getElementById(path).parentNode.removeChild(document.getElementById(path))
    }
</script>

<script type="text/javascript">
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
