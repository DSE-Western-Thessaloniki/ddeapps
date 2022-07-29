/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Fuse from "fuse.js";
import { createApp, defineAsyncComponent } from "vue";
import "./bootstrap";

window.Fuse = Fuse;

const appUrlEndsWithSlash =
    typeof process.env.MIX_APP_URL === "undefined"
        ? false
        : process.env.MIX_APP_URL.endsWith("/");
const appDir = `${
    process.env.MIX_APP_DIR !== "" ? process.env.MIX_APP_DIR + "/" : ""
}resources/js/ckeditor/`;
window.CKEDITOR_BASEPATH =
    process.env.MIX_APP_URL +
    (appUrlEndsWithSlash === false ? "/" : "") +
    appDir.replace("//", "/");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = createApp({});

// Add translation capabilities to vue components
app.mixin(require("./trans"));

app.component(
    "mailmerge-component",
    defineAsyncComponent(
        () => import("./components/mailmerge/MailMergeComponent.vue")
    )
);
app.component(
    "doclogoform",
    defineAsyncComponent(() => import("./components/mailmerge/DocLogoForm.vue"))
);
app.component(
    "xlsxcomponent",
    defineAsyncComponent(
        () => import("./components/mailmerge/XlsxComponent.vue")
    )
);
app.component(
    "pagepreview",
    defineAsyncComponent(() => import("./components/mailmerge/PagePreview.vue"))
);
app.component(
    "rolecomponent",
    defineAsyncComponent(
        () => import("./components/mailmerge/RoleComponent.vue")
    )
);
app.component(
    "recipientlinks",
    defineAsyncComponent(
        () => import("./components/mailmerge/RecipientLinks.vue")
    )
);

app.mount("#app");
