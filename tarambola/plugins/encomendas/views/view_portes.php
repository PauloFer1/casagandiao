<h1><?php echo __('Adicionar portes'); ?></h1>
<!-- <h3 id="editPageH">Adicionar novo</h3> -->
<div class="form-area">
    <input type="hidden" maxlength="255" value="<? echo($portes->id); ?>" name="id" id="idInput">        
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Código:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->codigo); ?>" name="nome_user" class="page-part-forms-part-text" id="codigoInput">        
                </div> 
        </div>
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">País:</label>
                <div class="page-part-forms-input">
                     <select id="paisInput">
                         <option value="NANDEF">Não definido</option>
                         <? for($i=0; $i<sizeof($paises); $i++): ?>
                        <option value="<? echo($paises[$i]->codigo); ?>" <? echo(($paises[$i]->codigo==$portes->pais)?'selected="selected"':''); ?> ><? echo($paises[$i]->descricao); ?></option>
                        <? endfor; ?>
                    </select>    
                    <!-- <input type="text" maxlength="255" value="<? echo($portes->pais); ?>" name="nome_user" class="page-part-forms-part-text" id="paisInput">        -->
                </div> 
        </div>
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor Fixo:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->valor); ?>" name="nome_user" class="page-part-forms-part-text" id="valorInput">        
                </div> 
        </div>
        <!--
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Unidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->unidade); ?>" name="nome_user" class="page-part-forms-part-text" id="unidadeInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor por Unidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->valor_unidade); ?>" name="nome_user" class="page-part-forms-part-text" id="valorUnidadeInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Descrição Unidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->descricao_unidade); ?>" name="nome_user" class="page-part-forms-part-text" id="descricaoUnidadeInput">        
                </div> 
        </div>
        -->
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor para portes grátis:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->valor_gratis); ?>" name="nome_user" class="page-part-forms-part-text" id="valorGratisInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick="savePortes('<? echo(URL_PUBLIC); ?>')">  salvar </button>
        </div>
    </div>
</div>

<style type="text/css" title="currentStyle">
                            @import "<? echo(URL_PUBLIC); ?>tarabackend/stylesheets/demo_table.css";
            </style>  
            <style type="text/css" title="currentStyle">
                            @import "<? echo(URL_PUBLIC);?>tarabackend/stylesheets/demo_table.css";
            </style>
            <script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC);?>/tarabackend/javascripts/jquery.js"></script>
            <script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC ); ?>/tarabackend/javascripts/jquery.dataTables.js"></script>
            <script type="text/javascript" charset="utf-8">
                            $(document).ready(function() {
                                    $('#listagem').dataTable({"bSort": false});
                            } );
            </script>
<h1><?php echo __('Associações'); ?></h1>
<table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                <span>UNIDADE MÁXIMA</span>
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
                            <? foreach ($assocs as $po): ?>
                            <tr class="listagemLinha">
                                  <td>
                                    <span><? echo($po->unidade_max); ?></span>
                                </td>
                                <td>
                                    <span><? echo($po->valor); ?></span>
                                </td>
                                <td class="optList">
                                     <a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/edit_portes_assoc/<? echo($po->id); ?>" ><img src="<? echo(URL_PUBLIC . ADMIN_DIR); ?>/images/edit.png" alt="edit"/></a>
                                </td>
                                <td class="optList">
                                     <a href="javascript:confirmDelPortesAssoc(<? echo($po->id); ?>, '<? echo(URL_PUBLIC); ?>')"><img src="<? echo(URL_PUBLIC . ADMIN_DIR);?>/images/delete_icon.png" alt="X"/></a>
                               </td>
                            </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>