<h1><?php echo __('Configurar Mensagens registo Utilizadores'); ?></h1>
<?
$url = explode("/",($_SERVER["REQUEST_URI"])); 
    
    $id=$url[sizeof($url)-1];
    $sql = "SELECT * FROM tara_front_users_messages WHERE id = '".$id."'";
             $result = Record::query($sql);
             $result = $result->fetchAll();
?>
<h3 id="editPageH">Editar</h3>
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Lang:</label>
                <div class="page-part-forms-input">
                    <select id="lang_Select">
                        <option value="pt" <? echo(($result[0]['lang']=='pt')?'selected="selected"':''); ?> >PT</option>
                        <option value="en" <? echo(($result[0]['lang']=='en')?'selected="selected"':''); ?> >EN</option>
                        <option value="fr" <? echo(($result[0]['lang']=='fr')?'selected="selected"':''); ?> >FR</option>
                        <option value="es" <? echo(($result[0]['lang']=='es')?'selected="selected"':''); ?> >ES</option>
                    </select>   
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Função:</label>
                <div class="page-part-forms-input">
                    <select id="funcao_Select">
                        <option value="registo" <? echo(($result[0]['funcao']=='registo')?'selected="selected"':''); ?> >Registo</option>
                        <option value="confirmação" <? echo(($result[0]['funcao']=='confirmação')?'selected="selected"':''); ?> >Confirmação</option>
                    </select>     
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Mensagem:</label>
                <div class="page-part-forms-row_page_part">
                    <div class="page-part-forms-input">
                        <div class="page-part-forms-part-page-part">
                            <textarea  id="mensagem_Text" cols="40" rows="20" style="width: 100%; display: block;" name="mensagem"  class="textarea"><? echo($result[0]['mensagem']); ?></textarea>
                        </div>
                    </div> <!-- INPUT -->
                </div>
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick='updateMensagem(<? echo($id); ?>, "<? echo(URL_PUBLIC); ?>");'>  salvar </button>
        </div>
    </div>
</div>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Example content CSS (should be your site CSS)
        content_css : "css/example.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
});

</script>
