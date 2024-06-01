

$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': window.Laravel}
});

window['closeDialogCallback'] = function ($dialog) {
    $dialog.find('input.is-danger').removeClass('is-danger');
    $dialog.find('.help.is-danger').remove();
    $dialog.find('input').empty();
};

let ajaxDialogs = {

    settings: {
        openDialogClass: 'dialog--open',

        ajax: false,
        confirm: false,
        ajaxClass: 'dialog-ajax',
        disableOverlayClass: 'dialog-disabled_overlay',
        loadingClass: '.dialog__loading',
        loading: '<div class="dialog__loading"><div></div></div>',
        isBootstrap: false
    },

    target: null,

    supportDialog: {
        id: '#custom-edit-modal-support',
        isOpen: false,
    },
    confirmDialog: {
        id: '#pages-dialogs-confirm',
        isOpen: false,
    },
    renderModalDialog: {
        id: '#pages-dialogs-modal',
        isOpen: false,
    },

    bind(sElem, sDelegateFrom, sAction, oSettings) {
        let self = this;
        sDelegateFrom = sDelegateFrom || '';
        sAction = sAction || 'submit';
        let fn = function (event) {
            event.preventDefault();
            self.target = $(event.currentTarget);
            self.send();
            return false;
        };
        fn = _.bind(fn, self);
        if (sDelegateFrom) {
            $(sDelegateFrom).on(sAction, sElem, fn);
        } else {
            $(sElem).on(sAction, fn);
        }
        _.each(oSettings, function (field, key) {
            self.settings[key] = field;
        });
        //console.log(ajaxDialogs.settings);
    },

    send: function () {
        let self = this;
        console.log('send click');
        let $dialog = $(self.target.attr('data-dialog'));
        if ($dialog.hasClass('show')) {
            self.supportDialog.active = true;
            $dialog = $(self.supportDialog.id);
        }
        let $body = $('body');
        console.log($dialog);
        console.log($dialog.data());

        self.settings.isBootstrap = $dialog.hasClass('modal');

        if (self.settings.isBootstrap && !self.supportDialogIsOpen()) {
            $body.addClass('--fixed');
            $body.css('margin-right', document.getScrollbarWidth() + 'px');
            $body.find('.app-header').css('padding-right', document.getScrollbarWidth() + 'px');
        }

        if (self.settings.isBootstrap && !self.target.data('after-show-modal')) {
            try {
                self.openModal($dialog);
                if (self.supportDialogIsOpen()) {
                    self.backdropAddSupport();
                }
            } catch ($e) {
                console.log('Error: Modal is transitioning.');
            }
        } else {
            $dialog.addClass(self.settings.openDialogClass);
        }

        $dialog.off().on('click', '[data-dismiss="modal"]', function () {
            if (self.settings.isBootstrap && !self.supportDialogIsOpen()) {

                $body.removeClass('--fixed');
                $body.css('margin-right', 0);
                $body.find('.app-header').css('padding-right', 0);
            }
            if (self.target.attr('data-without-footer') !== undefined) {
                if (self.settings.isBootstrap) {
                    $dialog.find('.modal-footer').removeClass('hidden');
                }
            }

            try {
                $(this).closest('.modal').modal('hide');
                if (self.supportDialogIsOpen()) {
                    self.backdropRemoveSupport();
                } else {
                    //$('#custom-edit-modal').css('position: fixed;')
                }
            } catch ($e) {
                console.log('Error: Modal is transitioning.');
            }
        });

        //$dialog.modal('show');

        if (self.settings.ajax) {
            console.log(self.settings.ajax);
            self.addAjax($dialog, self.target);
        }

        if (self.settings.confirm) {
            console.log(self.settings.confirm);
            self.addConfirm($dialog, self.target);
        }

        if (self.target.attr('data-disabled-overlay') !== undefined) {
            $dialog.addClass(self.settings.disableOverlayClass);
        }

    },
    openModal($dialog) {
        $dialog.modal('show');
    },

    backdropAddSupport() {
        $('.modal-backdrop').addClass('is-support');
    },
    backdropRemoveSupport() {
        $('.modal-backdrop').removeClass('is-support');
    },

    addAjax: function ($dialog, $target) {
        let self = this;
        self.removeAjax($dialog);
        $dialog.addClass(self.settings.ajaxClass);
        if (self.settings.isBootstrap) {
            $dialog.find('.modal-content').append(self.settings.loading);
            $dialog.find('.dialog__loading').addClass('is-black');
        } else {
            $dialog.append(self.settings.loading);
        }

        $dialog.addClass(self.settings.disableOverlayClass);

        setTimeout(function () {
            $dialog.removeClass(self.settings.disableOverlayClass);
        }, 7000);

        if (self.target.data('loading')) {
            self.target.addClass('is-loading');
        }


        $.ajax({
            url: $target.attr('data-action'),
            type: "POST",
            data: self.dataAjax($target),
            success: function (result) {
                $dialog.find(self.settings.loadingClass).remove();
                $dialog.removeClass(self.settings.disableOverlayClass);
                if (result.view) {
                    self.appendAjax($dialog, result.view);
                } else {
                    self.appendAjax($dialog, result);
                }
                if (self.target.data('after-show-modal')) {
                    self.openModal($dialog);
                }
                self.afterAppend($dialog, result);
                window['multiselect']();
                window['datepicker']();
                window['markdown']();
                window['coordinates']();
                window['cleave-mask']();
                window['tooltip']();
                window['colorpicker']();
                window['tabs-scrolling']();
                window['fancybox-video-input']();
                window['textarea-limit']();
                if (self.target.data('loading')) {
                    self.target.removeClass('is-loading');
                }
                if ($target.data('model')) {
                    let sFuncName = $target.data('model') + '-AfterLoadDialog';
                    if (_.isFunction(window[sFuncName])) {
                        window[sFuncName](result, $target);
                    }
                }
            },
            error: function (data, status, headers, config) {
                $dialog.removeClass(self.settings.disableOverlayClass);
                self.removeAjax($dialog);
                self.afterError($dialog);
                if (self.target.data('loading')) {
                    self.target.removeClass('is-loading');
                }
            }
        });
    },
    afterAppend: function ($dialog, result) {
        let self = this;
        let $target = self.target;
        if ($target.data('ajax-init') !== undefined) {
            let aInit = _.split($target.data('ajax-init'), ',');
            _.each(aInit, function (val) {
                let sFuncName = _.trim(val);
                if (_.isFunction(window[sFuncName])) {
                    window[sFuncName]($target, $dialog);
                }
            });
        }
        if ($target.data('callback') !== undefined) {
            let aInit = _.split($target.data('callback'), ',');
            _.each(aInit, function (val) {
                let sFuncName = _.trim(val);
                if (_.isFunction(window[sFuncName])) {
                    window[sFuncName](result, $target);
                }
            });
        }

        if ($target.data('without-footer') !== undefined) {
            if (self.settings.isBootstrap) {
                $dialog.find('.modal-footer').addClass('hidden');
            }
        }
    },
    afterError: function ($dialog) {
        let self = this;
        let error =
            '<div class="modal-header">' +
            '<h4 class="modal-title">Server Error</h4>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">×</span>' +
            '</button>' +
            '</div>';

        if (self.settings.isBootstrap) {
            $dialog.find('.modal-content').html(error);
        } else {
            $dialog.append(error);
        }
    },
    removeAjax: function ($dialog) {
        let self = this;
        if (!self.settings.isBootstrap) {
            $dialog.children()
                .filter(function () {
                    return (!$(this).hasClass('dialog__overlay'));
                }).remove();
            $('#custom-edit-modal').css('display', 'none');
        } else {
            $dialog.find('.modal-content').children().remove();
        }
    },
    appendAjax: function ($dialog, view) {
        let self = this;
        self.removeAjax($dialog);
        if (self.settings.isBootstrap) {
            $dialog.find('.modal-content').html(view);
        } else {
            $dialog.append(view);
        }
        return true;
    },
    dataAjax: function ($target) {
        return $target.data();
    },
    addConfirm: function ($dialog, $target) {
        let self = this;
        let subtitle = $dialog.find('.--subtitle');
        subtitle.addClass('hidden');

        if ($target.data('text') !== undefined) {
            $dialog.find('.--text').text($target.data('text'));
        }
        if ($target.data('subtitle') !== undefined) {
            subtitle.html($target.data('subtitle'));
            subtitle.removeClass('hidden');
        }
        if ($target.data('action') !== undefined) {
            $dialog.find('.ajax-form').attr('action', $target.data('action'));
        }
        if ($target.data('list-action') !== undefined) {
            $dialog.find('.ajax-form').attr('data-list-action', $target.data('list-action'));
        }
        if ($target.data('id') !== undefined) {
            $dialog.find('.ajax-form input[name="id"]').val($target.data('id'));
        }
        if ($target.data('form-view') !== undefined) {
            $dialog.find('.modal-content').html($target.data('form-view'));
        }
        $('.modal-backdrop').last().addClass('is-confirm');
    },
    afterClose($dialog) {
        let self = this;
        console.log(!$dialog.hasClass(self.settings.disableOverlayClass));
        if (!$dialog.hasClass(self.settings.disableOverlayClass)) {
            $dialog.removeClass(self.settings.openDialogClass);
            if (_.isFunction(window['closeDialogCallback'])) {
                window['closeDialogCallback']($dialog);
            }
            if ($dialog.hasClass(self.settings.ajaxClass)) {
                $dialog.removeClass(self.settings.ajaxClass);
                self.removeAjax($dialog);
            }
        }
        $('#custom-edit-modal').css('display', 'block');
    },
    supportDialogIsOpen() {
        let self = this;
        return $(self.supportDialog.id).hasClass('show') || $(self.renderModalDialog.id).hasClass('show');
    }
};


/*
 |----------------------------------------
 | Dialogs
 |
 | Template:
 |
 | Button
 | <a class="trigger" data-dialog="#register">Регистрация</a>
 | <a class="trigger" data-dialog="#register" data-ajax data-action="url" data-ajax-init="callback, callback">Регистрация</a>
 | <a class="trigger" data-dialog="#register" data-disabled-overlay>Регистрация</a>
 |----------------------------------------
 */
$(document).ready(function () {
    window['bulkInit']();
});

window['bulkInit'] = function () {
    let $body = $('body');
    $body.on('click', '.dialog .dialog__close', function () {
        let $dialog = $(this).closest('.dialog');
        ajaxDialogs.afterClose($dialog);
    });
    $body.on('click', '.dialog .dialog__overlay', function () {
        let $dialog = $(this).closest('.dialog');
        ajaxDialogs.afterClose($dialog);
    });
    $body.on('click', '.modal-backdrop', function () {
        $('.is-right-bar').find('.close[data-dismiss="modal"]').trigger('click');
    });

    let simpleDialog = $.extend(true, {}, ajaxDialogs);
    let ajaxDialog = $.extend(true, {}, ajaxDialogs);
    let confirmDialog = $.extend(true, {}, ajaxDialogs);

    simpleDialog.bind('.trigger[data-modal]', 'body', 'click', {
        openDialogClass: 'dialog--open'
    });

    ajaxDialog.bind('.trigger[data-ajax]', 'body', 'click', {
        openDialogClass: 'dialog--open',
        ajax: true
    });
    ajaxDialog.bind('.trigger-ajax[data-ajax]', 'body', 'click', {
        openDialogClass: 'dialog--open',
        ajax: true
    });
    confirmDialog.bind('.trigger[data-confirm]', 'body', 'click', {
        openDialogClass: 'dialog--open',
        confirm: true
    });
};
