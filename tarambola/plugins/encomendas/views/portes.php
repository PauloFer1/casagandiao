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
<h1><?php echo __('Portes'); ?></h1>
<table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                <span>CÓDIGO</span>
                            </th>
                            <th>
                              <span>PAÍS</span>
                            </th>
                            <th>
                               <span>VALOR</span>
                            </th>
                            <th>
                                <span>UNIDADE</span>
                            </th>
                            <th>
                                <span>VALOR UNIDADE</span>
                            </th>
                            <th>
                                <span>DESCRIÇÃO UNIDADE</span>
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
                            <? foreach ($portes as $po): ?>
                            <tr class="listagemLinha">
                                <td>
                                    <span><? echo($po->codigo); ?></span>
                                </td>
                                  <td>
                                    <span><? echo($po->pais); ?></span>
                                </td>
                                  <td>
                                    <span><? echo($po->valor); ?></span>
                                </td>
                                <td>
                                    <span><? echo($po->unidade); ?></span>
                                </td>
                                  <td>
                                    <span><? echo($po->valor_unidade); ?></span>
                                </td>
                                <td>
                                    <span><? echo($po->descricao_unidade); ?></span>
                                </td>
                                <td class="optList">
                                     <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/view_portes/<? echo($po->id); ?>" ><img src="<? echo(URL_PUBLIC . ADMIN_DIR); ?>/images/edit.png" alt="edit"/></a>
                                </td>
                                <td class="optList">
                                     <a href="javascript:confirmDelPortes(<? echo($po->id); ?>, '<? echo(URL_PUBLIC); ?>')"><img src="<? echo(URL_PUBLIC . ADMIN_DIR);?>/images/delete_icon.png" alt="X"/></a>
                               </td>
                            </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>