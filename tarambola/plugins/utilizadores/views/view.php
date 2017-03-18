<h1><?php echo __('Front-end Users'); ?></h1>
<?php
require_once(SERVER_URL . 'tarambola/app/classes/User_Plug.php');

    $url = explode("/",($_SERVER["REQUEST_URI"])); 
    $userId = $url[sizeof($url)-1];
    $user = User_Plug::getUserById($userId); 
    $userDocs = User_Plug::getDocsByUser($userId);
    $docs = User_Plug::getDocs();
?>
<h3 id="editPageH">Editar</h3>
<div class="form-area">
    <div id="Page-part-forms-Form">
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nome:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nome_user" value="<? echo($user->nome); ?>">        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Username:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="username_user" value='<? echo($user->username); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Morada:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="morada_user" value='<? echo($user->morada); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Cidade:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="cidade_user" value='<? echo($user->cidade); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Código postal:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="cod_postal_user" value='<? echo($user->cod_postal); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Telefone:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="telefone_user" value='<? echo($user->telefone); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Email:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="email_user" value='<? echo($user->email); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Nif:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="nif_user" value='<? echo($user->nif); ?>'>        
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Password:</label>
                <div class="page-part-forms-input">
                    <input type="text" maxlength="255" name="nome_user" class="page-part-forms-part-text" id="password_user" value=''>    
                </div> 
        </div>
        
        <div class="assocFormDiv2" id="" >
            <label class="boxTitle">Documentos associados</label>
                        <div class="selectContainer">
                            <span>Documentos</span>
                            <select id="assocDoc" multiple>
                                <? foreach($docs as $u): ?> 
                                    <option value="<? echo($u->id); ?>"><? echo($u->id.'-'.$u->nome); ?> </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                        <div class="inOutContainer">
                            <button class="inButton" onclick="selectDoc()">&raquo;</button>
                            <button class="outButton" onclick="unselectDoc()">&laquo;</button>
                        </div>
                        <div class="selectContainer">
                            <span>Associados</span>
                            <select id="2assocDoc" multiple>
                                <? foreach ($userDocs as $d): ?>
                                    <option class="assocDocOptions2" value='<? echo($d->id);  ?>' class="unsaved"><? echo($d->id.'-'.$d->nome); ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Observações:</label>
                <div class="page-part-forms-input">
                    <textarea name="obs_user" class="page-part-forms-part-text" id="obs_user"><? echo($user->observacoes); ?></textarea>  
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Estado:</label>
                <div class="page-part-forms-input">
                    <select id="register_user">
                        <option value="0" <? echo(($user->registo==0)?'selected="selected"':''); ?> >Não confirmado</option>
                        <option value="1" <? echo(($user->registo==1)?'selected="selected"':''); ?> >Confirmado</option>
                    </select>      
                    <button id="confirmacaoBtn" onclick="sendEmail(<? echo($userId); ?>, '<? echo(URL_PUBLIC); ?>', '<? echo($user->nome); ?>', '<? echo($user->email); ?>');">Confirmar (Enviar email)</button>
                </div> 
        </div>
        <div class="page-part-forms-row">
                <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                <button class="saveFrontUserBtn" onclick='updateFrontUser(<? echo($userId); ?>, "<? echo(URL_PUBLIC); ?>", <? echo(json_encode($userDocs)); ?>)'>  salvar </button>
        </div>
    </div>
</div>