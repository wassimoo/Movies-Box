$(document).ready(function () {
    'use strict';

    /*-----------------------------------------------------
    Navbar Toggle for Mobile
    ------------------------------------------------------*/
    function navbarCollapse() {
        if ($(window).width() < 992) {
            $(document).on('click', function (event) {
                var clickover = $(event.target);
                var _opened = $("#main-nav-collapse").hasClass("in");
                if (_opened === true && !(clickover.is('.dropdown, #main-nav-collapse input, #main-nav-collapse button, #main-nav-collapse .fa, #main-nav-collapse select'))) {
                    $("button.navbar-toggle").trigger('click');
                }
            });

            $('.dropdown').unbind('click');
            $('.dropdown').on('click', function () {
                $(this).children('.dropdown-menu').slideToggle();
            });
        }
    }
    navbarCollapse();

    /*-----------------------------------------
    Mobile dropdown toggle
    -----------------------------------------*/
    function dropdownToggle() {
        if ($(window).width() < 992) {
            $('.navbar-toggle').css('display', 'block');
            $('.navbar-collapse').css('display', 'none');

            $('.dropdown').unbind('click');

            $('.dropdown').on('click', function (dd) {
                dd.stopPropagation();
                $(this).children('.dropdown-menu').slideToggle();
            });
        } else {
            $('.navbar-toggle').css('display', 'none');
            $('.navbar-collapse').css('display', 'block');
        }
    }

    dropdownToggle();

    /*-----------------------------------------
    Header Slider 
    -----------------------------------------*/
    $('#banner-slider').owlCarousel({
        singleItem: true,
        slideSpeed: 200,
        autoPlay: 3000,
        stopOnHover: true,
        navigation: false,
        pagination: true,
        paginationNumbers: true,
    });

    /*-----------------------------------------
    Video Carousel 
    -----------------------------------------*/
    $('.video-carousel').owlCarousel({
        items: 4,
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [991, 3],
        itemsTablet: [767, 3],
        itemsMobile: [479, 1],
        slideSpeed: 200,
        navigation: true,
        navigationText: ['<i class=\"fa fa-angle-left\"></i>', '<i class=\"fa fa-angle-right\"></i>'],
        pagination: false,
    });

    /*-----------------------------------------
    Single Gallery Slider
    -----------------------------------------*/
    $('.single-gallery-slider').owlCarousel({
        singleItem: true,
        slideSpeed: 200,
        autoPlay: 3000,
        stopOnHover: true,
        navigation: true,
        navigationText: ['<i class=\"fa fa-angle-left\"></i>', '<i class=\"fa fa-angle-right\"></i>'],
        pagination: false,
    });

    /*-----------------------------------------
    Magnific Popup
    -----------------------------------------*/
    $('.image-large').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
    $('.play-video').magnificPopup({
        type: 'iframe'
    });
    $.extend(true, $.magnificPopup.defaults, {
        iframe: {
            patterns: {
                youtube: {
                    index: 'youtube.com/',
                    id: 'v=',
                    src: 'http://www.youtube.com/embed/%id%?autoplay=1'
                }
            }
        }
    });

    /*==========================================================
		Newletter Subscribe	
	==========================================================*/
    $(".subscription").ajaxChimp({
        callback: mailchimpResponse,
        url: "http://codepassenger.us10.list-manage.com/subscribe/post?u=6b2e008d85f125cf2eb2b40e9&id=6083876991" // Replace your mailchimp post url inside double quote "".  
    });

    function mailchimpResponse(resp) {
        if (resp.result === 'success') {

            $('.newsletter-success').html(resp.msg).fadeIn().delay(3000).fadeOut();

        } else if (resp.result === 'error') {
            $('.newsletter-error').html(resp.msg).fadeIn().delay(3000).fadeOut();
        }
    }

    /*-----------------------------------------
    Contact from validation
    -----------------------------------------*/

    // Function for email address validation
    function isValidEmail(emailAddress) {
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

        return pattern.test(emailAddress);

    }
    $("#contactForm").on('submit', function (e) {
        e.preventDefault();
        var data = {
            name: $("#name").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            subject: $("#subject").val(),
            message: $("#message").val()
        };

        if (isValidEmail(data['email']) && (data['message'].length > 1) && (data['name'].length > 1) && (data['subject'].length > 1)) {
            $.ajax({
                type: "POST",
                url: "sendmail.php",
                data: data,
                success: function () {
                    $('#contactForm .input-success').delay(500).fadeIn(1000);
                    $('#contactForm .input-error').fadeOut(500);
                }
            });
        } else {
            $('#contact-form .input-error').delay(500).fadeIn(1000);
            $('#contact-form .input-success').fadeOut(500);
        }

        return false;
    });

    /*-----------------------------------------
    All window event
    -----------------------------------------*/
    $(window).on('resize orientationchange', function () {
        dropdownToggle();
        navbarCollapse();
    });
});

/*-----------------------------------------
Preloader
-----------------------------------------*/
$(window).on('load', function () {
    $('#preloader').fadeOut('slow');
});
