<h1><?php echo($atributo->description); ?></h1>
<!-- <h3 id="editPageH">Adicionar novo</h3> -->
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Valor:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="atributoInput">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick="addAtributoValor('<? echo(URL_PUBLIC); ?>', <? echo($atributo->id); ?>)">  salvar </button>
        </div>
    </div>
</div>