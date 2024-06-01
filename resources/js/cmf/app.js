

window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * -------------------------------------------
 * Append laravel token
 * -------------------------------------------
 *
 */
window.Laravel = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': window.Laravel}
});

require('./common.js');
require('./project.js');
require('./template.js');

window.colors = [ '#F13D37', '#7FBFFB', '#7FB6B7', '#E66FC7', '#D24861', '#1DA25D', '#E8CA20' ];

import Vue from 'vue';
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//window.axios.defaults.withCredentials = true;

import ExampleComponent from './components/ExampleComponent.vue';

if (document.getElementById('app-stripe')) {
    new Vue({
        el: '#app-stripe',
        render: h => h(ExampleComponent),
        data: {},
    });
}

//var moment = require('moment'); // require
//console.log(moment('2020-12-01T03:40:47-05:00', moment.ISO_8601).format('YYYY-MM-DD HH:mm:ss'));
//
