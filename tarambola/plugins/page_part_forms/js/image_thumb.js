function showWindow(imgName, path, server_path)
{
    var html = ' <div style="padding:10px;">'+
      '<div style="width:100%; float:left;">'+
      '<div class="img-container">'+
        '<img class="cropper" src="'+path+"/public/images/"+imgName+'"/>'+
        '</div>'+
        '<div class="img-preview"></div>'+
        '<button id="createThumbBtn">Save Thumb</button>'+
        '<img id="loaderThumb" src="'+path+'tarambola/plugins/page_part_forms/images/ajax-loader.gif"/>'+
      '</div>'+
   '</div>';
  
  var width=0;
  var height=0;
  var pos_x=0;
  var pos_y=0;
    jQuery.window({
        showModal: true,
        modalOpacity: 0.5,
        title: "Create Thumbnail",
        content: html,
        width: 800,
        height:600
     });
     jQuery(".cropper").cropper({
        aspectRatio: 184/128,
        preview: ".img-preview",
        data:{
            x1: 20,
            y1: 20,
            width: 184,
            height: 128,
        },
        done: function(data) {
            width=data.width;
            height=data.height;
            pos_x = data.x1;
            pos_y=data.y1;
        }
    });
    
    jQuery('#createThumbBtn').click(function(evt){
        jQuery('#loaderThumb').fadeIn(0);
        evt.stopPropagation();
        jQuery.ajax({
            url:path+'tarambola/plugins/page_part_forms/scripts/create_thumb.php',
            type: 'POST',
                            data: {name:imgName, path:path, pos_x:pos_x, pos_y:pos_y, width:width, height:height, server_path:server_path},
                            dataType: "html",
                            success: function (response) {
                               // alert(response);
                               jQuery('#loaderThumb').fadeOut(0);
                                jQuery('#Page-part-forms-Page-Part-thumb').val(response+'/#imgsepara##/');
                                jQuery('#pageContinueBtn').trigger('click');
                            },
                            failure: function () {
                                jQuery('#loaderThumb').fadeOut(0);
                                alert("Server Error!");
                            }
        });
    });
    //var cropperHeader = new Croppic('img-container');
    
   
    
}
