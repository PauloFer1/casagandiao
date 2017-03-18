<h1><?php echo __('Adicionar portes'); ?></h1>
<!-- <h3 id="editPageH">Adicionar novo</h3> -->
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Código:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="codigoInput">        
                </div> 
        </div>
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">País:</label>
                <div class="page-part-forms-input">
                    <select id="paisInput">
                        <option value="NANDEF">Não definido</option>
                        <? for($i=0; $i<sizeof($paises); $i++): ?>
                            <option value="<? echo($paises[$i]->codigo); ?>"  ><? echo($paises[$i]->descricao); ?></option>
                        <? endfor; ?>
                    </select>  
                    <!-- <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="paisInput">        -->
                </div> 
        </div>
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="valorInput">        
                </div> 
        </div>
        <!--
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Unidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="unidadeInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor por Unidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="valorUnidadeInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Descrição Unidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="descricaoUnidadeInput">        
                </div> 
        </div>
        -->
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor para portes grátis:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="valorGratisInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick="insertPortes('<? echo(URL_PUBLIC); ?>')">  salvar </button>
        </div>
    </div>
</div>