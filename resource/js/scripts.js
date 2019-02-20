$(document).ready(function(){
    $('.hero-carousel').slick({
        dots: true,
        infinite: true,
        speed: 1200,
        slidesToShow: 1,
        fade: true,
        autoplay: true,
        autoplaySpeed: 7000,
        pauseOnHover: false
    });

    $('.close-carousel').click(function (e) {
        e.preventDefault();
        $(this).parent().remove();
    });

    $('[data-click=scroll-to-target]').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).attr('href');
        var headerHeight = 50;
        $('html, body').animate({
            scrollTop: $(target).offset().top - headerHeight
        }, 500);
    });

    $('.btn-mobile-trigger').click(function (e) {
        e.preventDefault();
        $('.menu-holder').toggleClass('open');
    });
    
    $('.btn-close-menu').click(function (e) {
        e.preventDefault();
        $('.menu-holder').removeClass('open');
    });

    if ($(window).width() >= 767) {
        $(".navbar-form .form-control").focus(function(){
            $(this).parent().parent().addClass("full-width");
        }).blur(function(){
            $(this).parent().parent().removeClass("full-width");
        });
    };

    $('.sticky-item-trigger').click(function (e) {
        $(this).parent().siblings().removeClass('open');
        $(this).parent().siblings().children().removeClass('active');
        $(this).toggleClass('active');
        $(this).parent().toggleClass('open')
        e.preventDefault();
    });

    $('.sticky-bottom-trigger').click(function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $('.sticky-bottom-holder').toggleClass('open');
        if ($(".sticky-bottom-trigger").hasClass("active")) {
            $('.sticky-bottom-trigger span').html('close');
        } else {
            $('.sticky-bottom-trigger span').html('contributions');
        }
    });

    $('.btn-collapse-text').click(function (e) {
        $(this).addClass('hide').closest('.card-story').find('.collapse-text-holder').addClass('open');
        $(this).parent().addClass('active');
        e.preventDefault();
    });

    $(".sticky-left-holder").addClass("active");

    $(".btn-vote").on("click", function(){
        $('.sticky-bottom-trigger').click();
        $("#upvote").click();
    });
});
