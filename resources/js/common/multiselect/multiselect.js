
require('bootstrap-select/dist/js/bootstrap-select.js');
require('ajax-bootstrap-select/dist/js/ajax-bootstrap-select.js');
require('ajax-bootstrap-select/dist/js/locale/ajax-bootstrap-select.en-US.min.js');
require('bootstrap-select/dist/js/i18n/defaults-en_US.min.js');

import {multiselectActions} from './actions.js';

$(document).ready(function () {
    window['multiselect']();
});

window['multiselect'] = function () {
    $('#user_search').on('change', function () {
        if ($(this).val().length) {
            $.each(jQuery.parseJSON($(this).val()), function (key, value) {
                $( "input[name='" + key + "']" ).val(value);
            });
        }
    });
    $('.selectpicker:not(.with-ajax)').selectpicker({
        style: 'btn-multiselect',
        dropupAuto: false,
        size: 7
    }).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        multiselectActions.changed(e);
    }).on('loaded.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        let $select = $(e.currentTarget);
        if ($select.data('change')) {
            $select.trigger('change');
        }
    });

    $('.selectpicker.with-ajax').selectpicker({
        style: 'btn-multiselect',
        dropupAuto: false,
        size: 7,
    }).ajaxSelectPicker({
        ajax: {
            url: null,
            type: "POST",
            dataType: "json",
            data: function () {
                let result = {
                    q: '{{{q}}}',
                    selected: this.plugin.$element.data('selected'),
                };
                // let self = $(this.plugin.$element);
                // // Добавляем возможность передавать дополнительные параметры через атрибуты вида data-params_
                // $('.form-control.set_params').each(function () {
                //     if ($(this).attr('name') != undefined &&
                //         self.attr('id') === $(this).data('setToId')) {
                //         if ($(this).prop("checked") == undefined || $(this).prop("checked")) {
                //             result[$(this).attr('name')] = $(this).val();
                //         }
                //     }
                // });
                return result;
            }
        },
        locale: {
            emptyTitle: 'Empty',
        },
        log: 0,
        //preserveSelected: false,
        emptyRequest: true,
        preprocessData: function (data) {
            return multiselectActions.ajaxProcessData(data);
        }
    }).on('loaded.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        if (e.target.value !== '' && e.target.value !== ' ') {
            multiselectActions.ajaxLoaded(e);
        }
    }).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        multiselectActions.ajaxChanged(e);
        if (clickedIndex !== undefined) {
            multiselectActions.changed(e);
        }
    }).on('rendered.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        let $select = $(e.currentTarget);
        const $parent = $select.closest('.bootstrap-select');
        if ($parent.hasClass('ajax-select')) {
            $parent.removeClass('ajax-select');
        }
        if ($parent.hasClass('with-ajax')) {
            $parent.removeClass('with-ajax');
        }
    });
};
