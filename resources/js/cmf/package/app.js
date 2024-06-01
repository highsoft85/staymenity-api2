$(document).ready(function() {

    $('nav .nav-toggle').click(function() {
        if ($(this).hasClass('is-active')) {
            $(this).removeClass('is-active');
            $('.nav-menu').removeClass('is-active');
        } else {
            $(this).addClass('is-active');
            $('.nav-menu').addClass('is-active');
        }
    });
    $('.nav-fixed-sidebar .nav-toggle').click(function() {
        if ($(this).hasClass('is-active')) {
            $(this).removeClass('is-active');
            $(this).closest('.nav-fixed-sidebar').removeClass('is-active');
        } else {
            $(this).addClass('is-active');
            $(this).closest('.nav-fixed-sidebar').addClass('is-active');
        }
    });


    /*
     |----------------------------------------
     | Navigation dropdown menu
     |----------------------------------------
     */
    $('.dropdown-link li:first-child').click(function(event) {
        $('.dropdown-link').removeClass('is-tap-active');
        $(this).parent().addClass('is-tap-active');
    });

    /*
     |----------------------------------------
     | Dropdown menu
     |----------------------------------------
     */
    $('.drop-link').click(function(event) {
        if ($(this).hasClass('is-active')) {
            $(this).parent().children('.drop-menu').css('display','none');
            $(this).removeClass('is-active');
        }
        else {
            $('.drop-link').removeClass('is-active');
            $('.drop-menu').css('display','none');
            $(this).parent().children('.drop-menu').css('display','block');
            $(this).addClass('is-active');
        }
    });

    /*
     |----------------------------------------
     | Notification
     |----------------------------------------
     */
    $('#notification-trigger').click(function(event) {
        $(this).addClass('active');
        var notification = $(this).attr('data-notification');
        $('.notifications').css('display', 'block');
        $(notification).css('display', 'block');
    });
    $('.notifications').on('click', '.ns-close', function(event) {
        $(this).parent().css('display', 'none');
    });
    /*
     |----------------------------------------
     | Tabs switch menu photo
     |----------------------------------------
     */
    $('.tabs-menu li a').click(function(event) {
        if (!$(this).parent().hasClass('is-active')) {
            $('.tabs-menu li').removeClass('is-active');
            $(this).parent().addClass('is-active');

            var section = $(this).parent().attr('data-section');
            $('.tabs-content').removeClass('is-active');
            $(section).addClass('is-active');
        }
    });

    /*
     |----------------------------------------
     | Tabs switch menu photo
     |----------------------------------------
     */
    $('.tabs-edit-menu li a').click(function(event) {
        if (!$(this).parent().hasClass('is-active')) {
            $('.tabs-edit-menu li').removeClass('is-active');
            $(this).parent().addClass('is-active');

            var section = $(this).parent().attr('data-section');
            $('.tabs-edit-content').removeClass('is-active');
            $(section).addClass('is-active');
        }
    });

    $('img').on('error', function(){
        $(this).attr('src', '/img/cover.png');
        console.log('error');
    });
    $('input[type="password"]').keydown(function(e) {
        if (e.keyCode == 32) {
            return false;
        }
    });


    /**
     * Сайд бар, открытие подменю
     */
    $('.menu-list__button').click(function() {
        if (!$(this).hasClass('is-active')) {
            $('.menu-list .menu-list__button').removeClass('is-active');
            $(this).addClass('is-active');
        } else {
            $('.menu-list .menu-list__button').removeClass('is-active');
        }
    });

    /**
     * Аякс подгрузка страницы
     */
    $('.ajax-page a').click(function() {
        var url = $(this).closest('.ajax-page').data('url');
        $('#admin-content').children()
            .filter(function() {
                return (!$(this).hasClass('section--loader'));
            }).remove();
        $('.section--loader').css('display', 'block');
        $.ajax({
            url: url,
            type: "POST",
            success: function (result) {
                $('.section--loader').css('display', 'none');
                $('#admin-content').append(result);
            },
            error: function (data, status, headers, config) {
                $('.section--loader').css('display', 'none');
                console.log('error');
            }
        });
        return false;
    });

    $('.nav-fixed-sidebar-hide-show').click(function() {
        if ($(this).hasClass('is-active')) {
            $('.nav-fixed-sidebar').addClass('hide');
            $(this).removeClass('is-active');
        } else {
            $('.nav-fixed-sidebar').removeClass('hide');
            $(this).addClass('is-active');
        }
    });
});

