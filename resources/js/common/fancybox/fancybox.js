

/**
 * -------------------------------------------
 * Jquery fancybox
 * -------------------------------------------
 *
 */
// Step 1: Create jQuery plugin
// ============================

//require('./../project/jquery.fancybox.min.js');
require('./jquery.fancybox.min.js');


$.fn.fancyMorph = function (opts) {

    let Morphing = function ($btn, opts) {
        let self = this;

        self.opts = $.extend({
            animationEffect: false,
            infobar: false,
            buttons: ['close'],
            smallBtn: false,
            touch: false,
            baseClass: 'fancybox-morphing',
            afterClose: function () {
                self.close();
            }
        }, opts);

        self.init($btn);
    };

    Morphing.prototype.init = function ($btn) {
        let self = this;

        self.$btn = $btn.addClass('morphing-btn');

        self.$clone = $('<div class="morphing-btn-clone" />')
            .hide()
            .insertAfter($btn);

        // Add wrapping element and set initial width used for positioning
        $btn.wrap('<span class="morphing-btn-wrap"></span>').on('click', function (e) {
            e.preventDefault();

            self.start();
        });

    };

    Morphing.prototype.start = function () {
        let self = this;

        if (self.$btn.hasClass('morphing-btn_circle')) {
            return;
        }

        // Set initial width, because it is not possible to start CSS transition from "auto"
        self.$btn.width(self.$btn.width()).parent().width(self.$btn.outerWidth());

        self.$btn.off('.fm').on("transitionend.fm webkitTransitionEnd.fm oTransitionEnd.fm MSTransitionEnd.fm", function (e) {

            if (e.originalEvent.propertyName === 'width') {
                self.$btn.off('.fm');

                self.animateBg();
            }

        }).addClass('morphing-btn_circle');

    };

    Morphing.prototype.animateBg = function () {
        let self = this;

        self.scaleBg();

        self.$clone.show();

        // Trigger repaint
        self.$clone[0].offsetHeight;

        self.$clone.off('.fm').on("transitionend.fm webkitTransitionEnd.fm oTransitionEnd.fm MSTransitionEnd.fm", function (e) {
            self.$clone.off('.fm');

            self.complete();

        }).addClass('morphing-btn-clone_visible');
    };

    Morphing.prototype.scaleBg = function () {
        let self = this;

        let $clone = self.$clone;
        let scale = self.getScale();
        let $btn = self.$btn;
        let pos = $btn.offset();

        $clone.css({
            top: pos.top + $btn.outerHeight() * 0.5 - ($btn.outerHeight() * scale * 0.5) - $(window).scrollTop(),
            left: pos.left + $btn.outerWidth() * 0.5 - ($btn.outerWidth() * scale * 0.5) - $(window).scrollLeft(),
            width: $btn.outerWidth() * scale,
            height: $btn.outerHeight() * scale,
            transform: 'scale(' + (1 / scale) + ')'
        });
    };

    Morphing.prototype.getScale = function () {
        let $btn = this.$btn,
            radius = $btn.outerWidth() * 0.5,
            left = $btn.offset().left + radius - $(window).scrollLeft(),
            top = $btn.offset().top + radius - $(window).scrollTop(),
            windowW = $(window).width(),
            windowH = $(window).height();

        let maxDistHor = (left > windowW / 2) ? left : (windowW - left),
            maxDistVert = (top > windowH / 2) ? top : (windowH - top);

        return Math.ceil(Math.sqrt(Math.pow(maxDistHor, 2) + Math.pow(maxDistVert, 2)) / radius);
    };

    Morphing.prototype.complete = function () {
        let self = this;
        let $btn = self.$btn;

        $.fancybox.open({src: $btn.data('src') || $btn.attr('href')}, self.opts);
    };

    Morphing.prototype.close = function () {
        let self = this;
        let $clone = self.$clone;

        self.scaleBg();

        $clone.one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function (e) {
            $clone.hide();

            self.$btn.removeClass('morphing-btn_circle');
        });

        $clone.removeClass('morphing-btn-clone_visible');

        $(window).off('resize.fm');
    };

    // Init
    this.each(function () {
        let $this = $(this);

        if (!$this.data("morphing")) {
            $this.data("morphing", new Morphing($this, opts));
        }

    });

    return this;
};
//require('jquery-fancybox/source/js/jquery.fancybox.pack.js');

$(document).ready(function () {
    fancybox();
    //initFancybox();
});

window['fancybox'] = function () {
    fancybox();
};
// https://fancyapps.com/fancybox/3/docs/#options
let fancybox = function () {
    $("[data-fancybox]").fancybox({
        animationDuration: 100,
        animationEffect: 'fade',
    });
    $("[data-morphing]").fancyMorph({
        hash: 'morphing'
    });
    $('.cl-group[data-fancybox]').fancybox({
        idleTime: false,
        baseClass: 'fancybox-custom-layout',
        margin: 0,
        gutter: 0,
        infobar: false,
        thumbs: {
            hideOnClose: false,
            parentEl: '.fancybox-outer'
        },
        touch: {
            vertical: false
        },
        buttons: [
            'close',
            'thumbs',
            // 'zoom',
            // 'download',
            // 'share'
        ],
        lang: 'ru',
        i18n: {
            'ru': {
                CLOSE: 'Закрыть',
                NEXT: 'Следущее',
                PREV: 'Предыдущее',
                ERROR: 'Ошибка загрузки. <br/> Попробуйте позже.',
                PLAY_START: 'Start slideshow',
                PLAY_STOP: 'Pause slideshow',
                FULL_SCREEN: 'Full screen',
                THUMBS: 'Все изображения',
                DOWNLOAD: 'Скачать',
                SHARE: 'Поделиться',
                ZOOM: 'Zoom'
            },
        },
        animationEffect: "fade",
        animationDuration: 300,
        transitionEffect: false,
        btnTpl: {

            download: '<a download data-fancybox-download class="fancybox-button fancybox-button--download" title="{{DOWNLOAD}}">' +
            '<svg viewBox="0 0 40 40">' +
            '<path d="M20,23 L20,8 L20,23 L13,16 L20,23 L27,16 L20,23 M26,28 L13,28 L27,28 L14,28" />' +
            '</svg>' +
            '</a>',
        },
        onInit: function (instance) {
            // Create new wrapping element, it is useful for styling
            // and makes easier to position thumbnails
            instance.$refs.inner.wrap('<div class="fancybox-outer"></div>');
            //console.log('init');
        },
        // Customize caption area - append an advertisement
        // caption : function( instance ) {
        //     let advert = '<div class="ad"><p><a href="//fancyapps.com/fancybox/">fancyBox3</a> - touch enabled, responsive and fully customizable lightbox script</p></div>';
        //     let caption = '<h3>Collection #162 – <br /> The Histographer</h3><p>This collection of photos, curated by The Histographer, is a collection around the concept of \'Autumn is here\'.</p><p><a href="https://unsplash.com/collections/curated/162" target="_blank">unsplash.com</a></p>';
        //
        //     return advert + caption;
        // }
    });
};

let initFancybox = function () {
    $('.fancybox').fancybox({
        scrolling: 'hidden',
        nextEffect: 'none',
        prevEffect: 'none',
        helpers: {
            overlay: {
                locked: false
            }
        },
        afterLoad: function () {
            let $fancyboxHeader = $('<div style="position: fixed; top: 5px; right: 70px;color: #fff; padding: 10px;"></div>');
            let $fancyboxNumberRight = $('<div style="text-align: right;">' + (this.index + 1) + ' из ' + this.group.length + '</div>');
            let $fancyboxImage = $('<div></div>');
            let $fancyboxName = $('<div></div>');

            let $fancyboxType = $('<div style="position: absolute; top: 0px; left: 100%; color: #fff; width: 100px; padding: 10px;"></div>');

            let $type = $('<div class="columns"><div class="column"><p><span class="icon"> <i class="fa fa-camera"></i> </span> <span>Canon PoserShot S95</span></p></div></div><div class="columns"><div class="column"><p><span class="icon"> <i class="fa fa-bolt"></i> </span> <span>10</span></p></div></div><div class="columns"><div class="column"><p><span class="icon"> <i class="fa fa-beer"></i> </span> <span>2l</span></p></div></div>');
            $fancyboxHeader
                .append($fancyboxNumberRight)
                .append($fancyboxImage)
                .append($fancyboxName);

            $fancyboxType
                .append($type);

            this.inner.prepend($fancyboxHeader);
            //this.inner.prepend( $fancyboxType );
            // this.content = '<h1>2. My custom title</h1>' + this.content.html();
        },
        tpl: {
            error: '<p class="fancybox-error">I want to display a different error meesage here.</p>',
            closeBtn: '<a class="fancybox-item fancybox-close"></a>',
            //next        : '<svg class="nav__icon"><use xlink:href="#icon-triangle"></use></svg>',
            //prev        : '<svg class="nav__icon"><use xlink:href="#icon-triangle"></use></svg>'
            next: '<a class="fancybox-nav fancybox-next"><span></span></a>',
            prev: '<a class="fancybox-nav fancybox-prev"><span></span></a>'
        }
    });
};
