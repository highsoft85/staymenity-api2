$(document).ready(function() {

    $('.nav--desta').on('click', '.nav__item', function(event) {
        if (!$(this).hasClass('nav__item--current')) {
            $('.nav--desta .nav__item').removeClass('nav__item--current');
            $(this).addClass('nav__item--current');

            $('.mockup-article').removeClass('article--current');
            $($(this).data('article-id')).addClass('article--current');

            $('.mockup-slider').removeClass('mockup-slider--current');
            $($(this).data('article-class')).addClass('mockup-slider--current');
        }
    });

});