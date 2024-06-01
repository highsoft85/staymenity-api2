

import tippy, {animateFill} from "tippy.js";

$(document).ready(function ($) {
    window['tooltip']();
});

window['tooltip'] = function () {
    const button = document.querySelector('[data-tippy-popover]');
    if (button !== null && button.length) {
        const instance = tippy(button);
        instance.destroyAll();
    }
    tippy('[data-tippy-popover]', {
        interactive: true,
        //theme: 'light',
        hideOnClick: false,
        animateFill: false,
        plugins: [animateFill],
        arrow: false,
        //duration: [275, 250000],
    });
    tippy('[data-tippy-input-popover]', {
        theme: 'light',
        animateFill: false,
        hideOnClick: false,
        trigger: 'click',
        placement: 'bottom',
        plugins: [animateFill],
        arrow: false,
    });
    tippy('[data-tippy-popover-html]', {
        theme: 'light',
        allowHTML: true,
        interactiveBorder: 30,
        interactive: true,
        hideOnClick: false,
        animateFill: false,
        plugins: [animateFill],
        arrow: false,
    });
};

