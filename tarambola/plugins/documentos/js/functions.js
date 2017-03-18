  /* ********************** Utilizadores ************************** */
    function showDescBox(id)
    {
        jQuery("#descCatBox"+id).show(200);
    }
    function closeDescBox(id)
    {
        jQuery("#descCatBox"+id).hide(100);
    }
  function showAssocBox(id)
    {
        jQuery("#assocbox"+id).show(200);
    }
    function closeAssocBox(id)
    {
        jQuery("#assocbox"+id).hide(100);
    }
    function savePageForm(id, url)
    {
        var form = jQuery('#selectOptBox'+id).find(":selected").text();
        var level = jQuery('#levelSelector'+id).find(":selected").text();

        var request = jQuery.ajax({
            url: url+'tarabackend/scripts/insert_module.php',
            type: 'GET',
            data: { modulo_id : id , nivel:level, form:form},
            dataType: "html",
            success: function (response) {
            closeSelectBox(id);
            },
        failure: function () {
            alert("ERRO!");
        }
          });
    }
    function saveDesc(id)
    {
        var request = jQuery.ajax({
                        url: '../tarambola/plugins/documentos/scripts/functions.php?request=descricao',
                        type: 'GET',
                        data: { id_user : id , descricao:jQuery('#descInput'+id).val()},
                        dataType: "html",
                        success: function (response) {
                        //alert(response);
                            closeDescBox(id);
                        },
                    failure: function () {
                        alert("ERRO");
                    }
                      });
    }
    function selectUsers(id, idDoc)
    {
        var opts = new Array();
        jQuery('.options2'+idDoc).each(function(k, opt){
                opts.push(jQuery(opt).val());
        });
        jQuery('#selectOptBox'+id+' :selected').each(function(i, selected){
            var hasUser=false;
            var user = jQuery(selected);
            for(var i=0; i<opts.length; i++)
                {
                    if(opts[i]==user.val())
                        hasUser=true;
                }
            if(!hasUser)
                {
                    jQuery('#2selectOptBox'+id).append('<option class="options2'+idDoc+'" value="'+user.val()+'">'+user.text()+'</option>');
                     var request = jQuery.ajax({
                        url: '../tarambola/plugins/documentos/scripts/functions.php?request=assoc',
                        type: 'GET',
                        data: { id_user : user.val() , id_doc:idDoc},
                        dataType: "html",
                        success: function (response) {
                        //alert(response);
                        //closeAssocBox(id);
                        },
                    failure: function () {
                        alert("ERRO");
                    }
                      });
                }
        });
        
    }
    function unselectUsers(id, idDoc)
    {
        jQuery('#2selectOptBox'+id+' :selected').each(function(i, selected){ 
            jQuery(selected).remove();
                     var request = jQuery.ajax({
                        url: '../tarambola/plugins/documentos/scripts/functions.php?request=remove',
                        type: 'GET',
                        data: { id_user : jQuery(selected).val() , id_doc:idDoc},
                        dataType: "html",
                        success: function (response) {
                        //alert(response);
                        //closeAssocBox(id);
                        },
                    failure: function () {
                        alert("ERRO");
                    }
                      });
        });
        
    }
    function saveAssocs(id)
    {
        
    }
    /* ********************** Categorias ************************** */
    function showCatsBox(id)
    {
        jQuery("#assocCatBox"+id).show(200);
    }
    function closeCatsBox(id)
    {
        jQuery("#assocCatBox"+id).hide(100);
    }
    function selectCat(id)
    {
        var opts = new Array();
        jQuery('.options2Cat'+id).each(function(k, opt){
                opts.push(jQuery(opt).val());
        });
        jQuery('#catOptBox'+id+' :selected').each(function(i, selected){
            var hasUser=false;
            var cat= jQuery(selected);
            for(var i=0; i<opts.length; i++)
                {
                    if(opts[i]==cat.val())
                        hasUser=true;
                }
            if(!hasUser)
                {
                    jQuery('#2catOptBox'+id).append('<option class="options2Cat'+id+'" value="'+cat.val()+'">'+cat.text()+'</option>');
                     var request = jQuery.ajax({
                        url: '../tarambola/plugins/documentos/scripts/functions.php?request=assoc_cat',
                        type: 'GET',
                        data: { id_cat : cat.val() , id_doc:id},
                        dataType: "html",
                        success: function (response) {
//                        alert(response);
                        //closeAssocBox(id);
                        },
                    failure: function () {
                        alert("erro");
                    }
                      });
                }
        });
        
    }
    function unselectCat(id)
    {
        jQuery('#2catOptBox'+id+' :selected').each(function(i, selected){ 
            jQuery(selected).remove();
                     var request = jQuery.ajax({
                        url: '../tarambola/plugins/documentos/scripts/functions.php?request=remove_cat',
                        type: 'GET',
                        data: { id_cat : jQuery(selected).val() , id_doc:id},
                        dataType: "html",
                        success: function (response) {
                        //alert(response);
                        //closeAssocBox(id);
                        },
                    failure: function () {
                        alert("erro");
                    }
                      });
        });
        
    }