

const Huebee = require('huebee/dist/huebee.pkgd.min');


$(document).ready(function ($) {
    window['colorpicker']();
});

window['colorpicker'] = function () {
    if ($('.color-input').length) {
        let hueb = new Huebee('.color-input', {
            // options
            notation: 'hex',
            saturations: 0,
            hues: 1,
            // staticOpen: true,
            customColors: [
                '#41b882', '#dc3545', '#7FBFFB', '#7FB6B7', '#fb5f4f', '#E66FC7', '#D24861', '#1DA25D', '#8a6510', '#7175AA', '#6d466b', '#e2b18f'
            ]
        });
    }
};
