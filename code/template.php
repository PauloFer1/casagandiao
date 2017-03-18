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
   
    <meta name="description" content="Casa Gandião">
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
            <li><a href="index.html">Home</a></li>
            <li><a href="menu.html">História</a></li>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Serviços <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="news-list.html">News List</a></li>
                <li><a href="news-detail.html">News Detail</a></li>
                <li><a href="shop-list.html">Shop List</a></li>
                <li><a href="shop-detail.html">Shop Detail</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="about.html">Galeria</a></li>
            <li><a href="gallery.html">Casas</a></li>
            <li><a href="gallery.html">Contatos</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div><!--/.container -->
    </nav><!--/.navbar -->
    
     <!-- :::::::::::::::::::::::::::::::::::: SNIPPETS ::::::::::::::::::::::::::::::::::::::: -->
            <?php
            if ($this->url() == BASE_URL . $language->url ) {

            } else { 
                if ($this->slug() == "404") {
                    $this->includeSnippet('404');
                } else {
                   header('Location: ' . BASE_URL . $language->url . 'funcionalidades/404');
                }
            }
            ?>
            <!-- :::::::::::::::::::::::::::::::::::: /SNIPPETS :::::::::::::::::::::::::::::::::::::::: -->

    <!-- Banner Section Begin -->
    <section class="banner" id="banner" style="background: url(<?php echo URL_PUBLIC; ?>public/themes/default/images/layout/bg1.jpg) center; background-size: cover;">
      <div class="overlay"></div>
      <div class="banner-content">
        <div class="container">
          <div class="col-md-6 banner-image">  
            <div class="banner-images-carousel carousel slide" data-ride="carousel" data-pause="false">
              <ol class="carousel-indicators">
                <li data-target=".banner-images-carousel" data-slide-to="0" class="active"></li>
               <!-- <li data-target=".banner-images-carousel" data-slide-to="1"></li> -->
              </ol> 
              <div class="carousel-inner" role="listbox">
                <div class="item active">
                  <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/layout/serv1.png" alt="">
                </div>
              <!--  <div class="item">
                  <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/layout/serv2.png" alt="">
                </div> -->
              </div>
            </div>
          </div>
          <div class="col-md-6 banner-description">
            <div class="banner-description-carousel carousel slide" data-ride="carousel" data-pause="false">
              <div class="carousel-inner">
                <div class="item active">
                  <div class="subtitle wow animated fadeIn" data-wow-delay="0.5s">
                    <p></p>
                  </div>
                  <div class="title wow animated fadeIn" data-wow-delay="1s">
                    <h1>Torne o seu dia ainda mais especial</h1>
                  </div>
                  <div class="description wow animated fadeIn" data-wow-delay="1.5s">
                    <p>Agende uma visita</p>
                  </div>
                  <div class="buttons">
                    <a href="#" class="def-btn">See More</a>
                  </div>
                </div>
                <!--
                <div class="item">
                  <div class="subtitle wow animated fadeIn" data-wow-delay="0.5s">
                    <p></p>
                  </div>
                  <div class="title wow animated fadeIn" data-wow-delay="1s">
                    <h1>Encontre-se com as nossas paisagens</h1>
                  </div>
                  <div class="description wow animated fadeIn" data-wow-delay="1.5s">
                    <p></p>
                  </div>
                  <div class="buttons">
                    <a href="#" class="def-btn">See More</a>
                  </div>
                </div>
                  -->
              </div>              
            </div><!-- /.carousel-inner -->
          </div><!-- /.banner-description -->
        </div><!-- /.container -->

        <div class="scroll-info scroll-to">
          <a href="#first-content">
            <div class="mouse-wheel-icon">
              <div class="wheel"></div>
            </div>
          </a>
        </div><!--/.scroll-info -->
      </div><!-- /.banner-content -->
    </section><!-- /.banner -->
    <!-- Banner Section End -->
    
    <!-- About Section Begin -->
    <section class="about" id="first-content">
      <div class="container">
        <div class="col-md-6 text wow animated fadeIn" data-wow-delay="0.3s">
          <div class="title section-title">
            <h1>Um pouco sobre nós</h1>
            <div class="section-subtext">
              <div class="border-left"></div>
              <h2>Casa do Gandião</h2>
              <div class="border-right"></div>
            </div>
            <div class="title-separator-container">
              <div class="title-separator"></div>
            </div>
          </div>
          <div class="description">
            <p class="colored">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse nec felis id sem sollicitudin condimentum. Praesent tempus ligula a massa venenatis.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse nec felis id sem sollicitudin condimentum. Praesent tempus ligula a massa venenatis, eget suscipit nunc gravida. Etiam sapien risus, convallis at quam ut.</p>
          </div>

          <div class="text-center read-more-link"><a href="#" class="def-btn">Read More</a></div>
        </div><!-- /.text -->

        <div class="col-md-6 about-carousel-wrap wow animated fadeIn" data-wow-delay="0.6s">
          <div class="carousel-wrap">
            <div class="about-carousel">
              <div class="img-relative photo">
                <div class="photo">
                  <img alt="My Photo" src="<?php echo URL_PUBLIC; ?>public/themes/default/images/food-tp-1.png">
                </div>
              </div>
              <div class="img-relative photo">
                <div class="photo">
                  <img alt="My Photo" src="<?php echo URL_PUBLIC; ?>public/themes/default/images/food-tp-2.png">
                </div>
              </div>
              <div class="img-relative photo">
                <div class="photo">
                  <img alt="My Photo" src="<?php echo URL_PUBLIC; ?>public/themes/default/images/food-tp-3.png">
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.about-carousel -->
      </div><!-- /.container -->
    </section><!-- /.about -->
    <!-- About Section End -->

    <!-- Reservation Info Section Begin -->
    <section class="reservation-info no-padding" style="background: url(<?php echo URL_PUBLIC; ?>public/themes/default/images/bg2.jpg) center; background-size: cover; background-attachment: fixed;">
      <div class="overlay"></div>
      <div class="container">
       <div class="title section-title wow animated fadeIn">
          <h1>Opening Hour</h1>
          <div class="section-subtext">
            <div class="border-left"></div>
            <h2>We Are Open Everyday</h2>
            <div class="border-right"></div>
          </div>
        </div>
        
        <div class="col-md-6 col-md-offset-3">
          <div class="top-text wow animated fadeIn">
            <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse nec felis id sem sollicitudin condimentum.</div>
            <div class="info">Reservation Number</div> 
            <div class="contact-number">+11 22 33 44 55</div>
          </div>

          <div class="reservation-schedule wow animated fadeIn">
            <div class="left-section">
              <div class="title section-title">
                <h1>Monday to Friday</h1>
              </div>
              <div class="time textbold">
                09 : 00 
              </div>
              <div class="time textbold">
                22 : 00 
              </div>
            </div>
            <div class="separator"></div>
            <div class="right-section">
              <div class="title section-title">
                <h1>Monday to Friday</h1>
              </div>
              <div class="time textbold">
                09 : 00 
              </div>
              <div class="time textbold">
                24 : 00 
              </div>
            </div>
          </div><!-- /.reservation-schedule -->
        </div>
      </div><!-- /.container -->
    </section><!-- /.reservation -->
    <!-- Reservation Info Section End -->

    <!-- Menu Section Begin -->
    <section class="menu">
      <div class="decoration-right-top"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/bg-decoration-right2.png" alt=""></div>

      <div class="title section-title wow animated fadeIn">
        <h1>Our Menu</h1>
        <div class="section-subtext">
          <div class="border-left"></div>
          <h2>It's So Delicious</h2>
          <div class="border-right"></div>
        </div>        
        <div class="title-separator-container">
          <div class="title-separator"></div>
        </div>
      </div>

      <div class="container menu-list">
        <div class="col-md-4">
          <div class="title wow animated fadeIn">
            <h2>Appetizer</h2>
            <div class="title-separator-container">
              <div class="title-separator"></div>
            </div>
          </div><!-- /.title -->

          <div class="content">
            <div class="item wow animated fadeIn">
              <div class="price">$2</div>
              <div class="name"><h3>Putu Kalapa</h3></div>
              <div class="description"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et rhoncus diam, consectetur adipiscing elit.</p></div>
            </div><!-- /.item -->

            <div class="item wow animated fadeIn">
              <div class="price">$1</div>
              <div class="name"><h3>Ranginang</h3></div>
              <div class="description"><p>Aenean posuere placerat urna a auctor. Cras vitae mollis odio. Aenean posuere placerat. posuere placerat.</p></div>
            </div><!-- /.item -->

            <div class="item wow animated fadeIn">
              <div class="price">$3</div>
              <div class="name"><h3>Bala - Bala</h3></div>
              <div class="description"><p>Curabitur vitae magna viverra, imperdiet mauris et, fermentum massa. imperdiet mauris et.</p></div>
            </div><!-- /.item -->
          </div><!-- /.content -->
        </div><!-- /.col-md-4 -->

        <div class="col-md-4">
          <div class="title wow animated fadeIn">
            <h2>Main Course</h2>
            <div class="title-separator-container">
              <div class="title-separator"></div>
            </div>
          </div><!-- /.title -->

          <div class="content">
            <div class="item wow animated fadeIn">
              <div class="price">$7</div>
              <div class="name"><h3>Batagor Kuah</h3></div>
              <div class="description"><p>Nullam at fringilla est. Sed nisi nulla, congue vehicula maximus vel, consectetur vulputate ex.</p></div>
            </div><!-- /.item -->

            <div class="item wow animated fadeIn">
              <div class="price">$9</div>
              <div class="name"><h3>Nasi Goreng</h3></div>
              <div class="description"><p>Nulla eu laoreet eros. Donec vel nibh eget nisl varius consequat nec quis elit. consectetur adipiscing</p></div>
            </div><!-- /.item -->

            <div class="item wow animated fadeIn">
              <div class="price">$5</div>
              <div class="name"><h3>Karedok Leunca</h3></div>
              <div class="description"><p> Integer rutrum, metus vel pulvinar dignissim, ex justo posuere ipsum, sed congue purus ipsum sit amet ex.</p></div>
            </div><!-- /.item -->
          </div><!-- /.content -->
        </div><!-- /.col-md-4 -->

        <div class="col-md-4">
          <div class="title wow animated fadeIn">
            <h2>Drinks</h2>
            <div class="title-separator-container">
              <div class="title-separator"></div>
            </div>
          </div><!-- /.title -->

          <div class="content">
            <div class="item wow animated fadeIn">
              <div class="price">$3</div>
              <div class="name"><h3>Bajigur</h3></div>
              <div class="description"><p>Curabitur vitae magna viverra, imperdiet mauris et, fermentum massa. imperdiet mauris et.</p></div>
            </div><!-- /.item -->

            <div class="item wow animated fadeIn">
              <div class="price">$4</div>
              <div class="name"><h3>Bandrek</h3></div>
              <div class="description"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et rhoncus diam, consectetur adipiscing elit.</p></div>
            </div><!-- /.item -->

            <div class="item wow animated fadeIn">
              <div class="price">$3</div>
              <div class="name"><h3>Es Kalapa</h3></div>
              <div class="description"><p>Nullam at fringilla est. Sed nisi nulla, congue vehicula maximus vel, consectetur vulputate ex.</p></div>
            </div><!-- /.item -->
          </div><!-- /.content -->
        </div><!-- /.col-md-4 -->

        <div class="col-md-12">
          <div class="show-more text-center">
            <div class="more-menu">
              <a href="portfolio-list.html" class="def-btn">Complete List</a>
            </div>
          </div>   
        </div>     
      </div>
      <div class="decoration-left-bottom"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/bg-decoration-left.png" alt=""></div>
    </section>
    <!-- Menu Section End -->


    <!-- Testimonial Section Begin -->
    <section class="testimonial no-padding" style="background: url(<?php echo URL_PUBLIC; ?>public/themes/default/images/bg3.jpg) center; background-size: cover; background-attachment: fixed;">
      <div class="overlay"></div>

      <div class="container">
        <div class="title section-title wow animated fadeIn">
          <h1>Testimonial</h1>
          <div class="section-subtext">
            <div class="border-left"></div>
            <h2>What People Says</h2>
            <div class="border-right"></div>
          </div>
          <div class="title-separator-container">
            <div class="title-separator"></div>
          </div>
        </div>

        <div class="col-md-6 col-md-offset-3">
          <div id="testimonial-carousel" class="carousel slide" data-ride="carousel">   
            <div class="carousel-inner wow animated fadeIn" role="listbox">
              <div class="item active">
                <div class="testimonial-box">
                  <div class="testimonial-header">
                    <div class="testimonial-photo">
                      <img class="img-circle" src="<?php echo URL_PUBLIC; ?>public/themes/default/images/person1.jpg" alt="">
                    </div>
                    <div class="testimonial-headtext"> 
                      <div class="testimonial-name"><h4>Pluto Lorenzo</h4></div>
                      <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                      </div>
                    </div>
                  </div>

                  <div class="testimonial-text">                    
                    <p>
                      Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vivamus rutrum massa et sagittis consectetur. 
                    </p>                    
                  </div>
                </div>
              </div><!--/.item -->

              <div class="item">
                <div class="testimonial-box">
                  <div class="testimonial-header">
                    <div class="testimonial-photo">
                      <img class="img-circle" src="<?php echo URL_PUBLIC; ?>public/themes/default/images/person2.jpg" alt="">
                    </div>
                    <div class="testimonial-headtext"> 
                      <div class="testimonial-name"><h4>Sabela Emilia</h4></div>
                      <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                      </div>
                    </div>
                  </div>

                  <div class="testimonial-text">                    
                    <p>
                      Praesent faucibus nisi eu ipsum sollicitudin gravida. Proin nec aliquet eros. Phasellus vel tempor enim. Integer a orci nec ante pharetra rhoncus. Maecenas quam est.
                    </p>                    
                  </div>
                </div>

              </div><!--/.item -->
            </div><!--/.carousel-inner -->


            <!-- Controls -->
            <a class="left carousel-control" href="#testimonial-carousel" role="button" data-slide="prev">
              <i class="fa fa-angle-left"></i>
            </a>
            <a class="right carousel-control" href="#testimonial-carousel" role="button" data-slide="next">
              <i class="fa fa-angle-right"></i>
            </a>
          </div>

        </div>
      </div><!-- /.container -->

    </section><!-- /.testimonial -->
    <!-- Testimonial Section End -->


    <!-- Reservation Section Begin -->
    <section class="reservation-wrap no-padding" style="background: url(<?php echo URL_PUBLIC; ?>public/themes/default/images/banner4.jpg) center; background-size: cover; background-attachment: fixed;">
      <div class="overlay overlay-noised"></div>

      <div class="container content">
        
        <div class="col-md-offset-1 col-md-10 wow animated fadeInUp">
          <div class="reservation-form">
            <div class="ornament top-left-ornament">
              <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/top-left-ornament.png" alt="">
            </div>
            <div class="ornament top-right-ornament">
              <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/top-right-ornament.png" alt="">
            </div>
            <div class="ornament bottom-left-ornament">
              <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/bottom-left-ornament.png" alt="">
            </div>
            <div class="ornament bottom-right-ornament">
              <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/bottom-right-ornament.png" alt="">
            </div>

            <div class="title section-title">
              <h1>Reservation</h1>
              <div class="section-subtext">
                <div class="border-left"></div>
                <h2>Make A Reservation</h2>
                <div class="border-right"></div>
              </div>        
              <div class="title-separator-container">
                <div class="title-separator"></div>
              </div>
            </div><!--/.title -->

            <div class="info">Monday to Friday 09:00 to 22:00, Saturday &amp; Friday 09:00 to 01:00 </div>
            <div class="contact-number">+11 22 33 44 55</div>

            <form>
              <div class="col-md-6">
                <div class="form-title"><h3>Book A Table</h3></div>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><i class="flaticon-calendar-icons"></i></span>
                  <input type="text" class="form-control" placeholder="mm/dd/yyyy">
                </div>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><i class="flaticon-clock104"></i></span>
                  <input type="text" class="form-control" placeholder="--:--:--">
                </div>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><i class="flaticon-margarita"></i></span>
                  <input type="text" class="form-control" placeholder="Party">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-title"><h3>Contact Info</h3></div>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><i class="flaticon-avatar83"></i></span>
                  <input type="text" class="form-control" placeholder="Name">
                </div>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><i class="flaticon-new100"></i></span>
                  <input type="text" class="form-control" placeholder="Email">
                </div>
                <div class="input-group input-group-lg">
                  <span class="input-group-addon"><i class="flaticon-telephone60"></i></span>
                  <input type="text" class="form-control" placeholder="Phone">
                </div>
              </div><!-- /.col-lg-6 -->

              <div class="col-md-12">
                <textarea class="textarea form-control def-textarea" placeholder="Message" rows="10"></textarea>

                <div class="button-outer text-center">
                  <div class="button-inner">
                    <a href="portfolio-list.html" class="def-btn">Send Reservation</a>
                  </div>
                </div>
              </div>
            </form><!--/.form -->
          </div><!--/.reservation-form -->
        </div><!--/.col-md-10 -->
      </div><!--/.container -->
    </section><!--/.reservation-wrap -->
    <!-- Reservation Section Begin -->
    
    <!-- News Section Begin -->
    <section class="news">
      <div class="container">
        <div class="title section-title wow animated fadeIn">
          <h1>News List</h1>
          <div class="section-subtext">
            <div class="border-left"></div>
            <h2>Keep Update</h2>
            <div class="border-right"></div>
          </div>
          <div class="title-separator-container">
            <div class="title-separator"></div>
          </div>
        </div>
      </div><!--/.container -->
        
      <div class="news-wrap">
        <div class="news-bg" style="background: url(<?php echo URL_PUBLIC; ?>public/themes/default/images/banner5.jpg) center; background-size: cover; background-attachment: fixed;">
          <div class="overlay"></div>
        </div>
        
        <div class="container">

          <div class="news-container">
            <div class="news-row">
              <article class="col-md-6 no-padding">
                <div class="col-md-6 text-wrap">
                  <div class="title"><h3>New Strawberry soup</h3></div>
                  <div class="text"><p>Proin scelerisque erat at euismod vestibulum. Integer facilisis rutrum ante, sit amet sollicitudin enim. Fusce et est tellus. Nullam vitae arcu pellentesque.</p></div>
                  <div class="round-shape"><i class="fa fa-angle-right"></i></div>
                </div>
                <div class="col-md-6 image">
                  <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/news1.jpg" alt="">
                  <a href="news-detail.html" class="link"><i class="fa fa-search"></i></a>
                </div>
              </article>
              
              <article class="col-md-6 no-padding">
                <div class="col-md-6 text-wrap">
                  <div class="title"><h3>Get A Discount</h3></div>
                  <div class="text"><p>Suspendisse ut lorem molestie, luctus tortor a, porta mauris. Vestibulum quis consequat eros. Vestibulum id erat tellus. Sed ut tellus dictum, pharetra nisi ac, hendrerit purus. </p></div>
                  <div class="round-shape"><i class="fa fa-angle-right"></i></div>
                </div>
                <div class="col-md-6 image">
                  <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/news2.jpg" alt="">
                  <a href="news-detail.html" class="link"><i class="fa fa-search"></i></a>
                </div>
              </article>
            </div>
          </div><!--/.news-container -->

          <div class="news-container">
            <div class="news-row">
              <article class="col-md-6 no-padding">
                <div class="col-md-6 image">
                  <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/news3.jpg" alt="">
                  <a href="news-detail.html" class="link"><i class="fa fa-search"></i></a>
                </div>
                <div class="col-md-6 text-wrap reverse">
                  <div class="round-shape"><i class="fa fa-angle-left"></i></div>
                  <div class="title"><h3>Diet Diva</h3></div>
                  <div class="text"><p>Vestibulum lacus risus, finibus eu condimentum aliquam, porttitor sit amet neque. Vestibulum vitae diam eu leo gravida viverra hendrerit et metus.</p></div>
                </div>
              </article>
              <article class="col-md-6 no-padding">
                <div class="col-md-6 image">
                  <img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/news4.jpg" alt="">
                  <a href="news-detail.html" class="link"><i class="fa fa-search"></i></a>
                </div>
                <div class="col-md-6 text-wrap reverse">
                  <div class="round-shape"><i class="fa fa-angle-left"></i></div>
                  <div class="title"><h3>Food Photography</h3></div>
                  <div class="text"><p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vivamus rutrum massa et sagittis consectetur. </p></div>
                </div>
              </article>
            </div>
          </div><!--/.news-container -->

        </div><!--/.container-->
      </div><!--/.news-wrap -->
    </section><!--/.news -->
    <!-- News Section End -->

    <!-- Visit Us Section Start  -->
    <section class="visit-us no-padding dark">
      <div class="title wow animated fadeIn"><h3>Faça-nos uma visita</h3></div>
      <div class="text wow animated fadeIn">Estamos sempre disponíveis para si!</div>

      <div class="section-bottom-shape"><div class="shape dark"><i class="fa fa-angle-down"></i></div></div>
    </section>
    <!-- Visit Us Section End  -->

    <!-- Map Section Begin  -->
    <section class="map no-padding">
      <div class="map-canvas" id="map-canvas"></div>
    </section>
    <!-- Map Section End  -->

    <!-- Footer Section Begin  -->
    <section class="footer">
      <div class="container">

        <div class="col-md-3">
          <div class="footer-title"><h3>About Us</h3></div>
          <div class="footer-content"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur mattis mi vel nunc posuere vestibulum. Sed vitae vehicula leo. Pellentesque tempus. </p></div>
          <div class="footer-title second-title"><h3>Opening Hours</h3></div>
          <div class="footer-content">
            <ul class="opening-hours">
              <li><div class="day">Monday - Thursday</div><div class="time">9:00 - 23:00</div></li>
              <li><div class="day">Friday</div><div class="time">9:00 - 1:00</div></li>
              <li><div class="day">Saturday - Sunday</div><div class="time">9:00 - 00:00</div></li>
            </ul>
          </div>
        </div><!--/.col-md-3 -->

        <div class="col-md-3">
          <div class="footer-title"><h3>Twitter Feed</h3></div>
          <div class="footer-content">
            <ul class="recent-post">
              <li>
                <div class="thumbnail"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/footer-image1.jpg" alt="blog thumbnail"></div>
                <div class="recent-post-content">
                  <div class="text"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
                  <div class="date">September, 7th 2014</div>
                </div>
              </li>
            </ul>

            <ul class="recent-post">
              <li>
                <div class="thumbnail"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/footer-image2.jpg" alt="blog thumbnail"></div>
                <div class="recent-post-content">
                  <div class="text"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
                  <div class="date">September, 7th 2014</div>
                </div>
              </li>
            </ul>

            <ul class="recent-post">
              <li>
                <div class="thumbnail"><img src="<?php echo URL_PUBLIC; ?>public/themes/default/images/footer-image3.jpg" alt="blog thumbnail"></div>
                <div class="recent-post-content">
                  <div class="text"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
                  <div class="date">September, 7th 2014</div>
                </div>
              </li>
            </ul>
          </div>
        </div><!--/.col-md-3 -->

        <div class="col-md-2">
          <div class="footer-title"><h3>Contact Us</h3></div>
          <div class="footer-content">
            <div class="footer-form">
              <form>
                <input type="text" class="form-control" placeholder="Name">
                <input type="text" class="form-control" placeholder="Email">
                <textarea class="form-control" placeholder="Your Message"></textarea>
              </form>
              <div class="def-btn">Send Message</div>
            </div>
          </div>
        </div><!--/.col-md-3 -->

      </div><!-- /.container -->
    </section><!-- /.footer -->
    <!-- Footer Section End  -->
    
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
