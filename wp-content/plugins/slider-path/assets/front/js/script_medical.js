(function($) {
	
	"use strict";


    if ($('.banner-carousel-one').length) {
        $('.banner-carousel-one').owlCarousel({
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            loop:true,
            margin:0,
            dots: true,
            nav:true,
            singleItem:true,
            smartSpeed: 500,
            autoplay: true,
            autoplayTimeout:6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1024:{
                    items:1
                }
            }
        });         
    }

    if ($('.banner-carousel-two').length) {
        $('.banner-carousel-two').owlCarousel({
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            loop:true,
            margin:0,
            dots: true,
            nav:true,
            singleItem:true,
            smartSpeed: 500,
            autoplay: true,
            autoplayTimeout:6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1024:{
                    items:1
                }
            }
        });         
    }

    if ($('.banner-carousel-three').length) {
        $('.banner-carousel-three').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            active: true,
            smartSpeed: 1000,
            autoplay: 6000,
            navText: [ '<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1200:{
                    items:1
                }
            }
        });         
    }
 

    if ($('.banner-carousel-four').length) {
        $('.banner-carousel-four').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            active: true,
            smartSpeed: 500,
            autoplay: 6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1200:{
                    items:1
                }
            }
        });         
    }

    if ($('.banner-carousel-five').length) {
        $('.banner-carousel-five').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            active: true,
            smartSpeed: 500,
            autoplay: 6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1200:{
                    items:1
                }
            }
        });         
    }

    if ($('.banner-carousel-six').length) {
        $('.banner-carousel-six').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            active: true,
            smartSpeed: 500,
            autoplay: 6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1200:{
                    items:1
                }
            }
        });         
    }

    if ($('.banner-carousel-seven').length) {
        $('.banner-carousel-seven').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            active: true,
            smartSpeed: 500,
            autoplay: 6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1200:{
                    items:1
                }
            }
        });         
    }

    if ($('.banner-carousel-eight').length) {
        $('.banner-carousel-eight').owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            active: true,
            smartSpeed: 500,
            autoplay: 6000,
            navText: [ '<span class="flaticon-back"></span>', '<span class="flaticon-right"></span>' ],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1200:{
                    items:1
                }
            }
        });         
    }

    function onHoverthreeDmovement() {
        var tiltBlock = $('.js-tilt');
        if(tiltBlock.length) {
            $('.js-tilt').tilt({
                maxTilt: 20,
                perspective:5000, 
                glare: true,
                maxGlare: 0
            })
        }
    }

    $(document).ready(function() {
      $('select:not(.ignore)').niceSelect();
    });
    
    jQuery(document).on('ready', function () {
        (function ($) {
            onHoverthreeDmovement();
        })(jQuery);
    });

})(window.jQuery);
