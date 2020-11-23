/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

require('vue-context');

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

Vue.component('mailmerge-component', require('./components/mailmerge/MailMergeComponent.vue').default);
Vue.component('mailmerge-doc-logo', require('./components/mailmerge/MailMergeDocLogo.vue').default);
Vue.component('mailmerge-doc-contact-info', require('./components/mailmerge/MailMergeDocContactInfo.vue').default);
Vue.component('mailmerge-doc-date-priority', require('./components/mailmerge/MailMergeDocDatePriority.vue').default);
Vue.component('mailmerge-recipients', require('./components/mailmerge/MailMergeRecipients.vue').default);
Vue.component('doclogoform', require('./components/mailmerge/DocLogoForm.vue').default);
Vue.component('xlsxcomponent', require('./components/mailmerge/XlsxComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

window.XLSX = require('xlsx');
