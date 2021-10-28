const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .css('resources/css/normalize.css', 'public/css')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .sass('resources/sass/bulma.scss', 'public/css')
    .sass('resources/sass/blog.scss', 'public/css')

    .js('resources/js/posts/shared.js', 'public/js/posts')
    .js('resources/js/posts/edit.js', 'public/js/posts')
    .sass('resources/sass/posts/edit.scss', 'public/css/posts');
