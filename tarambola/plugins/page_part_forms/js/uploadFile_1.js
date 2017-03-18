 
 function initUploader(url, btn, container, structId){
    var btn = jQuery('#'+btn);
    var uploader = new ss.SimpleUpload({
      button: btn, // HTML element used as upload button
      url: url+'tarambola/plugins/page_part_forms/scripts/UploadFile.php', // URL of server-side upload handler
      name: 'uploadfile', // Parameter name of the uploaded file
      progressUrl: url+'tarambola/plugins/page_part_forms/scripts/uploadProgress.php', // enables cross-browser progress support (more info below)
      responseType: 'json',
      name: 'uploadfile',
      multiple: true,
      allowedExtensions: [], // for example, if we were uploading pics
      hoverClass: 'ui-state-hover',
      focusClass: 'ui-state-focus',
      disabledClass: 'ui-state-disabled',   
      onSubmit: function(filename, extension) {
          // Create the elements of our progress bar
          var progress = document.createElement('div'), // container for progress bar
              bar = document.createElement('div'), // actual progress bar
              fileSize = document.createElement('div'), // container for upload file size
              wrapper = document.createElement('div'), // container for this progress bar
              progressBox = document.getElementById(container); // on page container for progress bars

          // Assign each element its corresponding class
          progress.className = 'progressDownload';
          bar.className = 'barDownload';            
          fileSize.className = 'size';
          wrapper.className = 'wrapperDownload';

          // Assemble the progress bar and add it to the page
          progress.appendChild(bar); 
          wrapper.innerHTML = '<div class="name">'+filename+'</div>'; // filename is passed to onSubmit()
          wrapper.appendChild(fileSize);
          wrapper.appendChild(progress);                                       
          progressBox.appendChild(wrapper); // just an element on the page to hold the progress bars    

          // Assign roles to the elements of the progress bar
          this.setProgressBar(bar); // will serve as the actual progress bar
          this.setFileSizeBox(fileSize); // display file size beside progress bar
          this.setProgressContainer(wrapper);
          },

       // Do something after finishing the upload
       // Note that the progress bar will be automatically removed upon completion because everything 
       // is encased in the "wrapper", which was designated to be removed with setProgressContainer() 
      onComplete:   function(filename, response) {
          if(jQuery('#page_title').val()=="") jQuery('#page_title').val("INSERT TITLE HERE");
          if (!response.success) {
            alert(filename + ' upload failed! ' + response.msg + '!');
            return false;
          }
          else{
              $(structId).value = response.file + "/#imgsepara##/" + $(structId).value ;
              // jQuery('#pageContinueBtn').trigger('click');
              jQuery('form:first').submit();
          }
      }
    });
 }
