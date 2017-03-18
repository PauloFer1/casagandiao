<?php
    require_once(SERVER_URL.'tarambola/plugins/configuracoes/Configuracoes.php');
    
    $url = explode("/",($_SERVER["REQUEST_URI"])); 
    $request = $url[sizeof($url)-1];
    
    $conf = new Configuracoes();
?>

<!-- //******************************** Configuração de conta de envio *****************************************/ --> 
  <?php  if($request=='sender'): ?>

        <h1><?php echo __('Configuração de conta de servidor para o envio de email'); ?></h1>
        <?php error_reporting(E_ALL); ?>
        <?php $sender = $conf->getConf('sender');  ?>
         <h3 id="editPageH">Editar</h3>
            <div class="form-area">
                <div id="Page-part-forms-Form">
                    <div class="page-part-forms-row">
                            <label for="server" class="page-part-forms-label">Servidor:</label>
                            <div class="page-part-forms-input">
                                <input type="text" maxlength="255" name="server" class="page-part-forms-part-text" id="server" value="<?php echo($sender[0]->campo); ?>">        
                            </div> 
                    </div>
                    <div class="page-part-forms-row">
                            <label for="conta" class="page-part-forms-label">Conta:</label>
                            <div class="page-part-forms-input">
                                <input type="text" maxlength="255" name="conta" class="page-part-forms-part-text" id="conta" value='<?php echo($sender[0]->campo2); ?>'>        
                            </div> 
                    </div>
                    <div class="page-part-forms-row">
                            <label for="password" class="page-part-forms-label">Password:</label>
                            <div class="page-part-forms-input">
                                <input type="text" maxlength="255" name="password" class="page-part-forms-part-text" id="password" value='<?php echo($sender[0]->campo3); ?>'>        
                            </div> 
                    </div>
                    <div class="page-part-forms-row">
                        <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                        <button class="saveFrontUserBtn" onclick='updateContaServer("<?php echo(URL_PUBLIC); ?>")'>  salvar </button>
                    </div>
                </div>
            </div>

<!-- //******************************** Configuração email a enviar ******************************************// -->
    <?php elseif($request=='email'): ?>

        <h1><?php echo __('Configuração de email a receber'); ?></h1>
        <?php error_reporting(E_ALL); ?>
        
        <?php $sender = $conf->getConf('email');  ?>
         <h3 id="editPageH">Editar</h3>
            <div class="form-area">
                <div id="Page-part-forms-Form">
                    <div class="page-part-forms-row">
                            <label for="email" class="page-part-forms-label">Email:</label>
                            <div class="page-part-forms-input">
                                <input type="text" maxlength="255" name="email" class="page-part-forms-part-text" id="email" value='<?php echo($sender[0]->campo2); ?>'>        
                            </div> 
                    </div>
                    <div class="page-part-forms-row">
                        <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                        <button class="saveFrontUserBtn" onclick='updateEmail("<?php echo(URL_PUBLIC); ?>")'>  salvar </button>
                    </div>
                </div>
            </div>
        
    <?php endif; ?>
    <!-- //******************************** Configuração PAYPAL *****************************************/ --> 
  <?php  if($request=='paypal'): ?>

        <h1><?php echo __('Configuração de credenciais PayPal'); ?></h1>
        <?php error_reporting(E_ALL); ?>
        <?php $sender = $conf->getConf('paypal');  ?>
         <h3 id="editPageH">Editar</h3>
            <div class="form-area">
                <div id="Page-part-forms-Form">
                    <div class="page-part-forms-row">
                            <label for="client_id" class="page-part-forms-label">Client ID:</label>
                            <div class="page-part-forms-input">
                                <input type="text" maxlength="255" name="client_id" class="page-part-forms-part-text" id="client_id" value="<?php echo($sender[0]->campo); ?>">        
                            </div> 
                    </div>
                    <div class="page-part-forms-row">
                            <label for="client_secret" class="page-part-forms-label">Client Secret:</label>
                            <div class="page-part-forms-input">
                                <input type="text" maxlength="255" name="client_secret" class="page-part-forms-part-text" id="client_secret" value='<?php echo($sender[0]->campo2); ?>'>        
                            </div> 
                    </div>
                    <div class="page-part-forms-row">
                        <label for="Page-part-forms-Page-Part-titulo-en" class="page-part-forms-label">Guardar:</label>
                        <button class="saveFrontUserBtn" onclick='updatePayPal("<?php echo(URL_PUBLIC); ?>")'>  salvar </button>
                    </div>
                </div>
            </div>
<?php endif; ?>
<!-- //******************************** Configuração PAYPAL ******************************************// -->
