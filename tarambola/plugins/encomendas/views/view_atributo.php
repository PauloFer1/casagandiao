<style type="text/css" title="currentStyle">
                            @import "<? echo(URL_PUBLIC); ?>/tarabackend/stylesheets/demo_table.css";
            </style>  
            <style type="text/css" title="currentStyle">
                            @import "' . URL_PUBLIC . '/tarabackend/stylesheets/demo_table.css";
            </style>
            <script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC);?>/tarabackend/javascripts/jquery.js"></script>
            <script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC ); ?>/tarabackend/javascripts/jquery.dataTables.js"></script>
            <script type="text/javascript" charset="utf-8">
                            $(document).ready(function() {
                                    $('#listagem').dataTable( );
                            } );
            </script>
<h1><?php echo("Atributo"); ?></h1>
<table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                <span>DESCRIÇÃO</span>
                            </th>
                            <th>
                             <span>VER</span>
                            </th>
                            <th class="optList">
                                <span>ELIMINAR</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <? foreach ($valores as $val): ?>
                            <tr class="listagemLinha">
                                <td>
                                    <span><? echo($val->descricao); ?></span>
                                </td>
                                <td class="optList">
                                     <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/edit_atributo/<? echo($val->id); ?>" ><img src="<? echo(URL_PUBLIC . ADMIN_DIR); ?>/images/edit.png" alt="edit"/></a>
                                </td>
                                <td class="optList">
                                     <a href="javascript:confirmDel(<? echo($val->id); ?>, '<? echo(URL_PUBLIC); ?>')"><img src="<? echo(URL_PUBLIC . ADMIN_DIR);?>/images/delete_icon.png" alt="X"/></a>
                               </td>
                            </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>
</br>
