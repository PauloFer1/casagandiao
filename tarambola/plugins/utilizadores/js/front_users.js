function insertFrontUser(url)
{
      var nome = jQuery('#nome_user').val();
      var user = jQuery('#username_user').val();
      var morada = jQuery('#morada_user').val();
      var cidade = jQuery('#cidade_user').val();
      var cod_postal = jQuery('#cod_postal_user').val();
      var tel = jQuery('#telefone_user').val();
      var mail = jQuery('#email_user').val();
      var nif = jQuery('#nif_user').val();
      var pass = jQuery('#password_user').val();
      var obs = jQuery('#obs_user').val();

        var request = jQuery.ajax({
            url: url+'tarambola/plugins/utilizadores/scripts/user_frontend.php/insert',
            type: 'POST',
            data: { nome : nome , username:user, morada:morada, cidade:cidade, cod_postal:cod_postal, telefone:tel, email: mail, nif:nif, password:pass, obs:obs},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        window.location.replace(url+'tarabackend/plugin/utilizadores/view/'+response);
                    }
                else
                {
                    alert("Erro ao introduzir Utilizador!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updateFrontUser(id, url, ass)
{
      var nome = jQuery('#nome_user').val();
      var user = jQuery('#username_user').val();
      var morada = jQuery('#morada_user').val();
      var cidade = jQuery('#cidade_user').val();
      var cod_postal = jQuery('#cod_postal_user').val();
      var tel = jQuery('#telefone_user').val();
      var mail = jQuery('#email_user').val();
      var nif = jQuery('#nif_user').val();
      var pass = jQuery('#password_user').val();
      var reg = jQuery('#register_user').val();
      var obs = jQuery('#obs_user').val();
      
      var assocs = ass;
      var newAssocs = new Array();
      var add = new Array();
      var remove = new Array();
       jQuery('.assocDocOptions2').each(function(k, opt){
                newAssocs.push(jQuery(opt).val());
        });
      var found=0;
      for(var i=0; i<newAssocs.length; i++)
      {
        found=0;
        for(var k=0; k<assocs.length && found==0; k++)
        {
            if(assocs[k].id==newAssocs[i])
            {
                found=1;
                assocs.splice(k,1);
                newAssocs.splice(i,1); 
            }     
        }
      }
       for(var k=0; k<assocs.length; k++)
        {
            var request = jQuery.ajax({
            url: url+'tarambola/plugins/utilizadores/scripts/user_frontend.php/remove_assoc_doc',
            type: 'POST',
            data: { id_user: id, id_doc : assocs[k].id},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        
                    }
                else
                {
                    alert("Erro (1) ao salvar Utilizador!");
                }
            },
            failure: function () {
                alert("Erro (2) ao salvar Utilizador!");
            }
          });  
        }
        for(var i=0; i<newAssocs.length; i++)
        {
            var request = jQuery.ajax({
            url: url+'tarambola/plugins/utilizadores/scripts/user_frontend.php/assoc_doc',
            type: 'POST',
            data: { id_user: id, id_doc : newAssocs[i]},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        alert("new " + response);
                    }
                else
                {
                    alert("Erro (3) ao salvar Utilizador!");
                }
            },
            failure: function () {
                alert("Erro (4) ao salvar Utilizador!");
            }
          });  
        }
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/utilizadores/scripts/user_frontend.php/update',
            type: 'POST',
            data: { id: id, nome : nome , username:user, morada:morada, cidade:cidade, cod_postal:cod_postal, telefone:tel, email: mail, nif:nif, password:pass, registo:reg, obs:obs},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                        window.location.replace(url+'tarabackend/plugin/utilizadores/view/'+response);
                    }
                else
                {
                    alert("Erro (5) ao salvar Utilizador!");
                }
            },
        failure: function () {
            alert("Erro (6) ao salvar Utilizador!");
        }
          });
}

function selectDoc()
{
    var opts = new Array();
        jQuery('.assocDocOptions2').each(function(k, opt){
                opts.push(jQuery(opt).val());
        });
        jQuery('#assocDoc :selected').each(function(i, selected){
            var hasUser=false;
            var cat= jQuery(selected);
            for(var i=0; i<opts.length; i++)
                {
                    if(opts[i]==cat.val())
                        hasUser=true;
                }
            if(!hasUser)
                {
                    jQuery('#2assocDoc').append('<option class="assocDocOptions2" value="'+cat.val()+'">'+cat.text()+'</option>');
                }
        });
}
function unselectDoc()
{
     jQuery('#2assocDoc :selected').each(function(i, selected){ 
            jQuery(selected).remove();
        });
}

function confirmDelUser(id, url)
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
function sendEmail(id, url, nome, email)
{
      var request = jQuery.ajax({
            url: url+'tarambola/plugins/utilizadores/scripts/user_frontend.php/send_confirmation',
            type: 'POST',
            data: { id_user: id, nome:nome, email:email},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        jQuery('#register_user').val(1);
                        jQuery('.saveFrontUserBtn').click();
                    }
                else
                {
                    alert("Erro ao renviar email! (Err: "+response+")");
                }
            },
            failure: function () {
                alert("Erro ao salvar Utilizador!");
            }
          });  
}

