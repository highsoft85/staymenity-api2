


/**
 *
 * @type {{setData(*, *=, *=): *, removeOptions(*, *=): void, addOptions(*, *=, *=): void, loadingStart(*): void, loadingStop(*): void, getReleaseName(*): *}}
 */
let multiselectCommon = {

    /**
     *
     * @param data
     * @param key
     * @param value
     * @returns {*}
     */
    setData(data, key, value) {
        data.push({
            name: key,
            value: value,
        });
        return data;
    },

    /**
     *
     * @param $select
     * @param withDefault
     */
    removeOptions($select, withDefault) {
        $select.find('option').remove();
        if (withDefault !== undefined && withDefault) {
            $select.append('<option value="">Empty</option>');
        }
    },

    /**
     *
     * @param $select
     * @param data
     * @param selected
     */
    addOptions($select, data, selected) {
        _.each(data, function (key, value) {
            let beSelected = parseInt(selected) === parseInt(value) ? 'selected' : '';
            $select.append('<option value="' + value + '" ' + beSelected + '>' + value + '</option>');
        });
    },

    /**
     *
     * @param $select
     */
    loadingStart($select) {
        $select.closest('.form-group').addClass('is-loading');
    },

    /**
     *
     * @param $select
     */
    loadingStop($select) {
        $select.closest('.form-group').removeClass('is-loading');
    },

    /**
     *
     * @param $form
     * @returns {*}
     */
    getReleaseName($form) {
        let $year = $form.find('select[name="year"]');
        let year = $year.val();
        let $number = $form.find('select[name="number"]');
        let number = $number.val();
        let $name = $form.find('input[name="name"]');

        /**
         * @see release-AfterLoadDialog
         */
        let name;
        if ($name.data('original')) {
            name = $name.data('original');
        } else {
            name = $name.val();
        }
        name = name.split('№')[0].trim();
        name.replace(year, '');
        if (number !== '') {
            name += ' №' + number;
        }
        if (year !== '') {
            name += ' ' + year;
        }
        return name;
    }
};
export {multiselectCommon};
