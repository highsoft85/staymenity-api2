

import {commonCallback} from './common.js';



/*
 * Каллбэк для закрытия модального окна после отправки формы.
 */
window['closeModalAfterSubmit'] = function (result, $target) {
    let $body = $('body');
    console.log(result);
    if (result.success) {
        if ($body.find('.modal-backdrop').length === 1) {
            $body.removeClass('--fixed');
            $body.css('margin-right', 0);
        }
        $target.closest('.modal').modal('hide');
    }
};

/*
 * Каллбэк для закрытия модального окна после отправки формы.
 */
window['closeSupportModalAfterSubmit'] = function (result, $target) {
    let $body = $('body');
    if (result.success) {
        $target.closest('.modal').modal('hide');
    }
};

/*
 * Каллбэк для закрытия модального окна после отправки формы.
 */
window['closeAndTriggerDialogAfterSubmit'] = function (result, $target) {
    if (result.success) {
        $target.closest('.modal').modal('hide');
        let action = $target.data('trigger-action');
        let id = $target.data('trigger-action-id');
        $('button[data-action="' + action + '"][data-id="' + id + '"]').trigger('click');
    }
};
/*
 * Каллбэк для закрытия модального окна после отправки формы.
 */
window['closeDialogAfterSubmit'] = function (result, $target) {
    if (result.success) {
        let $dialog = $target.closest('.dialog');
        $dialog.find('.dialog__overlay').trigger('click');
    }
};

/*
 * Каллбэк для закрытия модального окна после отправки формы.
 */
window['refreshAfterSubmit'] = function (result, $target) {
    if (result.success && commonCallback.checkData($target, ['list', 'list-action'])) {
        let aLists = _.split($target.data('list'), ',');
        let aListActions = _.split($target.data('list-action'), ',');
        _.each(aLists, function (list, key) {
            let $view = $(list);
            let url = aListActions[key];
            let data = [];
            if ($target.data('form-data')) {
                console.log('serializeArray');
                data = $($target.data('form-data')).serializeArray();
            }
            if ($view.data('name') && $target.data('list-name') && trim($view.data('name')) !== trim($target.data('list-name'))) {
                return;
            }
            if (commonCallback.checkDataValue($target, 'list', '.admin-table')) {
                let page = $('.pagination-form').find('input[name="page"]').val();
                data = commonCallback.push(data, 'page', page);
            }
            if (commonCallback.checkDataValue($target, 'list', '.admin-table')) {
                const searchData = $('#search-bar-form').serializeArray();
                searchData.map(function (item) {
                    if (item.value !== '') {
                        data = commonCallback.push(data, item.name, item.value);
                    }
                })
            }
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                success: function (response) {
                    if (response.view) {
                        $view.html(response.view);
                        // if ($target.data('counter')) {
                        //     let $counter = $($target.data('counter'));
                        //     $counter.html(response.count);
                        // }
                        if (response.id !== undefined && $target.data('edit-after-create')) {
                            commonCallback.runEdit(response.id);
                        }
                    } else {
                        $view.html(response);
                    }
                    commonCallback.init.list($target);
                    window['tooltip']();
                    if (response.count !== undefined) {
                        commonCallback.setCount(response.count);
                    }
                    commonCallback.loading.stopLoading($target);
                },
                error: function (msg) {
                    commonCallback.loading.stopLoading($target);
                    console.log(msg);
                }
            });
        });
    }
    if (result.success && result.name) {
        $('[data-user-name]').text(result.name);
    }
};




window['strongRefreshView'] = function ($view, url, $data, $counter) {
    let data = null;
    if ($data) {
        data = $data.serializeArray();
    }
    console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function ($list) {
            if ($list.view) {
                $view.html($list.view);
                if ($counter) {
                    $counter.html($list.count);
                }
            } else {
                $view.html($list);
            }
        },
        error: function (msg) {
            console.log(msg);
        }
    });
};

window['refreshModalAfterSubmit'] = function (result, $target) {
    if (result.success) {
        let $view = $target.closest('.is-modal-body');
        if (result.view) {
            $view.html(result.view);
        } else {
            $view.html(result);
        }
    }
};




window['updateView'] = function (result, $target) {
    if (result.success && result.view) {
        $($target.data('view')).html(result.view);
        let sCallbacks = $target.data('ajax-init');
        if (sCallbacks) {
            commonCallback.runCallback(sCallbacks);
        }
    }
    if (result.success && result.src) {
        $('img[data-user-image]').attr('src', result.src);
    }
};

window['replaceView'] = function (result, $target) {
    if (result.success && result.view) {
        $($target.data('view')).replaceWith(result.view);
        let sCallbacks = $target.data('ajax-init');
        if (sCallbacks) {
            commonCallback.runCallback(sCallbacks);
        }
    }
};
window['appendView'] = function (result, $target) {
    if (result.success && result.view) {
        $($target.data('view')).append(result.view);
        let sCallbacks = $target.data('ajax-init');
        if (sCallbacks) {
            commonCallback.runCallback(sCallbacks);
        }
    }
};

window['apiDataDump'] = function (result, $target) {
    if (result.success && result.view) {
        $($target.data('view')).html(result.view);
    }
};
