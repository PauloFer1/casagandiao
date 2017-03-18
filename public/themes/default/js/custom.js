$(document).ready(function() {

  "use strict";

  // -------------- Preloader -------------- 
  $(".preloader").addClass('animated fadeOut');
  setTimeout(function(){
    $(".preloader").addClass('loaded');
  }, 1000);

  // -------------- Scroll to content animation -------------- 
  $(".scroll-to a[href^='#']").on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({
        scrollTop: $(this.hash).offset().top-70
    }, 1000);
  });

  // -------------- Slick -------------- 
  $('.about-carousel').slick({
    centerMode: true,
    centerPadding: '0px',
    vertical: true,
    verticalSwiping: true,
    slidesToShow: 1,
    prevArrow:'<div class="control center control-top"><div class="control-wrap"><button class="def-btn prev"><i class="fa fa-chevron-up"></i></button><img src="assets/images/btn-decoration-bottom.png" alt=""></div></div>',
    nextArrow:'<div class="control center control-bottom"><div class="control-wrap"><img src="assets/images/btn-decoration-top.png" alt=""><button class="def-btn next"><i class="fa fa-chevron-down"></i></button></div></div>',

    responsive: [
      {
        breakpoint: 768,
        settings: {
          arrows: true,
          centerMode: true,
          centerPadding: '0px',
          slidesToShow: 1,
          adaptiveHeight: true,
          verticalSwiping: true,
        }
      }    
    ]
  });


  $('.team-slider').slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 4,
    arrows: false,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });

  // -------------- On Scroll Navbar Effect -------------- 
  var window_width = $(window).width();

  $(window).on('scroll', function(){  
    "use strict"; 
    var scroll = $(window).scrollTop();
    if( scroll > 60 ){
      $(".navbar").addClass("scroll-fixed-navbar");
    } else {
      $(".navbar").removeClass("scroll-fixed-navbar");
    }
  });


  $('.showup-image .bottom-shape-wrap').on('mouseenter',function() { 
    $(this).toggleClass('show');
    $(".enlarge-image").toggleClass('hide');
  });

  $('.showup-image .bottom-shape-wrap').on('mouseleave', function(){
    $(this).removeClass("show");
  });

  // -------------- Team Hover --------------
  $('.team').on('mouseover', function(){
    $(this).find('.overlay').addClass('animated fadeInDown');
  });

  $('.team').on('mouseleave', function(){
    $(this).find('.overlay').removeClass('animated fadeInDown');
  });

  // -------------- Jquery WOW (reveal content when scroll) -------------- 
  var wow = new WOW(
    {
      animateClass: 'animated',
      offset:       100,
      mobile:       false
    }
  );
  wow.init();

  // -------------- Shop Amount Control -------------- 
  $(".amount-plus").on('click', function(){
    var $amountval = $(".amount-value");
    $amountval.val( + $amountval.val() + 1 );
  });

  $(".amount-minus").on('click', function(){

    var $amountval = $(".amount-value");

    if ((parseInt($amountval.val(), '8') > 1) && (!(parseInt($amountval.val(), '8') < 0))) {
      $amountval.val( + $amountval.val() - 1 );      
    }
  });

  // -------------- Shoping Cart -------------- 
  (function($) {
    $.fn.clickToggle = function(func1, func2) {
        var funcs = [func1, func2];
        this.data('toggleclicked', 0);
        this.on('click', function() {
            var data = $(this).data();
            var tc = data.toggleclicked;
            $.proxy(funcs[tc], this)();
            data.toggleclicked = (tc + 1) % 2;
        });
        return this;
    };
  }(jQuery));

  $('.float-cart').clickToggle(function() {    
    $('.cart-form').animate({right: '60px'}, 'slow').addClass('cart-form-visible');
    $('.cart-number').addClass('cart-number-hide hide');
  }, function() {
    $('.cart-form-visible').animate({right: '-100%'}, 'slow').removeClass('cart-form-visible');
    $('.cart-number-hide').removeClass('cart-number-hide hide');
  });

  // -------------- Jquery Isotope Setting -------------- 
  var $container = $('.gallery-image-container');
  $container.isotope({
    filter: '*',
    animationOptions: {
      duration: 750,
      easing: 'linear',
      queue: false
    }
  });

  $('.filter a').on('click', function(){
    $('.filter .current').removeClass('current');
    $(this).addClass('current');

    var selector = $(this).attr('data-filter');
    $container.isotope({
      filter: selector,
      animationOptions: {
        duration: 750,
        easing: 'linear',
        queue: false
      }
     });
     return false;
  });

  $('.fancybox').fancybox({
    helpers: {
      overlay: {
        locked: false
      }
    }
  });


  // -------------- Form Control Hover -------------- 
  $('.form-control').focus(function() { 
    $('.input-group .input-group-addon').removeClass("focused");
    $(this).prev().toggleClass("focused");
  });

  // -------------- Give Rating Hover -------------- 
  $('.give-rating i').mouseenter(function(){
    $(this).siblings("i").andSelf().removeClass("selected");
    $(this).siblings("i").andSelf().slice(0, $(this).index() + 1).addClass("selected");
  });

  // -------------- Jquery Masonry -------------- 
  var $container = $('.masonry-container');

  $container.imagesLoaded( function() {
    $container.masonry({
      columnWidth: '.masonry',
      itemSelector: '.masonry'
    });
  });
});


$(window).load(function(){  

  //  -------------- Sly.js (Vertical scrolling news ticker)  --------------
  $(function () {
    var frame  = $('#smart');
    var wrap   = frame.parent();

    // Call Sly on frame
    frame.sly({
      itemNav: "basic",
      smart: 1,
      activateOn: "click",
      mouseDragging: 1,
      touchDragging: 1,
      releaseSwing: 1,
      startAt: 0,
      scrollBy: 0,
      activatePageOn: "click",
      speed: 300,
      elasticBounds: 1,
      easing: 'easeOutExpo',
      dragHandle: 1,
      dynamicHandle: true,
      clickBar: 1,
      prev: wrap.find(".prev"),
      next: wrap.find(".next")
    });
  }());

});
