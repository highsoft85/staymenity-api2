$(document).ready(function () {
    //fancybox();
    //initFancybox();
});

window['fancybox-video-input'] = function () {
    $('.--fancybox-video-input').on('change paste keyup', function () {
        fancyboxVideoInput.updateByInput($(this));
    }).each(function () {
        fancyboxVideoInput.updateByInput($(this));
    });
};

const fancyboxVideoInput = {
    updateByInput($target) {
        const value = $target.val();
        const $link = $($target.data('link'));
        const $iframe = $($target.data('iframe'));
        if (value !== '') {
            const videoId = youtube.getId(value);
            $link.removeClass('disabled');
            $link.attr('href', value);
            $iframe.attr('src', '//www.youtube.com/embed/' + videoId);
            $link.text('Посмотреть видео');
        } else {
            $link.addClass('disabled');
            $link.attr('href', '');
            $iframe.attr('src', '');
            $link.text('Видео отсутствует');
        }
    }
};

const youtube = {
    getId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);

        return (match && match[2].length === 11)
            ? match[2]
            : null;
    }
};
