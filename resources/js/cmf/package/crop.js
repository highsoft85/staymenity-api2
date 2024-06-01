// Check for jQuery.
if (typeof(jQuery) === 'undefined') {
    let jQuery;
    // Check if require is a defined function.
    if (typeof(require) === 'function') {
        jQuery = $ = require('jquery');
        // Else use the dollar sign alias.
    } else {
        jQuery = $;
    }
}

(function($){
    $.fn.bulkCrop = function(options) {
        let settings = $.extend({
            element: '#cropper',
            close: '#crop-delete',
            inputs: {
                x: '#cropX',
                y: '#cropY',
                w: '#cropW',
                h: '#cropH',
                cropImageWith: '#cropImageWith',
                cropImageHeight: '#cropImageHeight'
            },
            after: '.control.__crop-active',
            before: '.control.__crop-init'
        }, options);

        $(settings.close).click(function(){
            let JcropAPI = $(settings.element).data('Jcrop');
            JcropAPI.destroy();
            hide();
        });


        let update = function(c) {
            $(settings.inputs.x).val(c.x);
            $(settings.inputs.y).val(c.y);
            $(settings.inputs.w).val(c.w);
            $(settings.inputs.h).val(c.h);
            $(settings.inputs.cropImageWith).val($(settings.element).css('width'));
            $(settings.inputs.cropImageHeight).val($(settings.element).css('height'));
        };

        let show = function() {
            $(settings.after).css('display','block');
            $(settings.before).css('display','none');
        };

        let hide = function() {
            $(settings.element).css('height','auto');
            $(settings.element).css('width','100%');
            $(settings.before).css('display','block');
            $(settings.after).css('display','none');
        };

        let init = function() {
            $(this)
                .click(function() {
                    show();
                    $(settings.element).Jcrop({
                        onSelect: update
                    });
                });
        };

        return this.each(init);
    };
}(jQuery));

$(document).ready(function() {
    $('#crop-image').bulkCrop({
        element: '#cropper',
        close: '#crop-delete',
        inputs: {
            x: '#cropX',
            y: '#cropY',
            w: '#cropW',
            h: '#cropH',
            cropImageWith: '#cropImageWith',
            cropImageHeight: '#cropImageHeight'
        },
        before: '.control.__crop-active',
        after: '.control.__crop-init'
    });
});


