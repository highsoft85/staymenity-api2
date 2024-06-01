

/**
 * Init callbacks
 */

require('./callbacks/form.js');


window['replaceIconAfterSubmit'] = function (result, $target) {
    if (result.success) {
        let $icon = $target.find('.icon i');
        if ($icon.hasClass($icon.attr('on-class'))) {
            $icon.removeClass();
            $icon.addClass($icon.attr('off-class'));
        } else {
            $icon.removeClass();
            $icon.addClass($icon.attr('on-class'));
        }
    }
};

window['replaceFormAttributesAfterSubmit'] = function (result, $target) {
    if (result.success) {
        let title = $target.attr('title');
        if (title == $target.attr('on-title')) {
            $target.attr('title', $target.attr('off-title'));
        } else {
            $target.attr('title', $target.attr('on-title'));
        }
    }
};


window['resetSearchFilters'] = function (result, $target) {
    if (result.success) {
        $($target.data('search-container') + ' select').each(function () {
            console.log($(this).data('null-value'));
            $(this).val($(this).data('null-value'));
        });
    }
};

window['updateBasket'] = function (result, $target) {
    if (result.success) {
        $.ajax({
            url: '/basket/count',
            type: "POST",
            success: function (response) {
                if (response.success) {
                    $('.cart__count').text(response.count);
                }
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }
};

window['initItemSwitch'] = function (result, $target) {
    //initItemSwitch();
    console.log('initItemSwitch');
};

window['tableShowOnly'] = function ($target) {
    //initItemSwitch();
    console.log('tableShowOnly');
    let hide = $target.data('table-hide');

    let $table = $('.is-right-bar .modal-body').find('.admin-table table');
    for (let i = 1; i <= hide; i++) {
        $table.find('th:nth-last-child(' + i + ')').addClass('hidden');
        $table.find('td:nth-last-child(' + i + ')').addClass('hidden');
    }
};


window['editFormUser'] = function (result, $target) {
    if (result.success && result.name) {
        $('[data-user-name]').text(result.name);
    }
};

/**
 *
 * @param result
 * @param $target
 */
window['journal-PeriodNewTab'] = function (result, $target) {
    if (result.success && result.new_tab && result.year) {
        let $form = $('#periods_form');
        let $navs = $form.find('.nav-tabs').first().find('.nav-link:not(.btn)');
        let year = result.year;
        let beforeItemKey = null;
        $navs.each(function (key, value) {
            console.log(key, value);
            if (parseInt($(this).text()) < parseInt(year)) {
                beforeItemKey = key + 1;
                console.log(beforeItemKey);
                return false;
            }
        });
        let hrefTab = '#period-tab-' + year + '';
        let $navTabs = $form.find('.nav-tabs').first();
        let htmlNav = '<li class="nav-item"><a class="nav-link" data-toggle="tab" href="' + hrefTab + '">' + year + '</a></li>';
        if (beforeItemKey !== null) {
            $(htmlNav).insertBefore($navTabs.find('.nav-item:nth-child(' + beforeItemKey + ')'));
        } else {
            $(htmlNav).insertBefore($navTabs.find('.nav-item:last-child'));
        }
        //$('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="' + hrefTab + '">' + year + '</a></li>').insertBefore($navTabs.find('.nav-item:last-child'));
        //$form.find('.nav-tabs').first().append('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="' + hrefTab + '">' + year + '</a></li>');
        $form.find('.tab-content').first().append('<div class="tab-pane tab-submit" id="period-tab-' + year + '">' + result.new_tab + '</div>');
        window['multiselect']();
        $('.nav-link[href="' + hrefTab + '"]').trigger('click');
        $form.find('.empty').remove();
    }
};

/**
 *
 * @param result
 * @param $target
 */
window['journal-PeriodOpenModal'] = function (result, $target) {
    $('.--period-periodicity').each(function () {
        /**
         * @see ./multiselect.js
         */
        window['multiselect-periodAfterChange']($(this), true);
    });
};

/**
 *
 * @param result
 * @param $target
 */
window['release-AfterLoadDialog'] = function (result, $target) {
    let $name = $('#name_id');
    $name.data('original', $name.val());
};

window['order-addPosition'] = function () {
    $.ajax({
        url: 'http://laravel-panor.test/admin/order/0/action/getCartView',
        type: "POST",
        success: function ($result) {
            $('.order_cart').html($result.view);
            $('#totalPrice_id').val($result.totalPrice);
            //$('#totalPrice_id').attr('disabled', true);
        }
    });
};

/**
 * Обновление комментариев после сохранения
 *
 * @param result
 * @param $target
 */
window['commentsUpdateAfterSubmit'] = function (result, $target) {
    if (result.success && result.view !== undefined) {
        $('.--comments-field-container').html(result.view);
        const $textarea = $('.--comments-field-textarea textarea');
        $textarea.val('');
        $textarea.trigger('change');
    }
};

window['afterSaveMarkdown'] = function (result, $target) {
    console.log(result);
    $target.closest('form').find('.markdown').val(result.text);
};
