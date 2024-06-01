import {multiselectCommon} from './common.js';

/**
 * Смена журнала в редактировании выпуска
 *
 * @param $select
 * @param initial
 */
window['multiselect-releaseJournalChange'] = function($select, initial) {
    let data = [];
    let $form = $select.closest('form');
    let $nameInput = $select.closest('form').find('input[name="name"]');
    let journalName = $select.find('option[value="' + $select.val() + '"]').text();
    $nameInput.data('original', journalName);
    $nameInput.val(multiselectCommon.getReleaseName($form));

    let $yearSelect = $select.closest('form').find('select[name="year"]');
    multiselectCommon.setData(data, 'journal_id', $select.val());

    let yearSelectValue = $yearSelect.val();
    multiselectCommon.loadingStart($yearSelect);
    $.ajax({
        url: $select.data('action'),
        type: "POST",
        data: data,
        success: function (result) {
            multiselectCommon.removeOptions($yearSelect, true);
            if (result.data) {
                _.each(result.data, function (value) {
                    let selected = parseInt(yearSelectValue) === parseInt(value) ? 'selected' : '';
                    $yearSelect.append('<option value="' + value + '" ' + selected + '>' + value + '</option>');
                });
            }
            $yearSelect.selectpicker('refresh');
            $yearSelect.trigger('change');
            multiselectCommon.loadingStop($yearSelect);
        },
        error: function (data, status, headers, config) {
            multiselectCommon.loadingStop($yearSelect);
        }
    });
};

/**
 * Смена года в редактировании выпуска
 *
 * @param $select
 * @param initial
 */
window['multiselect-releaseYearChange'] = function($select, initial) {
    let data = [];
    let $form = $select.closest('form');
    let $numberSelect = $select.closest('form').find('select[name="number"]');
    let $nameInput = $select.closest('form').find('input[name="name"]');
    $nameInput.val(multiselectCommon.getReleaseName($form));

    multiselectCommon.setData(data, 'year', $select.val());
    multiselectCommon.setData(data, 'journal_id', $form.find('select[name="journal"]').val());
    //multiselectCommon.setData(data, 'current_number', $numberSelect.val());

    multiselectCommon.loadingStart($numberSelect);
    let numberSelectValue = $numberSelect.val();
    $.ajax({
        url: $select.data('action'),
        type: "POST",
        data: data,
        success: function (result) {
            multiselectCommon.removeOptions($numberSelect, true);
            if (result.data) {
                _.each(result.data, function (params, month) {
                    let paramsValiues = '';
                    _.each(params, function (value, key) {
                        paramsValiues = paramsValiues + key + '="' + value + '" ';
                    });
                    $numberSelect.append('<option value="' + month + '" ' + paramsValiues + '>' + month + '</option>');
                });
            }
            $numberSelect.selectpicker('refresh');
            $numberSelect.trigger('change');

            multiselectCommon.loadingStop($numberSelect);
        },
        error: function (data, status, headers, config) {
            multiselectCommon.loadingStop($numberSelect);
        }
    });
};

/**
 * Смена номера в редактировании выпуска
 *
 * @param $select
 * @param initial
 */
window['multiselect-releaseNumberChange'] = function($select, initial) {
    let $form = $select.closest('form');
    let $nameInput = $select.closest('form').find('input[name="name"]');
    $nameInput.val(multiselectCommon.getReleaseName($form));

    let selected = $select.find("option:selected");
    $form.find('input[name="active_date"]').val(selected.attr('active_date'));
    $form.find('input[name="price_for_printed"]').val(selected.attr('price_release_printed'));
    $form.find('input[name="price_for_electronic"]').val(selected.attr('price_release_electronic'));
    $form.find('input[name="price_for_articles"]').val(selected.attr('price_articte'));
    $form.find('input[name="twin"]').prop('checked', false);
};

/**
 * Изменить/Скрыть значения месяцев после смены периодичности
 * @param $select
 * @param initial
 */
window['multiselect-periodAfterChange'] = function($select, initial) {
    let journalPeriod = journalPeriodEvents.init($select, initial);
    let value = parseInt($select.val());
    switch (value)  {
        case 3: // раз в 2
            journalPeriod.eachOptions(2);
            journalPeriod.disableMonths(false);

            //journalPeriod.saveValueToNumber([1,2,3], [7,8,9]);
            break;
        case 2: // раз в 3
            journalPeriod.eachOptions(3);
            journalPeriod.disableMonths(false);

            //journalPeriod.saveValueToNumber([1,2], [7,8]);
            break;
        case 1: // раз в полугодие
            journalPeriod.eachOptions();
            journalPeriod.disableMonths(false);

            //journalPeriod.saveValueToNumber([1], [7]);
            break;
        case 0: // не выбрано
            journalPeriod.eachOptions();
            journalPeriod.disableMonths(true);
            journalPeriod.diselect();

            //journalPeriod.saveValueToNumber([], []);
            break;
        case 6: // ежемесячно
            journalPeriod.eachOptions(1);
            journalPeriod.disableMonths(false);

            //journalPeriod.saveValueToNumber([1,2,3,4,5,6], [7,8,9,10,11,12]);
            break;
    }
    journalPeriod.refresh();

    //journalPeriod.setNumbers();
};

window['multiselect-systemChangeConnection'] = function($select, initial) {
    $select.closest('.--connections-inputs').find('.--connection-block').removeClass('hidden');
    let value = $select.find('option:selected').data('block');
    if (value === 'db') {
        $select.closest('.--connections-inputs').find('.--connection-block[data-connection="tunneler"]').addClass('hidden');
    } else {
        $select.closest('.--connections-inputs').find('.--connection-block[data-connection="db"]').addClass('hidden');
    }
};

window['multiselect-bannerTypeChange'] = function ($select, $dialogInit) {
    let $dialog = $select.closest('form');
    if ($dialogInit !== undefined) {
        $dialog = $dialogInit;
    }
    let $galleryTab = $dialog.find('.nav-item:last-child');
    let $inputLink = $dialog.find('input[name="link"]');
    let $inputNews = $dialog.find('select[name="news"]');
    if ($select.val() === 'news') {
        $('.is-news-group').removeClass('hidden');
        $('.is-link-group').addClass('hidden');
        $inputLink.prop('required', false);
        $inputNews.prop('required', true);

        $galleryTab.addClass('hidden');
    } else {
        $('.is-link-group').removeClass('hidden');
        $('.is-news-group').addClass('hidden');
        $inputLink.prop('required', true);
        $inputNews.prop('required', false);

        $galleryTab.removeClass('hidden');
    }
};
