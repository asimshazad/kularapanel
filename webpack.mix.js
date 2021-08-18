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

mix.js([
    'resources/js/app.js'
], 'public/js/asimshazad-components.min.js');

mix.combine([
    'resources/css/fontawesome.min.css',
    'resources/css/bootstrap.min.css',
    'resources/css/datatables.min.css',
    'resources/css/tempusdominus-bootstrap-4.min.css',
    'resources/css/bootstrap-select.min.css',
    'resources/css/daterangepicker.css',
    'resources/css/ekko-lightbox.css',
    'resources/css/summernote.css',
    'resources/css/easymde.min.css',
    'resources/css/bootstrap4-toggle.min.css',
    'resources/lib/datatimepicker/jquery.datetimepicker.css',
    'bower_components/kdev-sendform/dist/css/kdev-sendForm.css',

], 'public/css/asimshazad-all.min.css');

mix.combine([
    'resources/js/jquery.min.js',
    'resources/js/jquery-ui.min.js',
    'resources/js/popper.min.js',
    'resources/js/bootstrap.min.js',
    'resources/js/pdfmake.min.js',
    'resources/js/vfs_fonts.js',
    'resources/js/datatables.min.js',
    'resources/js/handlebars.min.js',
    'resources/js/moment.min.js',
    'resources/js/sweetalert2.js',
    'resources/js/tempusdominus-bootstrap-4.min.js',
    'resources/js/bootstrap-select.min.js',
    'resources/js/daterangepicker.min.js',
    'resources/js/ekko-lightbox.min.js',
    'resources/js/summernote.js',
    'resources/js/summernote-bs4.js',
    'resources/js/tagsinput.js',
    'resources/js/pusher.min.js',
    'resources/js/push.min.js',
    'resources/js/serviceWorker.min.js',
    'resources/js/easymde.min.js',
    'resources/js/bootstrap4-toggle.min.js',
    'resources/lib/datatimepicker/jquery.datetimepicker.full.js',
    'bower_components/kdev-sendform/dist/js/kdev-sendForm.js',

], 'public/js/asimshazad-all.min.js');

