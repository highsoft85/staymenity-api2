
require('./bootstrap-datetimepicker.js');

$(document).ready(function () {
    window['datepicker']();
});

window['datepicker'] = function () {
    if (typeof $.fn.datetimepicker !== 'undefined' && $('.datetimepicker').length) {
        $('.datetimepicker[data-format="date"]').datetimepicker({
            format: 'DD.MM.YYYY',
            locale: 'en',
        });
        $('.datetimepicker[data-format="datetime"]').datetimepicker({
            sideBySide: true,
            format: 'L HH:mm',
            locale: 'en',
        });
        $('.datetimepicker').each(function () {
            let $current = $(this);
            if ($current.data('parent')) {
                let $child = $current;
                let $parent = $('.datetimepicker[name="' + $child.data('parent') + '"]');
                // события изменений, чтобы нельзя было to выбирать раньше from
                $parent.on('dp.change', function (e) {
                    let valueBeforeMinDate = $child.val();
                    $child.data('DateTimePicker').minDate(e.date);
                    if (valueBeforeMinDate !== '' && $parent.val() !== '') {
                        $child.data('DateTimePicker').date(moment($child.val(), 'DD.MM.YYYY HH:mm'));
                    } else {
                        $child.data('DateTimePicker').date(null);
                    }
                });
                $parent.trigger('dp.change');
            }
        });
        $('.datetimepicker[data-format="time"]').datetimepicker({
            format: 'LT',
            locale: 'en',
        });
    }
};
