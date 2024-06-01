let ajaxForm = {

    options: {
        ajaxLink: true,
        ajaxForm: true,
        ajaxTabs: true,
        validation: {
            helpClass: 'help',
            errorClass: 'is-danger',
            helpErrorClass: '.help.is-danger'
        },
        innerFormSubmit: '.inner-form-submit',
        loadingClass: 'is-loading',
        submit: '.btn.inner-form-submit',
        tab: {
            activeClass: '.active'
        }
    },
    ajax: {
        processData: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    },

    data: {},

    loading: {
        container: [
            'dialog__loading', 'is-black', 'is-container'
        ]
    },

    form: null,
    link: null,
    tagName: null,

    bind(sElem, sDelegateFrom, sAction) {
        const self = this;
        sDelegateFrom = sDelegateFrom || '';
        sAction = sAction || 'submit';
        let fn = function (event) {
            event.preventDefault();
            self.form = $(event.currentTarget);
            if (self.form.data('submit-active-tab')) {
                self.form = self.form.closest('.modal-content').find(self.form.data('submit-active-tab') + self.options.tab.activeClass).find('form');
                self.link = $(event.currentTarget);
                if (self.link.data('with-form')) {
                    let aForm = _.split(self.link.data('with-form'), ',');
                    _.each(aForm, function (val, index) {
                        let cloneAjaxForm = new Array();
                        let time = index === 0 ? 0.5 : index;
                        setTimeout(function () {
                            cloneAjaxForm[index] = _.clone(ajaxForm, true);
                            cloneAjaxForm[index].form = $(val);
                            cloneAjaxForm[index].tagName = cloneAjaxForm[index].form.get(0).tagName;
                            console.log($(val));
                            console.log(cloneAjaxForm[index].form);
                            cloneAjaxForm[index].send();
                        }, 500 / time);
                    });
                    return false;
                }
            }
            if (self.form.data('form')) {
                if (self.form.get(0).tagName === 'BUTTON') {
                    self.link = self.form;
                }
                self.form = $(self.form.data('form'));
            }
            self.tagName = self.form.get(0).tagName;
            if (self.form.data('delay')) {
                document.delay(function () {
                    self.send();
                }, 300);
            } else {
                self.send();
            }
            return false;
        };
        fn = _.bind(fn, this);
        if (sDelegateFrom) {
            $(sDelegateFrom).on(sAction, sElem, fn);
        } else {
            $(sElem).on(sAction, fn);
        }
    },

    withForm() {
        const self = this;
        let forms = self.form.data('with-form');
        let activeSubmit = self.form.data('active-submit') && parseInt(self.form.data('active-submit')) === 1;
        if (forms && !activeSubmit) {
            self.form.data('active-submit', 1);
            let aForms = _.split(forms, ',');
            _.each(aForms, function (val, index) {
                let $form = $(val);
                if ($form.length) {
                    if ($form.data('active-submit') && parseInt($form.data('active-submit')) === 1) {
                        console.log($form);
                    } else {
                        let time = index === 0 ? 0.5 : index;
                        setTimeout(function () {
                            $form.data('active-submit', 1);
                            $form.data('without-toastr', 1);
                            $form.submit();
                        }, 500 / time);
                    }
                }
            });
        }
        return true;
        if (activeSubmit) {
            //this.form.data('active-submit', 0);
            //return false;
        } else {
            //this.form.data('active-submit', 1);
        }
    },

    send() {
        const self = this;
        if (self.form.attr('method') === 'get') {

        } else {
            self.post();
        }
    },

    post() {
        const self = this;
        let data = [];
        if (self.form.data('search')) {
            data.push({
                name: self.form.attr('name'),
                value: self.form.val()
            });
        } else {
            data = self.getFormData();
        }
        self.clearValidate();
        if (!self.validate()) {
            self.notification({
                type: 'error',
                text: 'Fill in required fields',
                title: 'Error',
            });
            return;
        }
        self.startLoading();
        if (!self.before(data)) {
            return;
        }
        $.ajax({
            url: self.form.attr('action'),
            type: "POST",
            data: data,
            processData: self.ajax.processData,
            contentType: self.ajax.contentType,
            success: function (result) {
                self.stopLoading();

                if (result.toastr) {
                    self.notification(result.toastr);
                }
                //self.after(result);

                if (result.success) {
                    self.after(result);
                } else if (result.error) {
                    self.showError(result.message);
                }
                if (result.item) {
                    self.setItemError(result.item);
                }
                if (self.form.data('append') && result.view) {
                    $(self.form.data('append-container')).html(result.view);
                }
                if (self.form.data('pagination') && result.view) {
                    self.pagination(result);
                }
                if (self.form.data('search') && result.view) {
                    $(self.form.data('pagination-container')).html(result.view);
                }
                if (self.form.data('tab') && result.view) {
                    $('.ajax-tabs').find('li').removeClass('is-active');
                    self.form.closest('li').addClass('is-active');
                    $(self.form.data('container')).html(result.view);
                }
                if (result.push) {
                    self.push(result.push);
                }
                self.countable(result);
            },
            error: function (data, status, headers, config) {
                if (data.toastr) {
                    self.notification(data.toastr);
                }
                self.stopLoading();

                self.validateServer(data);
            }
        });
    },

    notification(toastr) {
        window.notification.send(toastr);
    },

    get() {

    },

    submit() {

    },

    stopLoading() {
        const self = this;
        self.form.find(self.options.innerFormSubmit).removeClass(self.options.loadingClass);
        self.form.removeClass(self.options.loadingClass);
        self.form.closest('.select').removeClass(self.options.loadingClass);

        if (self.link !== null) {
            self.link.removeClass('is-loading');
        }

        if (self.form.data('loading-container') && self.form.data('callback') !== 'refreshAfterSubmit') {
            for (let key in self.loading.container) {
                $(self.form.data('loading-container')).removeClass(self.loading.container[key])
            }
        }
        if (self.tagName === 'SELECT') {
            self.form.removeClass('is-loading');
        }
        if (self.tagName === 'INPUT') {
            self.form.closest('.form-group').removeClass(self.options.loadingClass);
        }
        if (self.form.data('outer-loading')) {
            $(self.form.data('outer-loading')).removeClass(self.options.loadingClass);
        }
    },

    countable(response) {
        if (response.total !== undefined) {
            let $count = $('.--count-table-view .--all');
            if ($count.length) {
                $count.html(response.total);
            }
        }
        if (response.count !== undefined) {
            let $count = $('.--count-table-view .--get');
            if ($count.length) {
                $count.html(response.count);
            }
        }
    },

    startLoading() {
        const self = this;

        self.form.find(this.options.innerFormSubmit).addClass(self.options.loadingClass);

        self.form.closest('.select').addClass(self.options.loadingClass);

        if (self.form.data('pagination')) {
            self.form.addClass(self.options.loadingClass);
        }
        if (self.form.data('loading')) {
            self.form.addClass(self.options.loadingClass);
        }
        if (self.form.data('outer-loading')) {
            $(self.form.data('outer-loading')).addClass(self.options.loadingClass);
        }
        if (self.form.get(0).tagName === 'INPUT') {
            self.form.closest('.form-group').addClass(self.options.loadingClass);
        }

        if (self.link !== null) {
            self.link.addClass('is-loading');
        }

    },

    after(result) {
        const self = this;
        let sCallbacks = self.form.data('callback') || result.callback;
        let bDefaultsCall = true;
        if (sCallbacks) {
            let aCallbacks = _.split(sCallbacks, ',');
            if (_.first(aCallbacks) === '@') {
                bDefaultsCall = false;
                aCallbacks = _.drop(aCallbacks);
            }
            _.each(aCallbacks, function (val) {
                let sFuncName = _.trim(val);
                if (_.isFunction(window[sFuncName])) {
                    window[sFuncName](result, self.form);
                }
            });
        }
        self.ajaxInitPlugins();

        if (bDefaultsCall) {
            self.afterDefault(result);
        }
        if (result.redirect) {
            window.location.replace(result.redirect);
        }
        if (result.post) {
            if (result.post.form) {
                $('body').append(result.post.form);
                let form = $('#ext_auth_form');
                form.submit();
                form.remove();
            }
        }
        if (result._blank) {
            let linkToDownload = result._blank;
            let downloadLink = document.createElement('a');
            downloadLink.id = "link-to-download";
            downloadLink.href = linkToDownload;
            downloadLink.setAttribute("target", "_blank");
            document.body.appendChild(downloadLink);
            downloadLink.click();
            setTimeout(function () {
                $('#link-to-download').remove();
            }, 1000);
        }
        if (this.form.data('has-active')) {
            let aItems = _.split(this.form.data('items'), ',');
            _.each(aItems, function (val) {
                let sItem = _.trim(val);
                $(sItem).removeClass('is-active');
                if (!self.form.hasClass('is-active')) {
                    self.form.addClass('is-active');
                    if (self.form.get(0).tagName === 'SELECT') {
                        self.form.find('option').removeAttr('selected')
                            .filter('[value=' + self.form.val() + ']')
                            .attr('selected', true)
                    }
                }
            });
            if (self.form.get(0).tagName !== 'SELECT') {
                console.log('!== SELECT');
                _.each(aItems, function (val) {
                    let $sItem = $(_.trim(val));
                    if ($sItem.length) {
                        if ($sItem.get(0).tagName === 'SELECT') {
                            console.log('=== SELECT');
                            /*
                            $(sItem).find('option').removeAttr('selected')
                                .filter('[value=0]')
                                .attr('selected', true);
                            */
                            $sItem.find('option').prop("selected", false);
                        }
                    }
                });
            }
        }
        if (self.form.data('active-submit') && parseInt(self.form.data('active-submit')) === 1) {
            self.form.data('active-submit', 0);
        }
        if (self.form.hasClass('ajax-tab')) {
            let $tab = $(self.form.attr('href'));
            $tab.removeClass('is-loading');
        }
    },

    afterDefault(result) {
        const self = this;
        self.form.find('input, div').removeClass('__error');
        $('.text-error[data-name="error"]').empty();
    },

    before() {
        const self = this;
        if (self.form.data('loading-container')) {
            for (let key in self.loading.container) {
                $(self.form.data('loading-container')).addClass(self.loading.container[key])
            }
        }
        if (self.tagName === 'SELECT') {
            self.form.addClass('is-loading');
        }


        let sCallbacks = self.form.data('before');
        let goToAjax = true;
        if (sCallbacks !== undefined) {
            let self = this;
            if (sCallbacks) {
                let aCallbacks = _.split(sCallbacks, ',');
                _.each(aCallbacks, function (val) {
                    let sFuncName = _.trim(val);
                    if (_.isFunction(window['before-' + sFuncName])) {
                        goToAjax = window['before-' + sFuncName](self.form);
                    }
                });
            }
        }
        let messageLoading = self.form.find('.message-loading');
        if (messageLoading) {
            messageLoading.empty();
            messageLoading.addClass('is-loading');
            messageLoading.removeClass('__is-danger');
        }

        if (self.form.hasClass('ajax-tab')) {
            let $navs = self.form.closest('.nav-tabs');
            $navs.find('.nav-link').removeClass('active');
            let $tabs = $navs.parent().find('.tab-content');
            $tabs.find('.tab-pane').removeClass('active');
            let $tab = $(self.form.attr('href'));
            $tab.empty();
            $tab.addClass('active');
            $tab.addClass('is-loading');
            self.form.addClass('active');
            // скрыть сабмит
            let $submit = self.form.closest('.modal-content').find('.modal-footer .ajax-link');
            if (parseInt(self.form.data('hidden-submit')) === 0) {
                $submit.removeClass('hidden');
            } else {
                $submit.addClass('hidden');
            }
        }

        return goToAjax;
    },

    beforeDefault() {

    },

    clearValidate() {
        const self = this;
        self.form.find('.help.' + self.options.validation.errorClass).remove();
        self.form.find('input.' + self.options.validation.errorClass).removeClass(self.options.validation.errorClass);
        self.form.find('textarea.' + self.options.validation.errorClass).removeClass(self.options.validation.errorClass);
        self.form.find('select.' + self.options.validation.errorClass).closest('.form-group.' + self.options.validation.errorClass).removeClass(self.options.validation.errorClass);
        self.form.find('select.' + self.options.validation.errorClass).removeClass(self.options.validation.errorClass);
        self.form.find('.bootstrap-select .dropdown-toggle.' + self.options.validation.errorClass).removeClass(self.options.validation.errorClass);
    },
    validate() {
        const self = this;
        let $required = self.form.find('input[required], select[required]');
        //let $required = this.form.find('[data-required="1"]');
        if (self.form.data('required-by-radio')) {
            let labelRadio = self.form.find('.label-radio.active');

            $.each($required, function (key) {
                let $closestContainer = $(this).closest(self.form.data('required-by-radio-blocks'));
                if ($closestContainer.length) {
                    if ($closestContainer.data('id') !== labelRadio.data('id')) {
                        delete $required[key];
                    }
                }
            });
            $required = $required.filter(function (n) {
                return n !== undefined;
            });
            console.log($required);
        }
        let bResult = true;
        let $firstElementToFocus = null;
        $required.each(function (i, e) {
            let $elem = $(e);
            let sElemType = $elem.get(0).tagName;
            let bFilled = ($elem.is(':checkbox')) ? $elem.prop('checked') : $elem.val();
            // пропускаем проверку полей пользователя заказа
            let required_skip = ($elem.closest("div[id$='_user_form']"));
            required_skip = (required_skip.length == 1) ? required_skip.hasClass('hidden') : false;

            if ((!bFilled || bFilled.length === 0) && (!required_skip)) {
                $elem.addClass(self.options.validation.errorClass);
                if ($firstElementToFocus === null) {
                    $firstElementToFocus = $elem;
                }
                bResult = false;
                switch (sElemType) {
                    case 'SELECT':
                        if ($elem.hasClass('combobox') || $elem.parent().hasClass('__combobox')) {
                            $elem.closest('.form-group').addClass('__error');
                        }
                        if ($elem.hasClass('selectpicker')) {
                            $elem.closest('.form-group').addClass(self.options.validation.errorClass);
                        }
                        if ($elem.data('role') === 'chosen-select') {
                            $elem.closest('.form-group').addClass('__error');
                        }
                        break;
                    case 'INPUT':
                        if ($elem.is(':checkbox')) {
                            $elem.parent().addClass('__error');
                        } else {
                            $elem.addClass('__error');
                        }
                        break;
                    default:
                        $elem.addClass('__error');
                        break;
                }
            }
        });
        if (!bResult && $firstElementToFocus !== null) {
            $firstElementToFocus.focus();
        }
        return bResult;
    },

    validateServer(result) {
        const self = this;

        let resultJson = result.responseJSON;
        if (resultJson.errors) {
            resultJson = resultJson.errors;
        }
        for (let key in resultJson) {
            let $input = self.form.find('input[name="' + key + '"]');
            if (!$input.length) {
                $input = self.form.find('select[name="' + key + '"]');
                if ($input.hasClass('selectpicker')) {
                    $input.closest('.bootstrap-select').find('.dropdown-toggle').addClass(self.options.validation.errorClass);
                }
            }
            if (!$input.length) {
                $input = self.form.find('textarea[name="' + key + '"]');
            }
            $input.addClass(self.options.validation.errorClass);
            /*
            setTimeout(function() {
                input.removeClass(self.options.validation.errorClass);
            }, 10000);
            */
            let message;
            if (_.isArray(resultJson[key])) {
                message = resultJson[key][0];
            } else {
                message = resultJson[key];
            }
            $input.closest('.form-group').append(self.validationTemplate(message, false));
            //this.form.find('.text-error[data-name="' + key + '"]').text(result.responseJSON[key][0]);
            if (key === 'error') {
                self.showError(message);
            }
            $input.closest('.form-holder').append(self.validationTemplate(message, false));
            //this.form.find('.text-error[data-name="' + key + '"]').text(result.responseJSON[key][0]);
            if (key === 'g-recaptcha-response') {
                self.form.find('textarea[name="' + key + '"]').closest('.form-group').append(self.validationTemplate(message, false));
            }
            if (key === 'toastr') {
                console.log(resultJson[key]);
                self.notification(resultJson[key]);
            }
        }
    },

    getFormData() {
        const self = this;
        let data = {};
        let formData = new FormData();
        let name;
        if (self.tagName === 'FORM') {
            data = self.form.serializeArray();
            console.log(data);
        } else if (self.tagName === 'SELECT') {
            name = self.form.attr('name');
            data['' + name] = self.form.val();
        } else if (self.tagName === 'INPUT' && self.form.attr('type') === 'checkbox') {
            data = self.form.data();
            name = self.form.attr('name');
            if (self.form.is(':checked')) {
                data['' + name] = 1;
            } else {
                data['' + name] = 0;
            }
        } else if (self.tagName === 'A' && self.form.attr('value')) {
            name = self.form.attr('name');
            data['' + name] = self.form.attr('value');
        } else {
            data = self.form.data();
        }
        if (!data._token || data._token === undefined) {
            data._token = window.Laravel;
        }
        self.form.find('.abc-checkbox input[data-unchecked]').each(function () {
            const $checkbox = $(this);
            if ($checkbox.is(':checked')) {
                data.push({
                    name: $checkbox.attr('name'),
                    value: 1,
                });
            } else {
                data.push({
                    name: $checkbox.attr('name'),
                    value: 0
                });
            }
        });
        if (self.form.data('edit-after-create')) {
            data.push({
                name: 'edit',
                value: 1
            });
        }
        if (data['bs.tooltip'] !== undefined) {
            delete data['bs.tooltip'];
        }
        if (self.form.data('pagination') && $('#search-bar-form').length) {
            data = $('#search-bar-form').serializeArray();
        }
        if (self.form.data('form-data')) {
            let supportData = [];
            _.each(data, function (value, key) {
                if (value.name === undefined) {
                    let val = value;
                    value.name = key;
                    value.value = val;
                }
                supportData = self.getFormDataPushKeyValue(supportData, value.name, value.value);
            });
            let sData = self.form.data('form-data');
            if (sData) {
                sData = _.split(sData, ',');
                _.each(sData, function (val) {
                    let $element = $(_.trim(val));
                    if ($element.length) {
                        if ($element.get(0).tagName === 'INPUT') {
                            if ($element.is(':checkbox')) {
                                supportData = self.getFormDataPushKeyValue(supportData, $element.attr('name'), $element.is(':checked') ? 1 : 0);
                            } else {
                                supportData = self.getFormDataPushKeyValue(supportData, $element.attr('name'), $element.val());
                            }
                        }
                        if ($element.get(0).tagName === 'SELECT') {
                            supportData = self.getFormDataPushKeyValue(supportData, $element.attr('name'), $element.val());
                        }
                        if ($element.get(0).tagName === 'FORM') {
                            _.each($element.serializeArray(), function (val) {
                                supportData = self.getFormDataPushKeyValue(supportData, val.name, val.value);
                            });
                        }
                    }
                });
            }
            _.merge(data, supportData);
            //data = supportData;
        }
        if (self.form.find('input[type="file"]:not(.--form-ignore)').length) {
            _.each(data, function (item) {
                formData.append(item.name, item.value);
            });
            self.form.find('input[type="file"]:not(.--form-ignore)').each(function () {
                let file = document.getElementById($(this).attr('id')).files[0];
                formData.append($(this).attr('name'), file);
            });
            self.ajax.processData = false;
            self.ajax.contentType = false;
            return formData;
        }
        return data;
    },

    getFormDataPushKeyValue(data, key, value) {
        data.push({
            name: key,
            value: value,
        });
        return data;
    },

    showError(sMessage) {
        const self = this;
        console.log(self.validationTemplate(sMessage, true));
        self.form.prepend(self.validationTemplate(sMessage, true));
    },

    setItemError(item) {
        console.log(item);
        console.log('[data-item-id="' + item + '"]');
        this.form.find('[data-item-id="' + item + '"]').addClass('__error');
    },

    validationTemplate(text, center) {
        const self = this;
        let classes = self.options.validation.helpClass + ' ' + self.options.validation.errorClass;
        if (center) {
            classes = classes + '  has-text-centered';
        }
        return '<span class="' + classes + '" style="margin-bottom: 10px;">' + text + '</span>';
    },

    ajaxInitPlugins() {
        const self = this;
        let sInit = self.form.data('ajax-init');
        if (sInit) {
            let aInit = _.split(sInit, ',');
            _.each(aInit, function (val) {
                let sFuncName = _.trim(val);
                if (_.isFunction(window[sFuncName])) {
                    window[sFuncName]();
                }
            });
        }
    },

    /**
     * data-pagination="1"                          -- включить пагинацию
     * data-pagination-container="selector"         -- куда будет вставляться шаблок
     * data-append="1"                              -- если true, то шаблон будет добавлять в контейнер
     * data-form-query="selector"                   -- скрытая форма для пагинации
     * <form class="pagination-form">
     <input type="hidden" name="page" value="{{ $paginator->currentPage() }}">
     @if(isset($aSearch) && !empty($aSearch))
     @foreach($aSearch as $key => $value)
     <input type="hidden" name="{{ $key }}" value="{{ $value }}">
     @endforeach
     @endif
     </form>
     * data-pagination-view-list-button="1"         -- означает, что кнопка находится отдельно от контейнера,
     *                                                 ожидается для контейнера result.view.list
     * data-pagination-button-container="selector"  -- контейнер для кнопки, ожидается result.view.button
     * @param result
     */
    pagination(result) {
        if (this.form.data('pagination-view-list-button')) {
            if (this.form.data('append')) {
                $(this.form.data('pagination-container')).append(result.view.list);
                $(this.form.data('pagination-button-container')).html(result.view.button);
            } else {
                $(this.form.data('pagination-container')).html(result.view.content);
                $(this.form.data('pagination-button-container')).html(result.view.button);
            }
        } else {
            if (this.form.data('append')) {
                this.form.closest(this.form.data('pagination-button-container')).remove();
                $(this.form.data('pagination-container')).append(result.view);
            } else {
                $(this.form.data('pagination-container')).html(result.view);
            }
        }
        window['tooltip']();
    },

};
window.ajaxForm = ajaxForm;

$(document).ready(function () {
    ajaxForm.bind('.ajax-form', 'body');
    ajaxForm.bind('.ajax-link', 'body', 'click');
    ajaxForm.bind('.ajax-select', 'body', 'change');
    ajaxForm.bind('.ajax-search', 'body', 'keyup');
    ajaxForm.bind('.ajax-input', 'body', 'keyup');
    ajaxForm.bind('.ajax-checkbox', 'body', 'change');
    ajaxForm.bind('.ajax-tab', 'body', 'click');

    $('body').on('click', 'button.inner-form-submit[type="submit"]', function (event) {
        event.preventDefault();
        $(this).closest('form.ajax-form').submit();
    });
    if ($('#search-bar').hasClass('show')) {
        $('#search-bar').find('form').submit();
    }
});
