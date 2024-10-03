var Slideout = require('slideout');
var wow = require('wowjs');
window.Popper = require('popper.js').default;

jQuery(function() {
    
    /**
     * Slideshow
     * https://github.com/kenwheeler/slick/
     */
    jQuery('.slideshow .slides').slick({
        arrows: false,
        fade: true,
        autoplay: true,
        autoplaySpeed: 4000,
    });
    
    /**
     * Scroll to a DOM element
     */
    jQuery('[data-scrollto]').on('click', function(e) {
        e.preventDefault();
        var target = jQuery(jQuery(this).data('scrollto'));

        jQuery('html, body').animate({
            scrollTop: target.offset().top
        }, 'slow');
    });
    
    // Anchor links in blocks
    jQuery('.blocks a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = jQuery(jQuery(this).attr('href'));
        var extraSpacing = 95;

        jQuery('html, body').animate({
            scrollTop: target.offset().top - extraSpacing
        }, 'slow');
    });
    
    /**
     * Partner Logos
     * https://github.com/kenwheeler/slick/
     */
    jQuery('.partner-logos .slides').slick({
        arrows: false,
        autoplay: true,
        autoplaySpeed: 4000,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 560,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }
        ]
    });

    /**
     * Testimonials
     * https://github.com/kenwheeler/slick/
     */
    jQuery('.testimonials-slideshow .slides').slick({
        arrows: false,
        fade: false,
        autoplay: true,
        autoplaySpeed: 7000
    });
    jQuery('.testimonials-slideshow .slides').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var slide     = jQuery('.testimonials-slideshow .slides .slide').not('.slick-cloned').eq(nextSlide);
        var slideshow = slick.$slider.closest('.testimonials-slideshow');
        var link      = slideshow.find('.testimonials-carousel__link');

        // Background
        slideshow.find('.bkgd').eq(currentSlide).css('z-index', 1);
        slideshow.find('.bkgd').eq(nextSlide).css('z-index', 10).addClass('bkgd--active');
        setTimeout(function() {
            slideshow.find('.bkgd').eq(currentSlide).removeClass('bkgd--active');
        }, 400);
        

        // Link
        var link_url    = slide.data('link-url');
        var link_target = slide.data('link-target');
        var link_title  = slide.data('link-title');
        
        if (link_url) {
            link.attr('href', link_url);
            link.attr('target', link_target);
            link.find('span').text(link_title);
            link.removeClass('hidden');
        } else {
            link.addClass('hidden');
        }
    });
});

/**
 * Slideout Nav
 * https://github.com/mango/slideout
 */
var slideout = new Slideout({
    'panel': document.getElementById('app-panel'),
    'menu': document.getElementById('app-nav-mobile'),
    'padding': 320,
    'tolerance': 70,
    'side': 'right',
    'touch': false
});

document.querySelector('.nav-icon').addEventListener('click', function() {
    slideout.toggle();
    
    // Allow the nav to be closed by clicking on the body
    document.querySelector('.app-main').addEventListener('click', closeMobileNav);
});

document.querySelector('#app-nav-mobile .close-icon').addEventListener('click', closeMobileNav);

function closeMobileNav() {
    slideout.close();
    document.querySelector('.app-main').removeEventListener('click', closeMobileNav);
}
    
/**
 * WOW.js
 * Animate elements as they are scrolled into view
 * Don't forget to include https://github.com/daneden/animate.css
 *
 * https://github.com/matthieua/WOW
 */
new wow.WOW().init();

/**
 * Get a URL parameter
 */
window.getURLParameter = function(param) {
    return decodeURIComponent((new RegExp('[?|&]' + param + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}

/**
 * Mobile Brower Detection
 *
 * @return bool
 */
window.isMobileDevice = function() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
};
