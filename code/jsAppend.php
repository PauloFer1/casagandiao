<!-- JavaScripts -->
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/custom-map.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/bootstrap.min.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/easing.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/sly.min.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/wow.min.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/masonry.pkgd.min.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/imagesLoaded.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/fancybox/jquery.fancybox.pack.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/isotope.pkgd.min.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/slick.min.js"></script>
    <script src="<?php echo URL_PUBLIC; ?>public/themes/default/js/custom.js"></script>
    
<!-- ############################ GENERICO ################################## -->
<? if ($this != null && $this->slug != null && file_exists(SERVER_URL . '/public/js/' . $this->slug . '.js')): ?>
    <script type="text/javascript" src="<? echo(URL_PUBLIC); ?>public/js/<? echo($this->slug); ?>.js"></script>
<? endif; ?>
<!-- ############################ END GENERICO ############################## -->