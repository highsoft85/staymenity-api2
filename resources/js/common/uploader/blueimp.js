//import {multiSave} from "../../cmf/project/multi/multiSave";


require('blueimp-file-upload/js/vendor/jquery.ui.widget.js');
require('blueimp-file-upload/js/jquery.iframe-transport.js');
require('blueimp-file-upload/js/jquery.fileupload.js');

$(document).ready(function () {
    //window['uploader']();

    $(document).bind('dragover', function (e) {
        let dropZones = $('.dropzone'),
            timeout = window.dropZoneTimeout;
        if (timeout) {
            clearTimeout(timeout);
        } else {
            dropZones.addClass('in');
        }
        let hoveredDropZone = $(e.target).closest(dropZones);
        dropZones.not(hoveredDropZone).removeClass('hover');
        hoveredDropZone.addClass('hover');
        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZones.removeClass('in hover');
        }, 100);
    });
});

/**
 * @see ./blueimp.js
 */
window['uploader'] = function () {
    console.log('init uploader');
    /**
     * <div class="file-uploader" id="uploader-1212"
     *  data-dropzone="#dropzone-1212"
     * >
     *
     * <div>
     */
    $('.file-uploader').each(function () {
        if (!$(this).hasClass('--is-init')) {
            $(this).addClass('--is-init');
            initFileUploader($(this));
        }
    });
    //window['drag-image']();
};

let initFileUploader = function ($container) {
    console.log($container);
    let id = '#' + $container.attr('id');
    let $dropzone = $($container.data('dropzone'));
    let $loadingContainer = $($container.data('loading-container'));

    $container.fileupload({
        dropZone: $dropzone,
        dataType: 'json',
        sequentialUploads: true,
        singleFileUploads: false,
        replaceFileInput: false,
        add: function (e, data) {
            let $form = $container;
            $loadingContainer.addClass('dialog__loading is-black is-container');
            $form.closest('.gallery-container').find('.help').remove();
            data.formData = _.merge(data.formData, $container.data());
            // if (multiSave.isMulti()) {
            //     data.formData = _.merge(data.formData, {
            //         multi_save: 1,
            //     });
            // }
            $(id + ' + label').addClass('is-loading');
            data.submit();
        },
        done: function (e, data) {
            $(id + ' + label').removeClass('is-loading');
            let update = $container.data('view-init');
            if (data.result.success) {
                $loadingContainer.find('.is-gallery-row[data-type="' + data.result.type + '"]').html(data.result.view);

                if (data.result.src) {
                    $('img[data-user-image]').attr('src', data.result.src);
                }
                if ($container.hasClass('image-markdown-input')) {
                    const $textarea = $container.closest('.markdown-editor-wrap').find('textarea');
                    const value = '<img src="' + data.result.file + '" alt="drawing" width="100%"/>';
                    $textarea.val($textarea.val() + value);
                    $textarea.trigger('change');
                }
            }
            $loadingContainer.removeClass('dialog__loading is-black is-container');
            if (update) {
                setTimeout(function () {
                    window['uploader']();
                    /* ---------- Tooltip ---------- */
                }, 100);
            }
        },
        error: function (data, textStatus, errorThrown) {
            $(id + ' + label').removeClass('is-loading');
            $loadingContainer.removeClass('dialog__loading is-black is-container');
            let responseJSON = data.responseJSON;
            if (responseJSON !== undefined) {
                let errors = data.responseJSON.errors;
                for (let key in errors) {
                    $container.closest('.gallery-container').prepend('<div class="help text-center is-danger" style="margin-bottom: 5px;">' + errors[key] + '</div>');
                }
            } else {
                for (let key in responseJSON) {
                    $container.closest('.gallery-container').prepend('<div class="help text-center is-danger" style="margin-bottom: 5px;">' + data.responseJSON[key][0] + '</div>');
                }
            }
            window.notification.error('Failed to upload image. Try another image');
            let update = $container.data('view-init');
            if (update) {
                setTimeout(function () {
                    window['uploader']();
                    /* ---------- Tooltip ---------- */
                }, 100);
            }
        }
    });
    $dropzone.click(function () {
        $container.trigger('click');
    });
};
