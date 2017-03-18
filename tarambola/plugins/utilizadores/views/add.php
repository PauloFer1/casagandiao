<h1><?php echo __('Front-end Users'); ?></h1>
<h3 id="editPageH">Adicionar novo</h3>
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nome:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="nome_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Username:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="username_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Morada:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="morada_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Cidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="cidade_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Código postal:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="cod_postal_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Telefone:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="telefone_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Email:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="email_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nif:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="nif_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Password:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" value="" name="nome_user" class="page-part-forms-part-text" id="password_user">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Observações:</label>
                <div class="page-part-forms-input">
                    <textarea name="obs_user" class="page-part-forms-part-text" id="password_user"></textarea>  
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick="insertFrontUser('<? echo(URL_PUBLIC); ?>')">  salvar </button>
        </div>
    </div>
</div>