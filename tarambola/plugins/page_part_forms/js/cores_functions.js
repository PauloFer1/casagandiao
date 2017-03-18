
    setEventsColor();
    setAddColorBtn();

function setEventsColor(){
    setRemoveColorBtns();
    setPicker();
}
function setPicker()
{
       jQuery('.pickerColor').colpick({
	layout:'hex',
	submit:0,
	colorScheme:'dark',
	onChange:function(hsb,hex,rgb,el,bySetColor) {
		jQuery(el).css('border-color','#'+hex);
		// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                    if(!bySetColor) 
                    {
                        jQuery(el).val(hex);
                        getBoxesColor();
                    }
                }
        }).keyup(function(){
                jQuery(this).colpickSetColor(this.value);
        }); 
}
function setRemoveColorBtns(){
    jQuery('.removeColorBtn').click(function(e){
        jQuery(this).parent().remove();
        getBoxesColor();
        e.preventDefault();
        return(false);
    })
}
function setAddColorBtn(){
     jQuery('.addNewColorBtn').click(function(e){
        jQuery('#allColors').append(jQuery('#hiddenDivColor').html());
        setEventsColor();
        e.preventDefault();
        return(false);
    })
}
function getBoxesColor(){
    var final = "{'cores':[";
    
    jQuery('#allColors .colorBox').each(function(index){
        if(index>0)
            final+=",";
        final+= parseInt(jQuery(this).find('input').val(),16);
        //alert(jQuery(this).find('.quantidadeAtt').val());
    });
    final += "]}";
    updateColor(final);
}
function updateColor(value){
    jQuery('#Page-part-forms-Page-Part-cores').val(value);
}