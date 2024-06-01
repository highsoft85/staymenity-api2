

window['colorpicker-image'] = function () {

    let canvas;
    let ctx;

    let images = [ // predefined array of used images
        $('.container-img').data('src')
    ];

    let iActiveImage = 0;

    // drawing active image
    let image = new Image();
    image.onload = function () {
        ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, canvas.width, canvas.height); // draw the image on the canvas
    };
    image.src = images[iActiveImage];

    // creating canvas object
    canvas = document.getElementById('panel');
    ctx = canvas.getContext('2d');

    $('#panel').mousemove(function (e) { // mouse move handler
        let canvasOffset = $(canvas).offset();
        let canvasX = Math.floor(e.pageX - canvasOffset.left);
        let canvasY = Math.floor(e.pageY - canvasOffset.top);

        let imageData = ctx.getImageData(canvasX, canvasY, 1, 1);
        let pixel = imageData.data;

        let pixelColor = "rgba(" + pixel[0] + ", " + pixel[1] + ", " + pixel[2] + ", " + pixel[3] + ")";
        $('#preview').css('backgroundColor', pixelColor);
    });

    $('#panel').click(function (e) { // mouse click handler
        let canvasOffset = $(canvas).offset();
        let canvasX = Math.floor(e.pageX - canvasOffset.left);
        let canvasY = Math.floor(e.pageY - canvasOffset.top);

        let imageData = ctx.getImageData(canvasX, canvasY, 1, 1);
        let pixel = imageData.data;

        let dColor = pixel[2] + 256 * pixel[1] + 65536 * pixel[0];
        $('#hexVal').val('#' + dColor.toString(16)).trigger('change');
    });

    $('#swImage').click(function (e) { // switching images
        iActiveImage++;
        if (iActiveImage >= 10) iActiveImage = 0;
        image.src = images[iActiveImage];
    });
    $('#hexVal').on('change keyup', function () {
        let value = $(this).val();
        $('[data-color="#hexVal"]').css({'background-color': value});
    });
};
