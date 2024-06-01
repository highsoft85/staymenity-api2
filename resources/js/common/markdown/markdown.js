
//const breaks = require('../../../../public/frontend/node_modules/remark-breaks/index');

const MarkdownIt = require('markdown-it')({
    html: true,
    linkify: true,
    typographer: true,
    breaks: true,
});

$(document).ready(function () {
    window['markdown']();

    $('body').on('drop', '.drop-area', (e) => {
        let files = e.originalEvent.dataTransfer.files;
        e.preventDefault();
        let selectorJq = $(e.target).closest('.markdown-editor-wrap').find('.image-markdown-input');
        let selector = document.getElementById(selectorJq.attr('id'));
        selector.files = files;
        selectorJq.trigger('change');
    });
    // $('body').on('change', '.image-markdown-input', function () {
    //     const $target = $(this);
    //     $target.closest('.markdown-editor-wrap').find('.upload-image-markdown-form input[type="submit"]').trigger('click');
    //     // не отрабатывает
    //     //$(e.target).closest('.markdown-editor-wrap').find('.upload-image-markdown-form').submit();
    //  });
    $('body').on('change', '.image-markdown-input', (e) => {
        $(e.target).closest('.markdown-editor-wrap').find('.upload-image-markdown-form').submit();
    });


});

function markdownImagesRender(result) {
    let images = [];
    let index = result.search(/!\[.*\]\(/g);
    while(index !== -1) {
        let start = result.indexOf('(', index) + 1;
        let end = result.indexOf(')', index);
        let src = result.substring(start, end);
        result = result.substring(0, index) +  "<img src='" + src + "'/>" + result.substring(end + 1, result.length);
        index = result.search(/!\[.*\]\(/g);
    }
    return result;
}


/**
 * Удаление табуляции из маркдауна
 */
function removeTabs(result) {
    return result.replace(new RegExp('\t', "g"), '');
}

/**
 * Маркдаун редактор имеет:
 *
 * - контейнер
 * class="markdown-editor-wrap markdown-wrapper-{{ $name }}"
 * data-area="#markdown-area-{{ $name }}"
 *
 * - textarea
 * class="markdown"
 * id="markdown-area-{{ $name }}"
 *
 * - дополнительную форму с картинкой:
 * form
 * id="upload-image-markdown-form-{{ $name }}"
 * class="ajax-form"
 * data-area="#markdown-area-{{ $name }}"
 *
 * input[type="file"]
 * class="image-markdown-input-file"
 * id="image-markdown-input-{{ $name }}"
 *
 *
 *
 */
window['markdown'] = function () {
    $('.markdown').each(function () {
        let $textarea = $(this);
        let $parent = $textarea.parent();
        $parent.css('position', 'relative');
        let $preview = $parent.find('.markdown-preview');
        let value = $textarea.val().trim();
        $textarea.val(value);
        $(this).bind('paste', function() {
            var self = this;
            setTimeout(function(e) {
                $textarea.val(removeTabs($(self).val()));
                $textarea.trigger('change');
            }, 0);
        });
        $(this).on('keyup change', function() {
            let value = $textarea.val();
            let result = MarkdownIt.render(value);
            result = markdownImagesRender(result);
            $preview.html(result);
        });
        $textarea.trigger('change');
    });
    $('.markdown-add-bold').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this), '**' + value + '**', 2)
    });
    $('.markdown-add-italic').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'*' + value + '*', 1)
    });
    $('.markdown-add-quote').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'> ' + value, 0)
    });
    $('.markdown-add-code').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'`' + value + '`', 1)
    });
    $('.markdown-add-link').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'[' + value + '](url)', 0)
    });
    $('.markdown-add-bullet-list').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'* ' + value, 0)
    });
    $('.markdown-add-numbered-list').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'1. ' + value, 0)
    });
    $('.markdown-add-task-list').click(function () {
        const value = getMarkdownValue($(this));
        putInMarkdwon($(this),'* [ ] ' + value, 0)
    });
    $('.markdown-add-table').click(function () {
        putInMarkdwon($(this), '| header | header |\n' +
            '| ------ | ------ |\n' +
            '| cell | cell |\n' +
            '| cell | cell | ', 0)
    });
    $('.markdown-toggler').off().click(function() {
        let $container = $(this).closest('.markdown-editor-wrap');
        let $window = $container.find('.markdown-window:not(.is-columns)');
        $window.toggleClass('hidden');
        $(this).toggleClass('active');
    });
    $('.markdown-columns-toggler').off().click(function() {
        let $container = $(this).closest('.markdown-editor-wrap');
        let $window = $container.find('.markdown-window.is-columns');
        $window.toggleClass('hidden');
        $(this).toggleClass('active');
        const $modal = $(this).closest('.modal');
        $window.css({
            //width: $modal.width(),
            right: $modal.width(),
        })
    });
    $('.markdown-view').each(function () {
        $(this).html(MarkdownIt.render($(this).html()));
    });
};

function putInMarkdwon($target, textToInsert, left) {
    let input = $target.closest('.markdown-editor-wrap').find('.markdown');
    input.focus();
    const value = input.val();
    const start = input.get(0).selectionStart;
    const end = input.get(0).selectionEnd;
    // update the value with our text inserted
    input.val(value.slice(0, start) + textToInsert + value.slice(end));

    // update cursor to be at the end of insertion
    input.get(0).selectionStart = input.get(0).selectionEnd = start + textToInsert.length - left;
    input.trigger('change');

}

function getMarkdownValue($target) {
    const $area =  $target.closest('.markdown-editor-wrap').find('.markdown');
    const value = $area.val();
    const start = $area.get(0).selectionStart;
    const end = $area.get(0).selectionEnd;
    return value.slice(start).slice(0, end - start);
}

/**
 * После загрузки фотографии отдельной формой
 * - в textarea вставляется ссылка на изображение
 *
 * @param result
 * @param $target
 */
window['markdownAfterImageUpload'] = function (result, $target) {
    console.log('markdownAfterImageUpload');
    $target.closest('form').find('input[name=file]').val('');
    if (result.success) {
        const $area = $($target.data('area'));
        $area.val($area.val() + '\n![image]('+result.file+')');
        $area.trigger('change');
    }
};

/**
 * После сохранения маркдайна как визивига
 * - подставляются новые данные после сохранения, изменяются только ссылки
 * на временные картинки
 *
 * @param result
 * @param $target
 */
window['afterSaveMarkdown'] = function (result, $target) {
    console.log('afterSaveMarkdown');
    if (result.success) {
        $target.find('textarea').val(result.text);
    }
};
