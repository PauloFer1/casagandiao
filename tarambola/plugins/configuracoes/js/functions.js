function insertMensagem(url)
{
      var lang = jQuery('#lang_Select').val();
      var funcao = jQuery('#funcao_Select').val();
      var mensagem = jQuery('#mensagem_Text').val();

        var request = jQuery.ajax({
            url: url+'tarambola/plugins/configuracoes/scripts/functions.php/insert',
            type: 'POST',
            data: { lang : lang , funcao:funcao, mensagem:mensagem},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        window.location.replace(url+'tarabackend/plugin/configuracoes/view/'+response);
                    }
                else
                {
                    alert("Erro ao introduzir Mensagem!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updateMensagem(id, url)
{
      var lang = jQuery('#lang_Select').val();
      var funcao = jQuery('#funcao_Select').val();
    //  var mensagem = jQuery('#mensagem_Text').val();
    var mensagem = tinyMCE.activeEditor.getContent();
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/configuracoes/scripts/functions.php/update',
            type: 'POST',
            data: { id:id, lang : lang , funcao:funcao, mensagem:mensagem},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        //alert(mensagem);
                        window.location.replace(url+'tarabackend/plugin/configuracoes/view/'+response);
                    }
                else
                {
                    alert("Erro ao introduzir Mensagem!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function confirmDel(id, url)
{
    var s = "Tem a certeza que pretende apagar?";
    var answer = confirm (s);
    if (answer)
    {
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/utilizadores/scripts/user_frontend.php/delete_user',
            type: 'POST',
            data: { id_user: id},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        window.location.replace(url+'tarabackend/plugin/utilizadores');
                    }
                else
                {
                    alert("Erro ao remover Utilizador!");
                }
            },
            failure: function () {
                alert("Erro ao salvar Utilizador!");
            }
          });  
    }
        
}
function toggle_confirm_popup(file, filename)
{
	var popup = $('confirmacao-email-popup');
	var file_mode = $('rename_file_new_name');
	$('rename_file_current_name').value = file;
	file_mode.value = filename;
	center(popup);
	Element.toggle(popup);
	Field.focus(file_mode);
}
function updateContaServer(url)
{
    var conta = jQuery('#conta').val();
    var server = jQuery('#server').val();
    var pass = jQuery('#password').val();
    
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/configuracoes/scripts/functions.php/sender',
            type: 'POST',
            data: { server : server , conta:conta, pass:pass},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        window.location.replace(url+'tarabackend/plugin/configuracoes');
                    }
                else
                {
                    alert("Erro ao salvar!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updateEmail(url)
{
    var email = jQuery('#email').val();
    
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/configuracoes/scripts/functions.php/email',
            type: 'POST',
            data: { email : email },
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        window.location.replace(url+'tarabackend/plugin/configuracoes');
                    }
                else
                {
                    alert("Erro ao salvar!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updatePayPal(url)
{
    var id = jQuery('#client_id').val();
    var secret = jQuery('#client_secret').val();
    
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/configuracoes/scripts/functions.php/paypal',
            type: 'POST',
            data: { id : id, secret:secret },
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        alert("Alteração efectuada com sucesso!");
                        window.location.replace(url+'tarabackend/plugin/configuracoes');
                    }
                else
                {
                    alert("Erro ao salvar!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
