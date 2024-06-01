

let commonCallback = {

    /**
     * Проверить data атрибуты на существование
     *
     * @param $target
     * @param array
     * @returns {boolean}
     */
    checkData($target, array) {
        let success = true;
        _.each(array, function (item, key) {
            if (!$target.data(item)) {
                success = false;
            }
        });
        return success;
    },

    /**
     * Проверить данные data атрибута
     * @param $target
     * @param name
     * @param value
     * @returns {boolean}
     */
    checkDataValue($target, name, value) {
        return $target.data(name) === value;
    },

    /**
     * Занести в массив значение
     *
     * @param data
     * @param name
     * @param value
     * @returns {*}
     */
    push(data, name, value) {
        data.push({
            name: name,
            value: value
        });
        return data;
    },


    loading: {

        /**
         * Остановить прелоадер контейнера
         *
         * @param $target
         */
        stopLoading($target) {
            if ($target.data('loading-container')) {
                for (let key in window.ajaxForm.loading.container) {
                    $($target.data('loading-container')).removeClass(window.ajaxForm.loading.container[key])
                }
            }
        }
    },

    init: {

        /**
         * Инициализация коллбеков по data-ajax-list-ini
         *
         * @param $target
         */
        list($target) {
            let sCallbacks = $target.data('ajax-list-init');
            if (sCallbacks) {
                commonCallback.runCallback(sCallbacks);
            }
        },
    },

    /**
     * Быстрое редактирование после создание, триггер на эту кнопку
     */
    runEdit(id) {
        let triggerOenModal = setInterval(function () {
            console.log('triggerOenModal');
            let $button = $('.admin-table a.--is-edit[data-edit=' + id + ']');
            if (!$('body').hasClass('modal-open') && $button.length) {
                $button.trigger('click');
                clearInterval(triggerOenModal);
            }
        }, 100);
        // через 10 секунд удалить интервал
        setTimeout(function () {
            clearInterval(triggerOenModal);
        }, 10000);
    },

    /**
     * Запустить коллбек
     *
     * @param string
     * @param prefix
     */
    runCallback(string, prefix = undefined) {
        let aCallbacks = _.split(string, ',');
        _.each(aCallbacks, function (val) {
            let sFuncName = _.trim(val);
            if (_.isFunction(window[sFuncName])) {
                console.log(sFuncName);
                window[sFuncName]();
            }
        });
    },

    /**
     * Занести количество выбранных элементов
     *
     * @param value
     */
    setCount(value) {
        let $count = $('.--count-table-view .--get');
        if ($count.length) {
            $count.html(value);
        }
    },
};

export {commonCallback};
