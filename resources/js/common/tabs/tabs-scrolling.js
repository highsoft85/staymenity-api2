require('jquery-bootstrap-scrolling-tabs/dist/jquery.scrolling-tabs.js');

$(function () {
    window['tabs-scrolling']();
});

window['tabs-scrolling'] = function () {

    $('.nav-tabs-scrolling-container .nav-tabs:not(.is-init)').scrollingTabs({
        bootstrapVersion: 4,
    }).on('ready.scrtabs', function() {
        $('.tab-content').css({
            visibility: 'visible',
        });
        $('.nav-tabs-scrolling-container .nav-tabs').addClass('is-init')
    });
};
