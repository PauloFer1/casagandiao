var domain="";
$(document).ready(function(){
    setColorSelect();
    setSizeSelect();
    setAddToCartBtn();
    $('.qtdSpinner').spinnerSelect({min: true, max:true});
});
function setAddToCartBtn()
{
    $('#addToCartBtn').click(function(evt){
        evt.preventDefault();
        addToCart(); 
    });
}
function setSizeSelect(){
    $('#selectSize').change(function(){
        
        var lista = new Array();
        var color = $('#selectColor option:selected').val();
        var size = $('#selectSize option:selected').val();
        var idColor = $('#atributoColor').val();
        var idSize = $('#atributoSize').val();
        lista.push(color);
        lista.push(size);
        
        getQtdByAttributes(lista);
    });
}
function setColorSelect(){
    $('#selectColor').change(function(){
        
        var lista = new Array();
        var color = $('#selectColor option:selected').val();
        var size = $('#selectSize option:selected').val();
        var idColor = $('#atributoColor').val();
        var idSize = $('#atributoSize').val();
        lista.push(color);
        lista.push(size);
        
        getValuesByAttributeValue(idColor, color, idSize);
    });
}
//################################## DOM CHANGERS ###########################################//
function changeSizeSelect(list)
{
    var html='';
    for(var propt in list)
    {
        html+='<option value="'+list[propt]['id']+'">'+list[propt]['descricao']+'</option>';
    }
    $('#selectSize').html(html);
    var lista = new Array();
        var color = $('#selectColor option:selected').val();
        var size = $('#selectSize option:selected').val();
        lista.push(color);
        lista.push(size);
        
        getQtdByAttributes(lista);
}
function changeQtd(qtd)
{
    if(qtd==-1)
        qtd=1000;
    var html='';
    for(var i=1; i<=qtd; i++)
    {
        html+='<option value="'+i+'">'+i+'</option>';
    }
    $('#qtdSelect').html(html);
}
//######################################### AJAX CALLS ###############################################//
function getQtdByAttributes(list)
{
    var url = window.location.protocol+'//'+window.location.host+domain+'/tarambola/app/shoppingcart/ajaxcall/attributefunctions.php/get-qtd-atr';
     var request = $.ajax({
                            url: url,
                            type: 'POST',
                            data: {list: list},
                            dataType: "html",
                            success: function (response) {
                                changeQtd(response);
                            },
                            failure: function () {
                                alert("Server Failure!");
                            }
                          });
}
function getValuesByAttributeValue(id1, value, id2)
{
    var url = window.location.protocol+'//'+window.location.host+domain+'/tarambola/app/shoppingcart/ajaxcall/attributefunctions.php/get-val-by-atrval';
     var request = $.ajax({
                            url: url,
                            type: 'POST',
                            data: {id1: id1, value:value, id2:id2},
                            dataType: "json",
                            success: function (response) {
                                changeSizeSelect(response);
                            },
                            failure: function () {
                                alert("Server Error!");
                            }
                          });
}
//********************* CART CALLS ************************//
function addToCart()
{
    var pageId = $('#itemPageId').val();
    var qtd = $('#qtdSelect option:selected').val();
    var color = $('#selectColor option:selected').val();
    var size = $('#selectSize option:selected').val();
    var colorId = $('#atributoColor').val();
    var sizeId = $('#atributoSize').val();
    var url = window.location.protocol+'//'+window.location.host+domain+'/tarambola/app/shoppingcart/ajaxcall/cartfunctions.php/add-item';
    if(qtd>0)
    {
        var request = $.ajax({
                            url: url,
                            type: 'POST',
                            data: {pageId: pageId, qtdItem:qtd, color:color, size:size, colorId:colorId, sizeId:sizeId},
                            dataType: "html",
                            success: function (response) {
                                alert(response);
                                if(response==-1)
                                {
                                    showMessage("Item Indisponível","A quantidade desejada não está disponível.");
                                    //alert("A quantidade desejada não está disponível!");
                                }
                                else
                                    getHtmlCart();
                            },
                            failure: function () {
                                //alert("Server Error!");
                                showMessage('Erro do Servidor','');
                            }
                          });
     }
     else
     {
       showMessage("Item Indisponível", '');  
     }
    return(false);
}
