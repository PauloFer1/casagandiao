<?php
require_once(SERVER_URL.'tarambola/plugins/configuracoes/Configuracoes.php');
?>
<h1><?php echo __('Configurações'); ?></h1>
<?php error_reporting(E_ALL); ?>

<?php
$_SESSION['pagina'] = $_SERVER['REQUEST_URI'];
$conf = new Configuracoes();

?>
<?php if(!isset ($_GET['conf'])) : ?>
    <?php $confs=$conf->getConf(); ?>
        <div id="innerConfiguracoes">
            <ul id="listaModulos">
                <li>
                    <a class="newBigBtn" title="Configurar Conta de envio de Emails" href="<?php echo(URL_PUBLIC); ?>tarabackend/plugin/configuracoes/edit/sender"><?php echo __('Configurar Conta de envio de Emails'); ?></a>
                </li>
                <li>
                    <a class="newBigBtn" title="Configurar Email de Receção" href="<?php echo(URL_PUBLIC); ?>tarabackend/plugin/configuracoes/edit/email"><?php echo __('Configurar Email de Receção'); ?></a>
                </li>
                <li>
                    <a class="newBigBtn" title="Configurar Mensagens Email" href="<?php echo(URL_PUBLIC); ?>tarabackend/plugin/configuracoes?conf=registo-msg"><?php echo __('Configurar Mensagens Email');?></a>
                </li>
                 <li>
                </li>
                <li>
                    <a class="newBigBtn" title="Credenciais PayPal" href="<?php echo(URL_PUBLIC); ?>tarabackend/plugin/configuracoes/edit/paypal"><?php echo __('Credenciais PayPal');?></a>
                </li>
                 <li>
                    <a class="newBigBtn" title="Apagar imagens não utilizadas" href="<?php echo(URL_PUBLIC); ?>tarabackend/plugin/configuracoes?conf=delete-imgs"><?php echo __('Delete unused images');?></a>
                </li>
            </ul>
        </div>
    <?php else : ?>
    <?php
        $confId= $_GET['conf'];
    ?>
<?php if ($confId =="email") : ?>
<?php $mails=$conf->getConf("email"); ?>
<script type="text/javascript">
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");

		var email = $("#email"),
			id = $("#id"),
			allFields = $([]).add(email).add(id),
			tips = $(".validateTips");

		function updateTips(t) {
			tips
				.text(t)
				.addClass('ui-state-highlight');
			setTimeout(function() {
				tips.removeClass('ui-state-highlight', 1500);
			}, 500);
		}

		function checkLength(o,n,min,max) {

			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Tamanho de " + n + " tem de estar entre "+min+" e "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}
	});
	</script>
<div id="innerConfiguracoes">
    <ul id="listaModulos">
        <?php foreach ($mails as $m) :?>
        <li>
            <a id="insertMail<?php echo($m->id); ?>" title="<?php echo($m->nome) ?>"><?php echo($m->nome) ?></a>
            <div id="dialog-form<?php echo($m->id); ?>" title="Email de Envio de Contactos">
                    <p class="validateTips"></p>

                    <form>
                            <input type="hidden" name="id" id="id" value="<?php echo($m->id) ?>" class=""/>
                            <label for="email">Email:</label>
                            <span id="inputC">
                            <input name="email" id="email" value="<?php echo($m->campo) ?>" class="text ui-widget-content ui-corner-all" />
                            </span>
                    </form>
            </div>
        </li>
        <script type="text/javascript">
            function setDialog(id)
            {
		$("#dialog-form"+id).dialog({
			autoOpen: false,
			width: 350,
                        modal: true,
			buttons: {
				'Guardar Mail': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					//bValid = bValid && checkLength(name,"username",3,16);
					bValid = bValid && checkLength(email,"email",6,80);
					//bValid = bValid && checkLength(password,"password",5,16);

					//bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Username may consist of a-z, 0-9, underscores, begin with a letter.");
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					//bValid = bValid && checkRegexp(password,/^([0-9a-zA-Z])+$/,"Password field only allow : a-z 0-9");

					if (bValid) {
						/*$('#users tbody').append('<tr>' +
							'<td>' + name.val() + '</td>' +
							'<td>' + email.val() + '</td>' +
							'<td>' + password.val() + '</td>' +
							'</tr>');*/
                                                    $.ajax({
                                                           type: "POST",
                                                           url: "<?php echo(URL_PUBLIC.'tarambola/plugins/configuracoes/save_mail.php') ?>",
                                                           data: "campo="+email.val()+"&id=" + id.val(),
                                                           success: function(msg){
                                                             alert( "Data Saved: " + msg );
                                                           }
                                                         });
						$(this).dialog('close');
					}
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
                $('#insertMail'+id)
			.button()
			.click(function() {
				$('#dialog-form'+id).dialog('open');
			});
                        }
        setDialog(<?php echo($m->id); ?>);
	</script>
        <?php endforeach ?>
    </ul>
</div>

<?php endif ?>

<?php if ($confId =="analytics") : ?>
<?php $mails=$conf->getConf("analytics"); ?>
    <script type="text/javascript">
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");

		var email = $("#email"),
                        password = $("password"),
			id = $("#id"),
			allFields = $([]).add(email).add(id),
			tips = $(".validateTips");

		function updateTips(t) {
			tips
				.text(t)
				.addClass('ui-state-highlight');
			setTimeout(function() {
				tips.removeClass('ui-state-highlight', 1500);
			}, 500);
		}

		function checkLength(o,n,min,max) {

			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Tamanho de " + n + " tem de estar entre "+min+" e "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}

	});
	</script>
<div id="innerConfiguracoes">
    <ul id="listaModulos">
        <?php foreach ($mails as $m) :?>
    <?php $campos=$conf->separaCampo($m->campo); ?>
        <li>
            <a id="insertMail<?php echo($m->id); ?>" title="<?php echo($m->nome) ?>"><?php echo($m->nome) ?></a>
            <div id="dialog-form<?php echo($m->id); ?>" title="Email de Envio de Contactos">
                    <p class="validateTips"></p>

                    <form>
                            <input type="hidden" name="id" id="id" value="<?php echo($m->id) ?>" class=""/>
                            <label for="email">Email:</label>
                            <input name="email" id="email" value="<?php echo($campos[0]) ?>" class="text ui-widget-content ui-corner-all" />
                            <label for="email">Password:</label>
                            <input name="password" id="password" value="<?php echo($campos[1]) ?>" class="text ui-widget-content ui-corner-all" />
                    </form>
            </div>
        </li>
        <script type="text/javascript">
            setDialog(<?php echo($m->id); ?>);
            function setDialog(id)
{
		$("#dialog-form"+id).dialog({
			autoOpen: false,
			width: 350,
                        modal: true,
			buttons: {
				'Guardar Mail': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					//bValid = bValid && checkLength(name,"username",3,16);
					bValid = bValid && checkLength(email,"email",6,80);
					//bValid = bValid && checkLength(password,"password",5,16);

					//bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Username may consist of a-z, 0-9, underscores, begin with a letter.");
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					//bValid = bValid && checkRegexp(password,/^([0-9a-zA-Z])+$/,"Password field only allow : a-z 0-9");

					if (bValid) {
						/*$('#users tbody').append('<tr>' +
							'<td>' + name.val() + '</td>' +
							'<td>' + email.val() + '</td>' +
							'<td>' + password.val() + '</td>' +
							'</tr>');*/
                                                    $.ajax({
                                                           type: "POST",
                                                           url: "<?php echo(URL_PUBLIC.'tarambola/plugins/configuracoes/save_mail.php') ?>",
                                                           data: "campo="+email.val()+"&id=" + id.val(),
                                                           success: function(msg){
                                                             alert( "Data Saved: " + msg );
                                                           }
                                                         });
						$(this).dialog('close');
					}
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
                $('#insertMail'+id)
			.button()
			.click(function() {
				$('#dialog-form'+id).dialog('open');
			});
                        }

	</script>
        <?php endforeach ?>
    </ul>
</div>
<?php endif?> 
    <?php  $confId= $_GET['conf']; if ($confId =="delete-imgs") : ?>
        <h3><? echo __('Delete unused images');?></h3>
            <?
                $dir = opendir(SERVER_URL."public/images/");
                while($entryName = readdir($dir) ) 
                {
                        $dirArray[] = $entryName;
                }
                closedir($dir);

                    $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
                if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
                    $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                    Record::connection($__tarambola_CONN__);
                    Record::getConnection()->exec("set names 'utf8'");
        
                    $count=0;
                    $imgs=0;
                foreach($dirArray as $file)
                {
                    $stm = Record::query("Select count(*) as num from tara_page_part where content_html like '%".$file."%'");
                    $result = $stm->fetchAll();
                    if($file==".." || $file==".DS_Store" || $file==" " || $file=="" || $file==".")
                    {
                        unset($dirArray[$count]);
                        $count++;
                    }
                    else
                    {
                        $count++;
                        if($result[0]['num']==0)
                        {
                            echo("<br>".$file."</br>");
                            unlink(SERVER_URL."public/images/".$file);
                            $imgs++;
                        }
                        
                    }
                }
                    echo("<h4 style='float:left; color:#666;'>".$imgs." Imagens Apagadas</h4>");
            ?>

    <?php endif ?>
    <?php if ($confId =="registo-msg") : ?>
        <h1><?php echo __('Configurar Mensagens registo Utilizadores'); ?></h1>
        <?
             $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
             if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
                $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
             
             Record::connection($__tarambola_CONN__);
             Record::getConnection()->exec("set names 'utf8'");
             
             $sql = "SELECT * FROM tara_front_users_messages";
             $result = Record::query($sql);
             $result = $result->fetchAll();
        ?>
        <style type="text/css" title="currentStyle">
                            @import "<?echo(URL_PUBLIC); ?>/tarabackend/stylesheets/demo_table.css";
            </style>  
            <style type="text/css" title="currentStyle">
                            @import "<? echo(URL_PUBLIC); ?>/tarabackend/stylesheets/demo_table.css";
            </style>
            <script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC); ?>/tarabackend/javascripts/jquery.js"></script>
            <script type="text/javascript" language="javascript" src="<? echo(URL_PUBLIC); ?>/tarabackend/javascripts/jquery.dataTables.js"></script>
            <script type="text/javascript" charset="utf-8">
                            $(document).ready(function() {
                                    $('#listagem').dataTable( );
                            } );
            </script>
            <div id=innerModulos>
                <table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th><span>Lang</span></th>
                                <th><span>Função</span></th>
                                <th><span>Texto</span></th>
                                <th><span>Editar</span></th>
                                <th><span>Apagar</span></th>
                            </tr>
                            </thead>
                            <tbody>
                                <? for($i=0; $i<sizeof($result); $i++): ?>
                                <tr class="listagemLinha">
                                    <td><p><? echo($result[$i]['lang']); ?></p></td>
                                    <td><p><? echo($result[$i]['funcao']); ?></p></td>
                                     <td><p><? echo($result[$i]['mensagem']); ?></p></td>
                                     <td><a href="<? echo(URL_PUBLIC); ?>/tarabackend/plugin/configuracoes/view/<? echo($result[$i]['id'] );?>"><img src="<? echo(URL_PUBLIC . ADMIN_DIR); ?>/images/edit.png" alt="edit"/></a></td>
                                    <td><a href="javascript:confirmDel(<? echo($result[$i]['id']); ?>, <? echo(URL_PUBLIC); ?>)"><img src="<? echo(URL_PUBLIC . ADMIN_DIR); ?>/images/delete_icon.png" alt="X"/></a></td>
                                </tr>
                                <? endfor; ?>
                            </tbody>
                 </table>
            </div>
    <?php endif; ?>
<?php endif ?>

