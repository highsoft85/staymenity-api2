import {multiselectActions} from "../../common/multiselect/actions";


$(document).ready(function ($) {
    searchCollapse.init();
});

/**
 *
 */
let searchCollapse = {

    $container: null,
    $submit: $('.--search-bar-submit'),
    $reset: $('.--search-bar-reset'),

    /**
     * Инициализация событий
     */
    init() {
        let self = this;
        self.$container = $('#search-bar');
        let hash = window.location.hash;
        if (hash === '#search-bar') {
            self.$container.collapse('show');
            self.afterShow();
        }
        if (self.$container.hasClass('show')) {
            self.afterShow();
        }

        self.$container.on('show.bs.collapse', function () {
            self.afterShow();
        }).on('hide.bs.collapse', function () {
            self.afterHide();
        });

        self.$reset.on('click', function () {
            $('#search-bar-form select').each(function () {
                if ($(this).hasClass('selectpicker')) {
                    multiselectActions.setEmpty($(this));
                } else {
                    $(this).val('');
                }
            });
            $('#search-bar-form .datetimepicker').each(function () {
                $(this).val('');
                $(this).trigger('dp.hide');
            });
            $('#search-bar-form .ajax-input').each(function () {
                $(this).val('');
                $(this).trigger('change');
            });
            self.$submit.trigger('click');
        });
    },

    /**
     * Сама кнопка
     * @returns {jQuery|HTMLElement}
     */
    button() {
        let self = this;
        return $('a[data-toggle="collapse"][href="#' + self.$container.attr('id') + '"]');
    },

    /**
     * После показа
     */
    afterShow() {
        let self = this;
        let $icon = self.button().find('.fa');
        $icon.removeClass('fa-search');
        $icon.addClass('fa-times');
        self.$submit.removeClass('hidden');
        self.$reset.removeClass('hidden');
    },

    /**
     * После скрытия
     */
    afterHide() {
        let self = this;
        let $icon = self.button().find('.fa');
        $icon.addClass('fa-search');
        $icon.removeClass('fa-times');
        self.$submit.addClass('hidden');
        self.$reset.addClass('hidden');
    },
};
