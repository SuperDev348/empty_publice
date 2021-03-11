 
 
 
// header Fixed class
$(window).on("scroll", function(){   
var scroll = $(window).scrollTop();
if (scroll >= 2) {
    $(".main_header").addClass("dark_bg");
} else {
    $(".main_header").removeClass("dark_bg");
}
});

// header Menu Scroll down js
$(window).on("scroll", function(){ 
  var scrollPos = $(window).scrollTop();
  $('.main_menu_list li a').on("each", function(){  
    var currentElement = $(this);
    var currentTop =  $($(this).attr('href')).offset().top - 80;
    var currentHeight = $(currentElement.attr("href")).outerHeight();
    if ( currentTop <= scrollPos && (currentTop + currentHeight) > scrollPos ) {
            $(currentElement).parent().siblings().find('a').removeClass('active');
            $(currentElement).addClass('active');
        }
        else{
            $(currentElement).removeClass('active');
        }
});
});
$('.main_menu_list li a').on('click', function(e) {
  

  $('html, body').animate(
    {
      scrollTop: $($(this).attr('href')).offset().top - 77,
    },
    500,
    'linear'
  )
});


// Scroll up js
$(window).on("scroll", function(){
    if ($(this).scrollTop() >= 50) {       
        $('.scrollUp').fadeIn(200);   
    } else {
        $('.scrollUp').fadeOut(200);   
    }
});
$('.scrollUp').on('click', function() {
    $('body,html').animate({
        scrollTop : 0                       
    }, 800);
});

$(document).ready(function () {
// scroll Down js
$('.scroll_down').on('click', function(e) {
  e.preventDefault()
  $('html, body').animate({scrollTop: $('#about').offset().top - 77, },'slow')
});

$( ".sliding_link" ).on("click", function( event ){
    event.preventDefault();
    $("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 500);
});

 
 




// mobile menu toggle
$(".menu-bar").on('click', function() {
  $(this).addClass('active');
  $('.main_menu').addClass('opened');
});
$(".close_menu").on('click', function() {
  $('.menu-bar').removeClass('active');
  $('.main_menu').removeClass('opened');
});
});


 

// Main Slider Slide animation
function mainSlider() {
        "use strict";
        var BasicSlider = $('.banner_slider');
        BasicSlider.on('init', function(e, slick) {
            var $firstAnimatingElements = $('.b_slide').find('[data-animation]');
            doAnimations($firstAnimatingElements);
        });
        BasicSlider.on('beforeChange', function(e, slick, currentSlide, nextSlide) {
            var $animatingElements = $('.b_slide[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
            doAnimations($animatingElements);
        });    

        function doAnimations(elements) {
            var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            elements.each(function() {
                var $this = $(this);
                var $animationDelay = $this.data('delay');
                var $animationType = 'animated ' + $this.data('animation');
                $this.css({
                    'animation-delay': $animationDelay,
                    '-webkit-animation-delay': $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function() {
                    $this.removeClass($animationType);
                });
            });
        }
    }
    mainSlider();

if ($(".b_slide").hasClass(".slick-active") ) {
   alert("sfsd");
    $(this).find(".wow").removeClass('wow');
}



// Loader Js
$(window).on("load", function(){
    $(".loader").fadeOut("slow");
});


// go down
$(".go_down_arrow i.fa,.sliding_link").on('click', function() {
    $('html, body').animate({
        scrollTop: $("#main_body").offset().top
    }, 800);
});



 


// Teams slider js
$('.team_list').slick({
  dots: false,
  infinite: true,
  speed: 500,
  // fade:true,
  slidesToShow: 4,
  arrows:true,
  autoplay:true,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 479,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
}) 

// related items slider js
$('.related_items_slider').slick({
  dots: true,
  infinite: true,
  speed: 500,
  slidesToShow: 3,
  arrows:false,
  autoplay:true,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 479,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
}) 


// testimonials slider  js
$('.testi_slider').slick({
  dots: true,
  infinite: true,
  speed: 500,
  slidesToShow: 2,
  arrows:false,
  autoplay:true,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 479,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
}) 

// Partners slider  js
$('.partners_slider').slick({
  dots: false,
  infinite: true,
  speed: 500,
  slidesToShow: 5,
  arrows:false,
  autoplay:true,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 479,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
}) 

// Wow animation call
new WOW().init();





