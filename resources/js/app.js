/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
//window.Vue = require('vue');

require('vue-context');

window.Fuse = require('fuse.js');

window.CKEDITOR_BASEPATH = process.env.MIX_APP_URL + '/' +
                    process.env.MIX_APP_DIR + '/resources/js/ckeditor/';
require('../../public/resources/js/ckeditor/ckeditor.js');
window.CKEditor_Vue = require('ckeditor4-vue');

//window.CKEditor = require('@ckeditor/ckeditor5-vue2');
//window.ClassicEditor = require('@ckeditor/ckeditor5-build-classic/build/ckeditor');

//require('@ckeditor/ckeditor5-build-classic/build/translations/el');
//Vue.use(CKEditor);

// Add translation capabilities to vue components
Vue.mixin(require('./trans'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('mailmerge-component', () => import('./components/mailmerge/MailMergeComponent.vue'));
Vue.component('doclogoform', () => import('./components/mailmerge/DocLogoForm.vue'));
Vue.component('xlsxcomponent', () => import('./components/mailmerge/XlsxComponent.vue'));
Vue.component('pagepreview', () => import('./components/mailmerge/PagePreview.vue'));
Vue.component('rolecomponent', () => import('./components/mailmerge/RoleComponent.vue'));
Vue.component('recipientlinks', () => import('./components/mailmerge/RecipientLinks.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

window.XLSX = require('xlsx');
