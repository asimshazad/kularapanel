<template>
    <div class="tinymce-editor" :id="nameInput">
        <editor
            api-key="bzu319zydv26qozizxkocxv5yrdesjyq6er82n0ssmyqyy9r"
            :name="nameInput"
            ref="tinymce"
            initialValue=""
            :init="config"
            v-model="content"

        ></editor>
    </div>
</template>

<script>
    import Editor from '@tinymce/tinymce-vue'

    export default {
        name: "TinymceEditor",
        props: [
            'name',
            'content'
        ],
        components: {
            'editor': Editor
        },
        data() {
            return {
                nameInput: '',
                inputValue: '',
                config: {
                    language: 'uk',
                    language_url: '/lap/js/tinymce_uk.js',
                    height: 400,
                    selector: '#' + this.nameInput,
                    plugins: [
                        'advlist autolink lists link image charmap print preview quickbars importcss mediaembed',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount code formatpainter formatpainter casechange'
                    ],
                    //Для мобіли
                    mobile: {

                        toolbar_drawer: 'sliding'
                    },
                    // menu: {
                    //     format: {title: 'Format', items: 'casechange'}
                    // },
                    // mediaembed_max_width: '100%',
                    // formatselect items
                    block_formats: 'Paragraph=p; Header 2=h2; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h5;',

                    // Список кнопок/https://www.tiny.cloud/docs/advanced/editor-control-identifiers/#toolbarcontrols
                    toolbar:
                        'undo redo | formatselect | bold italic underline strikethrough superscript subscript casechange | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent | removeformat formatpainter | blockquote link media mediaembed charmap help | my_dash my_left_pointing my_right_pointing | code preview fullscreen',

                    event_root: '#mci',
                    toolbar_drawer: false,

                    // Додати кастомны елементи до редактору, наприклад кнопку додати зображення в редактор поза самим  редактором
                    // custom_ui_selector: '.my-custom-button',

                    quickbars_selection_toolbar: 'bold italic | quicklink quickbold h2 h3 blockquote',
                    // Підключити свою css
                    content_css: "/css/style.css",
                    branding: false,
                    //Додати в окрему вкладку набір символів
                    // charmap_append: [
                    //     [0x2600, 'sun']
                    // ]

                    default_link_target: "_blank",
                    default_link_ref: "nofollow",
                    rel_list: [
                        {title: 'No Referrer', value: 'noreferrer'},
                        {title: 'External Link', value: 'external'},
                        {title: 'Nofollow', value: 'nofollow'}
                    ],
                    setup: (editor) => {
                        //dash
                        editor.ui.registry.addButton('my_dash', {
                            text: '&mdash;',
                            onAction: function () {
                                tinymce.activeEditor.selection.setContent('&mdash;');
                            }
                        });

                        editor.ui.registry.addButton('my_left_pointing', {
                            text: '&laquo;',
                            onAction: function () {
                                tinymce.activeEditor.selection.setContent('&laquo;');
                            }
                        });
                        editor.ui.registry.addButton('my_right_pointing', {
                            text: '&raquo;',
                            onAction: function () {
                                tinymce.activeEditor.selection.setContent('&raquo;');
                            }
                        });
                    }

                }
            }
        },
        methods: {
            getHTML: function () {
                console.debug(tinymce.activeEditor.getContent());
            }
        },
        created: function () {
            this.nameInput = this.name;
        }
    }
</script>

<style scoped>
    /*Tiny MCE Нотіфікація вимкнути (попередження про неоплату плагіну)*/
    .tox-notifications-container {
        display: none;
    }
</style>
