const mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    output:{
        chunkFilename:'js/vuejs_code_split/[name].js',
    }
});

// Set the MIX_APP_DIR in .env to the name of the subdirectory on the server or
// comment it if it is going to reside on root
if ((typeof process.env.MIX_APP_DIR !== 'undefined') && (process.env.MIX_APP_DIR != ""))
    mix.setResourceRoot('/'+process.env.MIX_APP_DIR+'/');
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .vue({ version: 2 })
   .extract()
   .version('js/vuejs_code_split/*.js')
   .purgeCss();

if (!mix.inProduction()) {
    mix.sourceMaps()
       .browserSync('ddeapps.test');
}
