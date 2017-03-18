<h1><?php echo __('Editar Unidade máxima'); ?></h1>
<!-- <h3 id="editPageH">Adicionar novo</h3> -->
<div class="form-area">
    <input type="hidden" maxlength="255" value="<? echo($portesId); ?>" id="portesId">        
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Unidade máxima:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->unidade_max); ?>" name="nome_user" class="page-part-forms-part-text" id="unidadeMaxInput">        
                </div> 
        </div>
         <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($portes->valor); ?>" name="nome_user" class="page-part-forms-part-text" id="valorInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick="updatePortesAssoc(<? echo($portes->id); ?>,'<? echo(URL_PUBLIC); ?>')">  salvar </button>
        </div>
    </div>
</div>