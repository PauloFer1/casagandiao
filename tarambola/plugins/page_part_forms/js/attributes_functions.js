
        setEvents();
        setAddBtn();

function setEvents(){
    setRemoveBtns();
    setTriggers();
}
function setRemoveBtns(){
    jQuery('.removeAttrBtn').click(function(e){
        jQuery(this).parent().remove();
        getBoxes();
        e.preventDefault();
        return(false);
    })
}
function setAddBtn(){
     jQuery('.addNewBtn').click(function(e){
        jQuery('#allAttributes').append(jQuery('#hiddenDivAttr').html());
        setEvents();
        e.preventDefault();
        return(false);
    })
}
function setTriggers(){
    var select = jQuery('.attributeSelect');
    var input = jQuery('.quantidadeAtt');
    select.change(function(){
        getBoxes(); 
    });

    input.bind("change paste keyup", function() {
        getBoxes(); 
    });
}
function update(value){
    jQuery('#Page-part-forms-Page-Part-atributos').val(value);
}
function getBoxes(){
    var final = "{'atributos':[";
    
    jQuery('#allAttributes .attributesBox').each(function(index){
        //alert(jQuery(this).html());
        if(index>0)
            final+=",";
        var qtd = jQuery(this).find('.quantidadeAtt').val();
        if(qtd=="")
            qtd=0;
        final+="{'qtd':"+qtd+",'lista':[";
        //alert(jQuery(this).find('.quantidadeAtt').val());
        var atributo = getAtributosQtd(jQuery(this));
        final+=atributo+"]}";
    });
    final += "]}";
    update(final);
}
function getAtributosQtd(ele){
    var str="";
    ele.find('select').each(function(index){
        var atributo = jQuery(this).attr('id').slice(-1);
        var value = jQuery(this).find(':selected').val();
        if(index>0)
            str+=",";
        str += "{'atributo':"+atributo+",'valor':"+value+"}";
    });
    return(str);
  //  alert(ele.html());
}