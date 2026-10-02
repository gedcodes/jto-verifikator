const path = require('path');
const mix = require('laravel-mix');

mix.webpackConfig({
    watchOptions: {
        ignored: /node_modules/
    },
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@': __dirname + '/resources',
        }
    }
});

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require("tailwindcss"),
    ])
    .sass('resources/css/app.scss', 'public/css')
    .sourceMaps();

// Copy images dari resources/images ke public/images
// Menggunakan copy dengan pattern spesifik untuk menghindari recursive copy yang menyebabkan stack overflow
// Hanya copy file di root directory resources/images, tidak termasuk subdirectory
mix.copy('resources/images/*.png', 'public/images');
mix.copy('resources/images/*.jpg', 'public/images');
mix.copy('resources/images/*.jpeg', 'public/images');
mix.copy('resources/images/*.svg', 'public/images');
mix.copy('resources/images/*.gif', 'public/images');
if (mix.inProduction()) {
    mix.version();
}

// const mix = require('laravel-mix');

// /*
//  |--------------------------------------------------------------------------
//  | Mix Asset Management
//  |--------------------------------------------------------------------------
//  |
//  | Mix provides a clean, fluent API for defining some Webpack build steps
//  | for your Laravel applications. By default, we are compiling the CSS
//  | file for the application as well as bundling up all the JS files.
//  |
//  */
// mix.js('resources/js/app.js', 'public/js').vue()

//    .postCss('resources/css/app.css', 'public/css', [
//       require("tailwindcss")
//     ]);
