<?php
require_once(SERVER_URL.'tarambola/plugins/paginas/Paginas.php');
?>
<h1><?php echo __('Pages'); ?></h1>
<?php error_reporting(E_ALL); ?>

<?php
$_SESSION['pagina'] = $_SERVER['REQUEST_URI'];
$pag = new Paginas();

if(!isset($_GET['pagina']))
{
    
        $html='
            <div id=innerModulos>
                <ul id=listaModulos>
                    '.$pag->getListPag().'
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
     $pag->getPagina();
    //------------------------------html-------------------------------------------------
        
     $html='
         <div id=innerModulos>
    <h3 id="catTitulo">'.$pag->pagina->title.'</h3>
        <table id="listagem" cellpadding="0" cellspacing="0" border="0" class="display">
                <thead>
                    <tr>
                       '.$pag->printCamposLabel().'
                    </tr>
                </thead>
                <tbody>

                    '.$pag->printCampos().'
                 </tbody>
         </table>
         </div>
         ';
     //------------------------------@html-------------------------------------------------

     echo($headerApp);
     }
    else
    {
        $page = $pag->getPageById($_GET['it']);
        $html='
     <div id=innerModulos>
        <h3 id="catTitulo">'.$page->title.'</h3>
            <table id="listagem" cellpadding="0" cellspacing="0" border="0" class="display">
                    <thead>
                        <tr>
                           '.$pag->printPaCamposLabel().'
                        </tr>
                    </thead>
                    <tbody>

                        '.$pag->printPaCampos($page).'
                     </tbody>
             </table>
       </div>
             ';
         echo($headerApp);
    }
}

    echo($html);
?>

