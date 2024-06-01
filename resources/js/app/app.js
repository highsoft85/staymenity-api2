require('./bootstrap');

window.Laravel = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': window.Laravel}
});
import Vue from 'vue';

import ExampleComponent from './components/ExampleComponent.vue';

new Vue({
    el: '#app',
    render: h => h(ExampleComponent),
    data: {},
});
