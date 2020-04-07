require('./bootstrap');

if ($('#app-vue'))
    window.Vue = require('vue');
//Include components
import VuejsClipper from 'vuejs-clipper'
import CropImage from './components/CropImgComponent.vue';
import DropZone from './components/DropZoneComponent.vue';
import TinymceEditor from './components/TinymceEditorComponent';

const axios = require('axios').default;

Vue.use(VuejsClipper,
    {
        components: {
            clipperBasic: true,
            clipperPreview: true
        }
    }
);

//Якщо використовується vue ('#app-vue') тоді запускаєм
if ($('#app-vue').length) {
    const app = new Vue({
        el: '#app-vue',
        components: {
            CropImage,
            DropZone,
            TinymceEditor,
        }

    });
}

$.datetimepicker.setLocale('uk');
$('.date-time-picker').datetimepicker({
    // timepicker: false,
    format: 'd.m.Y H:i'
});

$('[data-toggle="tooltip"]').tooltip();

/*
Клік по дропзоне
 */
// $('.add-media-button').click(function (e) {
//     e.preventDefault();
//     $('#dropzone').click();
// });
//
//
// $('.to-redactor-paginate').click(function (e) {
//     e.preventDefault();
//     $('#dropzone').click();
// });
