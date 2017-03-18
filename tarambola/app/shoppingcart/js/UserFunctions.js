function register(nome, email, telefone, password, morada, nif, cidade, cod_postal, obs )
{
                        var request = $.ajax({
                            url: window.location.protocol+window.location.host+'public/scripts/user_functions.php/registo',
                            type: 'POST',
                            data: {nome: nome ,email: email, telefone:telefone ,password:password, morada:morada, nif:nif, obs:obs, cidade:cidade, cod_postal:cod_postal},
                            dataType: "html",
                            success: function (response) {
                                $('#preloaderRegisto').hide();
                                if(response==1)
                                    {
                                        $('#registoErrorMsg').html("Pedido de registo efectuado </br>Aguarde por email de confirmação!");
                                        $('#registoErrorMsg').show(200);
                                    }
                                    else
                                    {
                                        aler(response);
                                        $('#loginFormBox').show();
                                        if(response==-1)
                                        {
                                            $('.error').show(200);
                                            $('.error p').html("Email já existente!");
                                        }
                                        else
                                         {
                                             $('.error').show(200);
                                            $('.erro p').html(response);
                                         }
                                        $('#registoForm').show();
                                    }   
                                },
                            failure: function () {
                                $('#preloaderRegisto').hide();
                                $('#registoForm').show();
                                $('.error').show(200);
                                $('.error p').html("Erro no servidor!");
                            }
                          });
};
  function loginUser(email, password)
    {
        var request = $.ajax({
            url: window.location.protocol+window.location.host+'/public/scripts/user_functions.php/login',
            type: 'POST',
            data: {utilizador:email, password:password},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        $('#ajaxPreloader').hide();
                        $('#loginFormBox').show();
                        if(response==-1)
                            $('.error p').html("Excesso de tentativas erradas!");
                        else if(response==-2)
                            $('.error p').html("Password errada!");
                        else if(response==-3)
                            $('.error p').html("Username errado!");
                        else if(response==-4)
                            $('.error p').html("A aguardar confirmação!");
                    }
            },
            failure: function () {
                alert("erro");
            }
          });
          return(false);
    }
    function logoutUser()
    {
       var request = $.ajax({
            url: window.location.protocol+window.location.host+'/public/scripts/user_functions.php/logout',
            type: 'POST',
            data: {},
            dataType: "html",
            success: function (response) {
                if(response==true)
                    {
                        window.location.reload();
                    }
            },
            failure: function () {
                alert("erro");
            }
          });
          return(false); 
    }