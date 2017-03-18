<? require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php'; ?>
<style type="text/css" title="currentStyle">
    @import "<? echo(URL_PUBLIC); ?>/tarabackend/stylesheets/demo_table.css";
</style>
<script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC); ?>/tarabackend/javascripts/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC); ?>/tarabackend/javascripts/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    $('#listagem').dataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": false,
        "bAutoWidth": false
    });
} );
</script>
<h1><?php echo __('Encomendas'); ?></h1>

<div class="form-area form-encomenda">
    <div id="Page-part-forms-Form">
        <div class="produtos-encomenda">
         <table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                ARTIGO
                            </th>
                            <th>
                                REFERÊNCIA
                            </th>
                            <th>
                               PREÇO
                            </th>
                            <th>
                                QUANTIDADE
                            </th>
                            <th>
                                MEDIDA
                            </th>
                            <th>
                                DESCONTO
                            </th>
                            <th>
                                IVA
                            </th>
                            <th>
                                TOTAL
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <? $totalFinal = 0; ?>
                        <? foreach($produtos as $prod): ?>
                        <? $at = Encomendas::getAtributosByProduto($prod->id); ?>
                        <tr class="listagemLinha">
                            <td><? echo($prod->title.' '); ?>
                                <? for($k=0; $k<sizeof($at); $k++): ?>
                                    <? echo($at[$k]->description.':'.$at[$k]->descricao); ?>
                                <? endfor;?>
                            </td>
                            <td><? echo($prod->reference); ?></td>
                            <?
                                if($useIva)
                                    $valorUnidade = $prod->value_unity-($prod->value_unity*$prod->vat_val);
                                else
                                    $valorUnidade = $prod->value_unity;
                            ?>
                            <td><? echo(money_format('%.2n', $valorUnidade)); ?></td>
                            <td><? echo($prod->qtd); ?></td>
                            <td><? echo($prod->qtd_description); ?></td>
                            <td><? echo($prod->discount_total); ?></td>
                            <td><? echo($prod->vat_val); ?></td>
                            <td><? 
                            if($useIva)
                            {
                                $total = ($prod->qtd)*(($valorUnidade+($prod->value_unity*$prod->vat_val)))-$prod->discount_total;
                                $totalFinal+=$total;
                            }
                            else
                            {
                                $total = ($prod->qtd)*($valorUnidade)-$prod->discount_total;
                                $totalFinal+=$total; 
                            }
                            echo(money_format('%.2n', $total)); ?></td>
                        </tr>
                        <? endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                             <th></th>
                            <th>
                               TOTAL
                            </th>
                            <th>
                               <? echo(money_format('%.2n', $totalFinal)); ?>
                            </th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                             <th></th>
                            <th>
                              PORTES
                            </th>
                            <th>
                               <? echo(money_format('%.2n', $encomenda->shipping_costs)); ?>
                            </th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                             <th></th>
                            <th>
                               TOTAL ENCOMENDA
                            </th>
                            <th>
                               <? echo(money_format('%.2n', $totalFinal+$encomenda->shipping_costs)); ?>
                            </th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                             <th></th>
                            <th>
                               MÉTODO PAGAMENTO
                            </th>
                            <th>
                               <? echo($metodoPagamento->descricao); ?>
                            </th>
                        </tr>
                    </tfoot>
         </table>
     </div>
        <div id="dados-encomenda"> 
            <!--
        <div class="page-part-forms-row">
                <span class="campos-label-encomenda">Dados de envio</span>
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nome:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_name); ?>">        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Telefone:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_phone); ?>">        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Email:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_email); ?>">        
                </div> 
        </div>
            -->
        <div class="page-part-forms-row">
                <span class="campos-label-encomenda">Dados faturação</span>
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nome:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->invoice_name); ?>">        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nif:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->invoice_nif); ?>">        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Morada:</label>
                <div class="page-part-forms-input input-encomenda">
                    <textarea class="textarea-encomenda" readonly><? echo($encomenda->invoice_address); ?></textarea>        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Cidade:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->invoice_city); ?>">        
                </div> 
                 <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Código Postal:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->invoice_postal); ?>">        
                </div> 
                  <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">País:</label>
                 <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->invoice_country); ?>">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <span class="campos-label-encomenda">Dados envio</span>
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nome:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_name); ?>">        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Morada:</label>
                <div class="page-part-forms-input input-encomenda">
                    <textarea class="textarea-encomenda" readonly><? echo($encomenda->shipping_address); ?></textarea>        
                </div> 
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Cidade:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_city); ?>">        
                </div> 
                 <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Código Postal:</label>
                <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_postal); ?>">        
                </div> 
                  <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">País:</label>
                 <div class="page-part-forms-input input-encomenda">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" readonly value="<? echo($encomenda->shipping_country); ?>">        
                </div> 
        </div>
        </div>
        <div id="observacoes-encomenda">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Observações de encomenda:</label>
                <div class="page-part-forms-input input-encomenda">
                    <textarea class="textarea-encomenda" readonly><? echo($encomenda->observacoes); ?></textarea>        
                </div> 
        </div>
            <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Estado:</label>
                <div class="styled-select">
                    <select id="encomenda-state">
                        <? foreach($estados as $est): ?>
                        <option value="<? echo($est->id); ?>" <? echo(($encomenda->estado==$est->id)?'selected="selected"':''); ?> ><? echo($est->descricao); ?></option>
                        <? endforeach; ?>
                    </select>      
                </div>
                    <button id="confirmacaoBtn" onclick="saveState('<? echo(URL_PUBLIC); ?>', <? echo($encomenda->id); ?>)">Guardar</button>
                </div> 
                
        </div>
    </div>
</div>