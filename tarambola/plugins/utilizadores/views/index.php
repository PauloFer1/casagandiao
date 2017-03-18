<h1><?php echo __('Front-end Users'); ?></h1>
<?php
require_once(SERVER_URL . 'tarambola/app/classes/Page.php');
require_once(SERVER_URL . 'tarambola/app/classes/User_Plug.php');
if (isset($_GET['delete_user'])) {
    $userDelId = $_GET['delete_user'];
    $user = User_Plug::deleteUserById($userDelId);

}

if (isset($_GET['user'])) {
    $userId = $_GET['user'];
    $user = User_Plug::getUserById($userId);
    if (isset($_POST['confirmBtn'])) {
        if (User_Plug::confirmUser($userId)) {
            require_once(SERVER_URL . 'tarambola/app/classes/class.phpmailer.php');
            require_once(SERVER_URL . 'tarambola/app/classes/class.smtp.php');
            echo('<h3>Utilizador Confirmado');
            $email = $user->email;
            $mail_body = '<img src="' . URL_PUBLIC . 'public/themes/default/_img/logoCadeinor.png" alt="Cadeinor.com" />
<p>O seu registo em <a href="http://www.cadeinor.com" title="cadeinor"> cadeinor.com</a> foi aceite.</p>
';
            $subject = "Registo em cadeinor.com";
            $header = 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $header .= "From: Cadeinor <geral@cadeinor.com>\r\n";


            $Email = new PHPMailer();
            $Email->SetLanguage("pt");
            $Email->Host = "tarambola.pt";
            //$Email->AddEmbeddedImage(URL_PUBLIC.'public/themes/kompraki/_img/logo.png', 'logoimg', 'logo.png');
            //  $Email->IsMail();
            $Email->IsSMTP();
            $Email->SMTPAuth = true;
            $Email->Username = "testes@tarambola.pt";
            $Email->Password = "12345";
            $Email->IsHTML(true);
            //$Email->SmtpSend();
            // mude aqui, o endereço do seu servidor, ou inclua uma classe de smtp, como indicado abaixo linha 460
//include_once($this->PluginDir . "class.smtp.php");
            $Email->From = "geral@cadeinor.com";
            $Email->FromName = "Cadeinor";
            $Email->AddAddress($user->email);
            $Email->Subject = $subject;
            $Email->MessageID = $header;

            $Email->Body = $mail_body;


            if ($Email->Send()) {//mail($email, $subject, $mail_body, $header))
                echo'
                        <p>A sua Mensagem foi enviada correctamente!</p>';
            } else {
                echo'
                        <p>Ocorreu um erro no envio de dados. Por favor tente novamente!</p>';
            }
        } else {
            echo('<h3>Erro ao confirmar utilizador!</h3>');
        }
    }
    $html = '
        <table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                <span>Nome</span>
                            </th>
                            <th>
                              <span>Username</span>
                            </th>
                            <th>
                                <span>NIF</span>
                            </th>
                            <th>
                               <span>Morada</span>
                            </th>
                            <th>
                                <span>Empresa</span>
                            </th>
                            <th>
                             <span>Telefone</span>
                            </th>
                            <th>
                                <span>E-mail</span>
                            </th>
                            <th>
                               <label for="confirmBtn">confirmar utilizador</label>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="listagemLinha">
                                <td>
                                    <p>' . $user->nome . '</p>
                                </td>
                                <td>
                                    <p>' . $user->username . '</p>
                                </td>
                                 <td>
                                     <p>' . $user->nif . '</p>
                                </td>
                                 <td>
                                     <p>' . $user->morada . '</p>
                                </td>
                                 <td>
                                    <p>' . $user->cidade . '</p>
                                </td>
                                 <td>
                                   <p>' . $user->telefone . '</p>
                                </td>
                                 <td>
                                   <p>' . $user->email . '</p>
                                </td>
                                <td>
                                  <form action="" method="post">
        
        <input type="submit" name="confirmBtn" id="btnSend" value="confirmar" />
    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
<style type="text/css" title="currentStyle">
                            @import "' . URL_PUBLIC . '/tarabackend/stylesheets/demo_table.css";
            </style>       
';
    echo($html);
} else {
    $headerApp = '<style type="text/css" title="currentStyle">
                            @import "' . URL_PUBLIC . '/tarabackend/stylesheets/demo_table.css";
            </style>
            <script type="text/javascript" language="javascript" src="' . URL_PUBLIC . '/tarabackend/javascripts/jquery.js"></script>
            <script type="text/javascript" language="javascript" src="' . URL_PUBLIC . '/tarabackend/javascripts/jquery.dataTables.js"></script>
            <script type="text/javascript" charset="utf-8">
                            $(document).ready(function() {
                                    $(\'#listagem\').dataTable( );
                            } );
            </script>
';
    echo($headerApp);

    $users = User_plug::getUsers();

    $news = '<div id=innerModulos>';
    $news .= '<table id="listagem" class="display" cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>
                                Nome
                            </th>
                            <th>
                               Username
                            </th>
                            <th>
                                Estado
                            </th>
                            <th class="optList">
                                Ver
                            </th>
                            <th class="optList">
                                Apagar
                            </th>
                        </tr>
                    </thead>';

    if($users!=null)
    {
        foreach ($users as $us) {
            if ($us->registo == 1) {
                $estado = "confirmado";
            } else {
                $estado = "não confirmado";
            }
            $news.='<tr class="listagemLinha">
                        <td>
                            ' . $us->nome . '
                        </td>
                        <td>
                            ' . $us->username . '
                        </td>
                        <td>
                            ' . $estado . '
                        </td>
                        <td class="optList">
                             <a href=' . URL_PUBLIC . '/tarabackend/plugin/utilizadores/view/' . $us->id . '><img src="' . URL_PUBLIC . ADMIN_DIR . '/images/edit.png" alt="edit"/></a>
                        </td>
                        <td class="optList">
                             <a href="javascript:confirmDelUser(' . $us->id . ', \''.URL_PUBLIC.'\')"><img src="' . URL_PUBLIC . ADMIN_DIR . '/images/delete_icon.png" alt="X"/></a>
                       </td>
                       </tr>';
        }
    }
    $news.='</table></div>';

    echo($news);
}
?>
<div id="popups">
  <div class="popup" id="confirmacao-email-popup" style="display:none;">
    <h3><?php echo __('EMAIL DE CONFIRMAÇÃO'); ?></h3>
    <form action="<?php echo get_url('plugin/file_manager/create_file'); ?>" method="post"> 
      <div>
        <input id="create_file_path" name="file[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="confirmacao_email_name" maxlength="255" name="file[name]" type="text" value="" /> 
        <input id="create_file_button" name="commit" type="submit" value="<?php echo __('Create'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('confirmacao-email-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
</div>
