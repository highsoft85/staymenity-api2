$(function () {
    window['textarea-limit']();
});

window['textarea-limit'] = function () {
    $('.textarea-limit').each(function () {
        textareaLimit.init($(this)).initEvent().setCurrent();
    });
};

export const textareaLimit = {
    container: '.textarea-limit',
    $target: null,
    $textarea: null,

    init($target) {
        const self = this;
        self.$target = $target;
        self.$textarea = $(self.$target.data('target'));
        return self;
    },

    initEvent() {
        const self = this;
        self.$textarea.on('keyup', function () {
            textareaLimit.init($(this).closest('.form-group').find('.textarea-limit')).setCurrent();
        });
        return self;
    },

    /**
     *
     */
    setCurrent() {
        const self = this;
        if (self.$textarea === undefined) {
            return;
        }
        self.setCurrentValue(self.$textarea.val().length);
    },

    /**
     *
     * @param value
     */
    setCurrentValue(value) {
        const self = this;
        const $current = self.$target.find('.current');
        $current.text(value);
        self.setStatusCurrentByValue(value);
    },

    /**
     *
     * @param value
     */
    setStatusCurrentByValue(value) {
        const self = this;
        const limit = parseInt(self.$target.find('.limit').text());
        const $parent = self.$target.closest(self.container);
        if (value > limit) {
            $parent.addClass('text-danger');
        } else {
            $parent.removeClass('text-danger');
        }
    }
};
