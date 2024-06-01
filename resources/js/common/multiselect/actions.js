

/**
 * События для мультиселекта
 *
 * @type {{changed(*): void}}
 */
let multiselectActions = {
    /**
     * Изменение
     *
     * @param e
     */
    ajaxChanged(e) {
        let $select = $(e.currentTarget);
        if (_.isArray($select.val())) {
            $select.data('selected', $select.val().join(','));
        }
    },

    /**
     * Данные после запроса
     *
     * @param data
     * @returns {Array}
     */
    ajaxProcessData(data) {
        console.log(data);
        let values = data.search;
        let selected = data.selected;
        let array = [];
        if (values && values.length !== 0) {
            _.each(values, function (value, key) {
                array.push({
                    text: value,
                    value: key,
                    selected: selected[key] !== undefined,
                });
            });
        }
        return array;
    },

    /**
     * После загрузки
     *
     * @param e
     */
    ajaxLoaded(e) {
        setTimeout(function () {
            let $select = $(e.currentTarget);
            if (!$select.data('selected')) {
                $select.closest('.bootstrap-select').find('.bs-searchbox input').val(' ').trigger('keyup');
            }
            if ($select.data('AjaxBootstrapSelect').list !== undefined) {
                $select.trigger('change').data('AjaxBootstrapSelect').list.cache = {};
            }
            if ($select.data('change')) {
                $select.trigger('change');
            }
        }, 500);
    },

    /**
     *
     * @param $select
     */
    setEmpty($select) {
        $select.selectpicker('val', '');
        $select.selectpicker('deselectAll');
        $select.selectpicker('refresh');
        $select.trigger('change');
    },

    /**
     *
     * Изменение всех мультиселектов, нахождение коллбеков
     *
     * @param e
     */
    changed(e) {
        console.log('changed');
        let $select = $(e.currentTarget);
        let sCallbacks = $select.data('callback');
        if (sCallbacks) {
            let aCallbacks = _.split(sCallbacks, ',');
            _.each(aCallbacks, function (val) {
                let sFuncName = 'multiselect-' + _.trim(val);
                if (_.isFunction(window[sFuncName])) {
                    console.log(sFuncName);
                    window[sFuncName]($select);
                }
            });
        }
    }
};
export {multiselectActions};
