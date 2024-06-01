$(document).ready(function() {
    /*
     |----------------------------------------
     | File input
     | Template:
     |
     | Button
     |  <input type="file" name="file" id="file" class="is-file">
     |      <label for="file" class="button label-file">
     |      <span class="label-file-name">Выберите файл</span>
     |  </label>
     |
     |----------------------------------------
     */
    $('.is-file').each(function () {
        let $input = $(this),
            $label = $input.next('.label-file'),
            labelVal = $label.html();

        $input.on('change', function (element) {
            let fileName = '';
            if (element.target.value) {
                fileName = element.target.value.split('\\').pop();
            }
            if (fileName) {
                $label.addClass('has-file').find('.label-file-name').html(fileName);
                //$label.addClass('has-file').find('.label-file-name').html('Файл загружен');
            }
            else {
                $label.removeClass('has-file').html(labelVal);
            }
            //fileName ? $label.addClass('has-file').find('.label-file-name').html(fileName) : $label.removeClass('has-file').html(labelVal);
        });
    });
});
