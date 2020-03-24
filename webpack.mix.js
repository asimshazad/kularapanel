const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.combine([
    'resources/css/fontawesome.min.css',
    'resourcesjs/css/easymde.min.css',
    'resources/lib/datatimepicker/jquery.datetimepicker.css',
    'bower_components/kdev-sendform/dist/css/kdev-sendForm.css',

], 'public/css/kulara-all.min.css');

mix.combine([
    'resources/js/easymde.min.js',
    'resources/lib/datatimepicker/jquery.datetimepicker.full.js',
    'bower_components/kdev-sendform/dist/js/kdev-sendForm.js',

], 'public/js/kulara-all.min.js');

