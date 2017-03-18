jQuery(document).ready(function(){
    addEventToSaveBtn('#pageContinueBtn');
    addEventToSaveBtn('#pageCommitBtn');
});

function addEventToSaveBtn(id)
{
     jQuery(id).click(function(evt){
        var fields = jQuery('.required');
        var flag=false;
        var elem;
        fields.each(function(index){
            if(!jQuery(this).val())
            {
                //alert("Please fill field for "+jQuery(this).attr('label'));
                jQuery(this).css('border-color', 'red');
                jQuery('#'+jQuery(this).attr('id')+'_msg').show();
                elem=jQuery(this);
                flag=true;
            }
        });
        var fieldsRadio = jQuery('.required_radiobox');
        var names = new Array();
        fieldsRadio.each(function(index){
                addNames(names, jQuery(this).attr('name'));
                
                //alert("Please fill field for "+jQuery(this).attr('label'));
              /*  jQuery(this).css('border-color', 'red');
                jQuery('#'+jQuery(this).attr('id')+'_msg').show();
                elem=jQuery(this);
                flag=true;*/
        }); 
        for(var i=0; i<names.length; i++)
        {
            if(isFieldFill(fieldsRadio, names[i]))
            {
                var labels = jQuery('.page-part-forms-part-radio');
                labels.each(function(index){
                    if(jQuery(this).attr('label')==names[i])
                    {
                        jQuery(this).css('border-color', 'red');
                        var msgs = jQuery('.requiredMsg');
                        msgs.each(function(index){
                            if(jQuery(this).attr('label')==names[i])
                            {
                                jQuery(this).show();
                                elem=jQuery(this);
                            }
                        });
                        flag=true;
                    }
                });
            }
        }
        if(flag)
        {
            jQuery('html, body').animate({
                    scrollTop:elem.offset().top-150
                }, 500);
            return(false);
        }
    });
}
function addNames(array, name)
{
    for(var i=0; i<array.length; i++)
    {
        if(array[i]==name)
            return(0);
    }
    array.push(name);
}
function isFieldFill(fields, name)
{
    var flag=false;
    fields.each(function(index){
        if(jQuery(this).attr('name')==name)
        {
            if(!jQuery(this).is(':checked'))
                flag=true;
            else
            {
                flag=false;
                return(flag);
            }
        }
    });
    return(flag);
}