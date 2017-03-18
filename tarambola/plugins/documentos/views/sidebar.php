
<!-- <p class="button buttonLeft">
    <a  href="#"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/return_btn.png"/><span class="buttonText"> VOLTAR A DOCUMENTOS</span></a>
</p> -->
<div id="uploadProgress">
</div>
<p class="button">
    <button id="uploadDocumentBtn"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/upload_img.png"/><span class="buttonText">UPLOAD</span></button>
</p>
<!-- <p class="button">
    <button id="uploadDocumentBtn" onclick="toggle_popup('create-directory-popup', 'create_directory_name'); return false;"> <?php echo __('Criar directoria'); ?></button>
</p> -->
<p class="button">
    <a  onclick="toggle_popup('create-category-popup', 'create_category_name'); return false;"><img src="<? echo(URL_PUBLIC.ADMIN_DIR); ?>/images/add_new.png"/><span class="buttonText"> <?php echo __('Criar categoria'); ?></span></a>
</p>


<!-- * ************************ JAVASCRIPT ********************* * -->
<script type="text/javascript" charset="utf-8" >
    var btn = jQuery('#uploadDocumentBtn');
    var uploader = new ss.SimpleUpload({
      button: btn, // HTML element used as upload button
      url: '<? echo(URL_PUBLIC); ?>tarambola/plugins/documentos/scripts/UploadFile.php', // URL of server-side upload handler
      name: 'uploadfile', // Parameter name of the uploaded file
      progressUrl: 'uploadProgress.php', // enables cross-browser progress support (more info below)
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
              progressBox = document.getElementById('uploadProgress'); // on page container for progress bars

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
          if (!response) {
            alert(filename + 'upload failed');
            return false;
          }
          else{
              location.reload();
          }
      }
});
</script>