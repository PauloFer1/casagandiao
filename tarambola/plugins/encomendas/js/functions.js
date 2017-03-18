function saveState(url, id){
        var val = jQuery('#encomenda-state').val();
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/update-state',
            type: 'POST',
            data: { enc_id : id , state:val},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/encomendas');
                    }
                else
                {
                    alert("Erro ao salvar estado!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function saveAtributo(url)
{
    var attr = jQuery('#atributoInput').val();
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/add-atributo',
            type: 'POST',
            data: { descricao : attr},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/atributos');
                    }
                else
                {
                    alert("Erro ao salvar atributo!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updateAtributo(id, url)
{
    var attr = jQuery('#atributoInput').val();
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/update-atributo',
            type: 'POST',
            data: { descricao : attr, id:id},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/atributos');
                    }
                else
                {
                    alert("Erro ao salvar atributo!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function addAtributoValor(url, attrId)
{
    var val = jQuery('#atributoInput').val();
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/add-atributo-valor',
            type: 'POST',
            data: { descricao : val, atributo:attrId},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/view_atributo/'+attrId);
                    }
                else
                {
                    alert("Erro ao salvar atributo!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function saveIVA(url)
{
   var val = jQuery('#ivaInput').val(); 
   var use = jQuery('#useInput:checked').val(); 
   if(use!=1)
       use=0;
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/save-iva',
            type: 'POST',
            data: { val : val, use:use},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/iva');
                    }
                else
                {
                    alert("Erro ao salvar iva!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function insertPortes(url)
{
   var cod = jQuery('#codigoInput').val(); 
   var pais = jQuery('#paisInput option:selected').val(); 
   var valor = jQuery('#valorInput').val(); 
   var valorGratis = jQuery('#valorGratisInput').val(); 
   //var unidade = jQuery('#unidadeInput').val(); 
   //var valorUnidade = jQuery('#valorUnidadeInput').val(); 
   //var descricaoUnidade = jQuery('#descricaoUnidadeInput').val(); 
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/insert-portes',
            type: 'POST',
            data: {codigo : cod, pais:pais, valor:valor, valor_gratis:valorGratis},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/portes/');
                    }
                else
                {
                    alert("Erro ao salvar portes!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function savePortes(url)
{
   var idP = jQuery('#idInput').val(); 
   var cod = jQuery('#codigoInput').val(); 
   var pais = jQuery('#paisInput option:selected').val(); 
   var valor = jQuery('#valorInput').val(); 
   var valorGratis = jQuery('#valorGratisInput').val(); 
   //var unidade = jQuery('#unidadeInput').val(); 
   //var valorUnidade = jQuery('#valorUnidadeInput').val(); 
   //var descricaoUnidade = jQuery('#descricaoUnidadeInput').val(); 
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/save-portes',
            type: 'POST',
            data: {idPortes : idP, codigo : cod, pais:pais, valor:valor, valor_gratis:valorGratis},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/view_portes/'+idP);
                    }
                else
                {
                    alert("Erro ao salvar portes!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updatePortes(url)
{
   var cod = jQuery('#codigoInput').val(); 
   var pais = jQuery('#paisInput option:selected').val(); 
   var valor = jQuery('#valorInput').val(); 
   var valorGratis = jQuery('#valorGratisInput').val(); 
   var unidade = jQuery('#unidadeInput').val(); 
   var valorUnidade = jQuery('#valorUnidadeInput').val(); 
   var descricaoUnidade = jQuery('#descricaoUnidadeInput').val(); 
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/update-portes',
            type: 'POST',
            data: {codigo : cod, pais:pais, valor:valor, valor_gratis:valorGratis, unidade:unidade, valor_unidade:valorUnidade, descricao_unidade:descricaoUnidade},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/portes');
                    }
                else
                {
                    alert("Erro ao salvar Portes!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function insertPortesAssoc(url)
{
   var portesId = jQuery('#portesId').val(); 
   var max = jQuery('#unidadeMaxInput').val(); 
   var valor = jQuery('#valorInput').val(); 
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/insert-portes-assoc',
            type: 'POST',
            data: {portes_id:portesId, unidade_max:max, valor:valor},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        window.location.replace(url+'tarabackend/plugin/encomendas/view_portes/'+portesId);
                    }
                else
                {
                    alert("Erro ao salvar Portes!");
                }
            },
            failure: function () {
                alert("Erro de servidor!");
            }
          });
}
function updatePortesAssoc(id, url)
{
   var max = jQuery('#unidadeMaxInput').val(); 
   var valor = jQuery('#valorInput').val(); 
    var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/update-portes-assoc',
            type: 'POST',
            data: {id:id, unidade_max:max, valor:valor},
            dataType: "html",
            success: function (response) {
                if(response==1)
                    {
                        alert("Alterações efectuadas com sucesso!");
                    }
                else
                {
                    alert("Erro ao salvar Portes!");
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
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/delete-attr',
            type: 'POST',
            data: { id: id},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                       window.location.replace(url+'tarabackend/plugin/encomendas/atributos');
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
function confirmDelPortesAssoc(id, url)
{
    var s = "Tem a certeza que pretende apagar?";
    var answer = confirm (s);
    if (answer)
    {
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/delete-portes-assoc',
            type: 'POST',
            data: { id: id},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                       location.reload();
                    }
                else
                {
                    alert("Erro ao remover Associação!");
                }
            },
            failure: function () {
                alert("Erro ao salvar Associação!");
            }
          });  
    }
        
}
function confirmDelPortes(id, url)
{
    var s = "Tem a certeza que pretende apagar?";
    var answer = confirm (s);
    if (answer)
    {
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/delete-portes',
            type: 'POST',
            data: { id: id},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                       location.reload();
                    }
                else
                {
                    alert("Erro ao remover Associação!");
                }
            },
            failure: function () {
                alert("Erro ao salvar Associação!");
            }
          });  
    }
        
}
function confirmDelEncomenda(id, url)
{
    var s = "Tem a certeza que pretende apagar?";
    var answer = confirm (s);
    if (answer)
    {
        var request = jQuery.ajax({
            url: url+'tarambola/plugins/encomendas/scripts/functions.php/delete-encomenda',
            type: 'POST',
            data: { id: id},
            dataType: "html",
            success: function (response) {
                if(response!=0)
                    {
                       location.reload();
                    }
                else
                {
                    alert("Erro ao remover Associação!");
                }
            },
            failure: function () {
                alert("Erro ao salvar Associação!");
            }
          });  
    }
        
}
