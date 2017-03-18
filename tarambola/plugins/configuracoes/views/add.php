<h1><?php echo __('Configurar Mensagens registo Utilizadores'); ?></h1>
<h3 id="editPageH">Adicionar</h3>
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Lang:</label>
                <div class="page-part-forms-input">
                    <select id="lang_Select">
                        <option value="pt" >PT</option>
                        <option value="en" >EN</option>
                        <option value="fr" >FR</option>
                        <option value="es" >ES</option>
                    </select>   
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Função:</label>
                <div class="page-part-forms-input">
                    <select id="funcao_Select">
                        <option value="registo" >Registo</option>
                        <option value="confirmação" >Confirmação</option>
                    </select>     
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Mensagem:</label>
                <div class="page-part-forms-row_page_part">
                    <div class="page-part-forms-input">
                        <div class="page-part-forms-part-page-part">
                            <textarea  id="mensagem_Text" cols="40" rows="20" style="width: 100%; display: block;" name="mensagem"  class="textarea"></textarea>
                        </div>
                    </div> <!-- INPUT -->
                </div>
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick='insertMensagem( "<? echo(URL_PUBLIC); ?>");'>  salvar </button>
        </div>
    </div>
</div>
<script type="text/javascript">
//tinymce.init({selector:'textarea'});
</script>
