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
                                    //$('#listagem').dataTable({"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]});
                                    $('#listagem').dataTable( {
                                            "bSort" :   false
                                    });
                            } );
            </script>
<h1><?php echo __('Ver Encomendas'); ?></h1>
<table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                <span>ID</span>
                            </th>
                            <th>
                              <span>CLIENTE</span>
                            </th>
                            <th>
                               <span>DATA</span>
                            </th>
                            <th>
                                <span>VALOR</span>
                            </th>
                            <th class="optList">
                             <span>VER</span>
                            </th>
                            <th class="optList">
                                <span>ELIMINAR</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <? foreach ($encomendas as $enc): ?>
                            <tr class="listagemLinha">
                                <td>
                                    <span><? echo($enc->id); ?></span>
                                </td>
                                  <td>
                                    <span><? echo($enc->invoice_name); ?></span>
                                </td>
                                  <td>
                                    <span><? echo($enc->date); ?></span>
                                </td>
                                  <td>
                                    <span><? echo($enc->value_shipping); ?></span>
                                </td>
                                <td class="optList">
                                     <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/view_encomenda/<? echo($enc->id); ?>" ><img src="<? echo(URL_PUBLIC . ADMIN_DIR); ?>/images/edit.png" alt="edit"/></a>
                                </td>
                                <td class="optList">
                                     <a href="javascript:confirmDelEncomenda(<? echo($enc->id); ?>, '<? echo(URL_PUBLIC); ?>')"><img src="<? echo(URL_PUBLIC . ADMIN_DIR);?>/images/delete_icon.png" alt="X"/></a>
                               </td>
                            </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>