<?php
require_once(SERVER_URL.'tarambola/plugins/formularios/Formularios.php');
?>
<h1><?php echo __('Forms'); ?></h1>
<?php error_reporting(E_ALL); ?>

<?php
$_SESSION['pagina'] = $_SERVER['REQUEST_URI'];
$form = new Formularios();

if(!isset($_GET['formulario']))
{
    
        $html='
            <div id=innerModulos>
                <ul id=listaModulos>
                    '.$form->getListPag().'
                </ul>
            </div>
            ';
}
else
{
    //--------------------------------header para a tabela (javascript)----------------
        $headerApp='
            <style type="text/css" title="currentStyle">
                            @import "'.URL_PUBLIC.'/tarabackend/stylesheets/demo_table.css";
            </style>
            <script type="text/javascript" language="javascript" src="'.URL_PUBLIC.'/tarabackend/javascripts/jquery.js"></script>
            <script type="text/javascript" language="javascript" src="'.URL_PUBLIC.'/tarabackend/javascripts/jquery.dataTables.js"></script>
                <script type="text/javascript">
    $(document).ready(function() {
       $(\'#listagem\').dataTable({
            "oLanguage": {
                "sLengthMenu": "' . __('Show').' _MENU_ '. __('Entries') .'",
                "sZeroRecords": "'. __('No records found').'",
                "sInfo": "_START_ '.__('the').' _END_ '. __('of').' _TOTAL_ '. __('Entries').' ",
                "sInfoEmpty": "0 '. __('the').' 0 '. __('of').' 0 '. __('Entries').'",
                "sInfoFiltered": "('. __('filtered from').' _MAX_ '. __('total entries').')",
                "sInfoPostFix": "",
                "sSearch": "'. __('Search').':",
                "sUrl": "",
                "oPaginate": {
                    "sFirst":    "'. __('First').'",
                    "sPrevious": "'. __('Previous').'",
                    "sNext":     "'. __('Next').'",
                    "sLast":     "'. __('Last').'"
                }
            }
        } );
    } );
</script>
            <script type="text/javascript">
                        function confirmDel(id){
                            var s = "Tem a certeza que pretende apagar?";
                            var answer = confirm (s)
                            if (answer)
                            window.location="'.URL_PUBLIC.'/tarabackend/page/delete/"+id;
                        }
             </script>';
        //--------------------------------@header para a tabela (javascript)----------------
    if(!isset($_GET['it']))
    {
     $form->getFormulario();
    //------------------------------html-------------------------------------------------
        
     $html='
         <div id=innerModulos>
    <h3 id="catTitulo">'.$form->formulario->title.'</h3>
        <table id="listagem" cellpadding="0" cellspacing="0" border="0" class="display">
                <thead>
                    <tr>
                       '.$form->printCamposLabel().'
                    </tr>
                </thead>
                <tbody>

                    '.$form->printCampos().'
                 </tbody>
         </table>
         </div>
         ';
     //------------------------------@html-------------------------------------------------

     echo($headerApp);
     }
    else
    {
        $page = $form->getPageById($_GET['it']);
        $html='
     <div id=innerModulos>
        <h3 id="catTitulo">'.$page->title.'</h3>
            <table id="listagem" cellpadding="0" cellspacing="0" border="0" class="display">
                    <thead>
                        <tr>
                           '.$form->printPaCamposLabel().'
                        </tr>
                    </thead>
                    <tbody>

                        '.$form->printPaCampos($page).'
                     </tbody>
             </table>
       </div>
             ';
         echo($headerApp);
    }
}

    echo($html);
?>

