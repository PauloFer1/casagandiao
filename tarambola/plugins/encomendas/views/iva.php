<h1><?php echo __('IVA'); ?></h1>
<!-- <h3 id="editPageH">Adicionar novo</h3> -->
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor de IVA:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="<? echo($vat->value); ?>" name="nome_user" class="page-part-forms-part-text" id="ivaInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Utilizar preço s/IVA:</label>
                <div class="page-part-forms-input">
                    <input value="1" type="checkbox" <? echo(($vat->use==1)?'checked':''); ?> id="useInput"></input>    
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick="saveIVA('<? echo(URL_PUBLIC); ?>')">  salvar </button>
        </div>
    </div>
</div>
