<!DOCTYPE html>
<?php
require_once(SERVER_URL . 'tarambola/app/classes/Language.php');
$language = Language::getInstance();
Language::getInstance()->initWithLang("pt");


if (isset($_GET['lang'])) {
    $lang_on = ($_GET['lang']);
} else {
    $lang_on = ("pt");
}
?>
<html lang="pt">
  
<!-- Mirrored from demo.suavedigital.com/Warnas/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Feb 2017 23:57:02 GMT -->
<head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="" />
        <meta name="keywords" content="<?php
        if ($this->keywords == '')
            echo $home->keywords;
        else
            echo $this->keywords;
        ?>" />
   
    <meta name="description" content="warnas: Awesome Cafe &amp; Restaurant Template">
    <meta name="author" content="Tarambola">
    <meta name="robots" content="index, follow"/>
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo URL_PUBLIC; ?>public/themes/default/images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo URL_PUBLIC; ?>public/themes/default/images/apple-icon-114x114.png">
    <link rel="shortcut icon" href="<?php echo URL_PUBLIC; ?>public/themes/default/images/favicon.ico" />

    <title>Casa do Gandião | <?php echo($this->title()); ?></title>
    
       <!-- CSS -->
        <?php $this->includeSnippet('cssAppend'); ?> 
        <!-- END CSS -->

    
  </head>

  <body>
    <div class="preloader">
      <div class="image-container">
        <div class="image"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/preloader.gif" alt=""></div>
      </div>      
    </div>
    
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="logo">
        <div class="relative-logo">
          <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/logo.png" alt="logo">
        </div>
      </div>
      
      <div class="container">
        <div class="navbar-header">
          <div class="responsive-logo visible-xs">
            <a href="index.html"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/logo-mobile.png" alt=""></a>
          </div>
          
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="">Home</a></li>
            <li><a href="<?php echo($language->url); ?>apresentacao.html">Apresentação</a></li>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Serviços <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="news-list.html">Arrendamento do salão</a></li>
                <li><a href="news-detail.html">Arrendamento casa rural</a></li>
                <li><a href="shop-list.html">Catering</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo($language->url); ?>galeria.html">Galeria</a></li>
            <li><a href="<?php echo($language->url); ?>casas.html">Casas</a></li>
            <li><a href="<?php echo($language->url); ?>contactos.html">Contatos</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div><!--/.container -->
    </nav><!--/.navbar -->
    
     <!-- :::::::::::::::::::::::::::::::::::: SNIPPETS ::::::::::::::::::::::::::::::::::::::: -->
            <?php
            if ($this->url() == BASE_URL . $language->url ) {
                    $this->includeSnippet('home');
            } else { 
                if ($this->slug() == "apresentacao") {
                    $this->includeSnippet('apresentacao');
                } else if ($this->slug() == "galeria") {
                    $this->includeSnippet('galeria');
                } else if ($this->slug() == "contactos") {
                    $this->includeSnippet('contactos');
                } else if ($this->slug() == "404") {
                    $this->includeSnippet('404');
                } else {
                   header('Location: ' . BASE_URL . $language->url . 'funcionalidades/404');
                }
            }
            ?>
            <!-- :::::::::::::::::::::::::::::::::::: /SNIPPETS :::::::::::::::::::::::::::::::::::::::: -->

    
    
    <!-- Copyright Section Begin -->
    <section class="copyright">
      <div class="top-shape scroll-to"><a href="#banner"><div class="shape"><i class="fa fa-angle-up"></i></div></a></div>
      <div class="container">
        <div class="left-section col-md-6">Casa do Gandião &copy; 2017 </div>
        <div class="right-section col-md-6">
          <ul class="social">
            <li><div class="icon"><i class="fa fa-facebook"></i></div></li>
            <li><div class="icon"><i class="fa fa-twitter"></i></div></li>
            <li><div class="icon"><i class="fa fa-google-plus"></i></div></li>
          </ul>
        </div>
      </div>
    </section><!--/.copyright -->
    <!-- Copyright Section End -->

    <!-- JAVASCRIPT BEGIN -->
    <?php $this->includeSnippet('jsAppend'); ?>
    <!-- JAVASCRIPT END -->
    
  </body>

<!-- Mirrored from demo.suavedigital.com/Warnas/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Feb 2017 00:04:56 GMT -->
</html>
