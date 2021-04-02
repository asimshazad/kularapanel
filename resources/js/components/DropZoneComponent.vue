<template>
    <div class="dropzone-container"
         :class="{fixed: fixed}"
    >
        <div class="list-group-item bg-light text-left btn-group-sm text-center pb-1"
             v-if="addToEditor === true"
        >
            <button
                title="Додати всі зображення в редактор"
                @click.prevent="allImageToEditor"
                type="button" value="redirect"
                class="btn bg-success mb-2 add-all-img-to-editor text-white">
                <i class="fa fa-plus" aria-hidden="true"></i>
                всі в редактор
            </button>
            <button
                title="Зафіксувати справа"
                class="btn bg-success mb-2 text-white btn-fixed"
                @click.prevent="fixed = !fixed">
                <i v-if="!fixed" class="fa fa-window-restore" aria-hidden="true"></i>
                <i v-if="fixed" class="fa fa-reply-all" aria-hidden="true"></i>

            </button>
        </div>
        <vue-dropzone ref="vueDropzone" :id="dropZoneId"
                      :options="dropzoneOptions"
                      :useCustomSlot=true
                      :duplicateCheck="true"
                      v-on:vdropzone-sending="sendingEvent"
                      v-on:vdropzone-file-added="fileAddedEvent"
                      v-on:vdropzone-removed-file="removedFileEvent"
                      v-on:vdropzone-success="uploadSuccessEvent"
                      v-on:vdropzone-thumbnail="thumbnail"
        >
            <div class="dropzone-custom-content">
                <h3 class="dropzone-custom-title">Перетягніть, щоб завантажити вміст!</h3>
                <div class="subtitle">... або натисніть, щоб вибрати файл із комп'ютера</div>
            </div>
        </vue-dropzone>
        <div class="dropzone-pagination"
             v-if="paginate !== false && paginateJson.last_page > 1">
            <ul class="pagination">
                <li
                    v-if="paginateJson.current_page > 1"
                    class="page-item">
                    <a :href="paginateJson.prev_page_url" :aria-disabled="paginateJson.prev_page_url" rel="prev"
                       aria-label="« Назад" class="page-link"
                       @click.stop.prevent="getPage(paginateJson.prev_page_url)"
                    >‹‹</a>
                </li>
                <li
                    class="page-item"
                    :class="{active:paginateJson.current_page == 1}"
                >
                    <a :href="paginateJson.first_page_url" aria-label="Перша" class="page-link"
                       @click.stop.prevent="getPage(paginateJson.first_page_url)"
                    >1</a>
                </li>
                <li
                    v-if="paginateJson.current_page > paginateItems+2 "
                    class="page-item">
                    <a class="page-link"
                       @click.stop.prevent="getPage(paginateJson.last_page)"
                    >...</a>
                </li>
                <li class="page-item"
                    v-for="i in  (paginateEnd)"
                    v-if="paginateStart <= i"
                    :class="{active:paginateJson.current_page == i}"
                >
                    <a :href="paginateJson.path + '?page=' + (i)" class="page-link"
                       @click.stop.prevent="getPage(paginateJson.path + '&page=' + i)"
                    >{{i}}</a>
                </li>
                <li
                    v-if="paginateJson.current_page < paginateJson.last_page-6"
                    class="page-item">
                    <a class="page-link"
                       @click.stop.prevent="getPage(paginateJson.last_page)"
                    >...</a>
                </li>
                <li
                    class="page-item"
                    :class="{active:paginateJson.current_page == paginateJson.last_page}"
                >
                    <a :href="paginateJson.last_page_url" aria-label="Остання" class="page-link"
                       @click.stop.prevent="getPage(paginateJson.last_page_url)"
                    >{{paginateJson.last_page}}</a>
                </li>
                <li
                    v-if="paginateJson.current_page < paginateJson.last_page"
                    class="page-item">
                    <a :href="paginateJson.next_page_url" :aria-disabled="!paginateJson.next_page_url"
                       aria-label="Далі »" class="page-link"
                       @click.stop.prevent="getPage(paginateJson.next_page_url)"
                    >››</a>
                </li>
            </ul>
        </div>
        <input
            v-for="item in base64Images"
            type="hidden" name="base64_dropzone_files[]" :value="item"
        >
    </div>
</template>

<script>
    /**
     * url - куди відправляти для збереження
     * addToEditor - Показати чекбокси для встівки в текс новини (Обработчик вставки поза цим плагіном)
     *_token - csrf _token
     * id - Ідентифікатор моделі при редагуванні
     */
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'


    export default {
        name: "dropZoneComponent",
        props: {
            'url': {
                default: 'https://httpbin.org/post',
            },
            'returnBase64': {
                default: false
            },
            'short': {
                default: true
            },
            'addToEditor': {
                default: false,
                type: Boolean
            },
            'paginate': {
                default: false
            },
            '_token': {
                default: ''
            },
            'id': {
                default: null
            },
            'images': {
                default: false
            },
            'inputName': {
                default: 'dropzone_images'
            }
        },
        components: {
            vueDropzone: vue2Dropzone
        },
        mounted() {
            this.dropzoneElement = document.getElementById(this.dropZoneId)

            if (this.addToEditor === true)
                this.addEventEditor();

            if (!this.images || !this.images.length)
                return console.log('No default img dropzone');

            this.imagesList = JSON.parse(this.images);

            this.insertImageToLib(this.imagesList);

            if (this.paginate) {
                this.paginateJson = JSON.parse(this.paginate);
                this.setPaginate();
            }

            this.updateImageAttrsEvent();

        },
        data: function () {
            return {
                fixed: false,
                dropZoneId: 'dropzone-' + Math.random().toString(36).substring(7),
                dropzoneElement: null,
                dropzoneOptions: {
                    url: this.url,
                    thumbnailWidth: 300,
                    // thumbnailHeight: 200,
                    addRemoveLinks: true,
                    maxFilesize: 2,
                    headers: {"My-Awesome-Header": "header value"},
                    removeType: 'server',
                    previewTemplate: this.template(),
                },
                //Якщо були загружені
                base64Images: {},
                paginateJson: {},
                imagesList: false,
                paginateItems: 5, //К-сть по право і ліво
                paginateStart: 0,
                paginateEnd: 0,
                lastResponse: {},
                file: {}
            }
        },
        methods: {
            //Следим были ли изменения атрибутов изображения
            updateImageAttrsEvent(el) {

                let imgAttrs = document.getElementById(this.dropZoneId).querySelectorAll('.img-attrs input');
                imgAttrs.forEach((el) => {
                    el.addEventListener('change', (el) => {
                        el.target.parentElement.classList.add('changed');
                        let formData = new FormData(el.target.parentElement);
                        formData.append('_token', this._token);
                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "/admin/media/update-attrs");
                        xhr.send(formData);
                    })
                })
            },
            setPaginate() {
                let currentPg = this.paginateJson.current_page,
                    lastPg = this.paginateJson.last_page,
                    items = this.paginateItems;

                this.paginateStart = currentPg <= items + 1 ? 2 : currentPg - items;
                this.paginateEnd = (lastPg > items * 2 + this.paginateStart) ? items * 2 + this.paginateStart : lastPg - 1;
                this.paginateStart = (currentPg + items * 2 > lastPg) ? lastPg - (items * 2) : this.paginateStart;
                this.paginateStart = this.paginateStart <= 1 ? 2 : this.paginateStart;
            },
            // додати перед выдправкою поля
            sendingEvent(file, xhr, formData) {
                formData.append('_token', this._token);
                if (this.id)
                    formData.append('id', this.id);

            },
            removedFileEvent(file, error, xhr) {
                if (this.returnBase64) {
                    this.$delete(this.base64Images, file.upload.uuid);
                    if (!file.url)
                        return true;
                }

                //Не видаляэмо на ссервері
                if (this.dropzoneOptions.removeType === 'client')
                    return true;
                axios
                    .get(file.remove_link)
                    .then(response => {
                        return response.status;
                    })
            },
            uploadSuccessEvent(file, response) {
                if (this.returnBase64) {
                    this.$set(this.base64Images, file.upload.uuid, file.dataURL);
                    return true;
                }
                file.remove_link = response.remove_link;
                file.hash_name = response.hash_name;
                file.originalUrl = response.originalUrl;
                file.id = response.id;
                this.fileAddedEvent(file, response)
                this.updateImageAttrsEvent();


            },
            allImageToEditor() {
                let self = this;
                let inputs = document.querySelectorAll('.to-editor-input');

                inputs.forEach(function (el) {
                    tinymce.activeEditor.selection.setContent(self.wrapImg(el));
                })
            },
            addEventEditor() {
                let self = this;
                //Додати вибрані
                document.querySelector('#' + this.dropZoneId).addEventListener('click', function (el) {
                    el.stopPropagation();
                    el.preventDefault();

                    if (el.target.getAttribute('hashname')) {
                        tinymce.activeEditor.selection.setContent(self.wrapImg(el.target));
                        return false;
                    }
                })
                ;
            },
            wrapImg(el) {
                let wrap;
                if (this.short === true) {
                    wrap = '<p class="publication-img text-center">[img]' + el.getAttribute('hashname') + '[/img]</p>';
                } else {
                    wrap = '<img src="' + el.getAttribute('originalurl') + '" alt="">';
                }
                return wrap;
            },
            fileAddedEvent(file, response) {
                /*
                 * Если нет ID не добавляєм так як цей метод був визваний при відповіді сервера після відправки зображення
                 */
                if(!file.id)
                    return true

                let alt = (file.img_attrs && file.img_attrs.alt) ? file.img_attrs.alt : '';
                let title = (file.img_attrs && file.img_attrs.title) ? file.img_attrs.title : '';
                let source = (file.img_attrs && file.img_attrs.source) ? file.img_attrs.source : '';
                let insertHtml = "<form class='img-attrs'>" +
                    "<span>Alt </span><input type='text' value='" + alt + "' name='dzImgAttrs[" + file.id + "][alt]'><br>" +
                    "<span>Title </span><input type='text'  value='" + title + "'  name='dzImgAttrs[" + file.id + "][title]'><br>" +
                    "<span>Джерело</span><input type='text'  value='" + source + "'  name='dzImgAttrs[" + file.id + "][source]'><br>" +
                    "</form>";

                if (this.addToEditor === true) {
                    insertHtml += "<a hashName='" + file.hash_name + "' originalUrl=\"" + file.originalUrl + "\" class=\"to-editor-input pointer\">В редактор</a>";

                }

                file._removeLink.insertAdjacentHTML("beforeBegin", insertHtml);

            },
            insertImageToLib(imagesList) {
                for (let i = 0; i < imagesList.length; i++) {
                    this.$refs.vueDropzone.manuallyAddFile(this.imagesList[i], this.imagesList[i].url);

                }
            },
            getPage(link) {
                axios
                    .get(link)
                    .then(response => {
                        this.imagesList = response.data.images;
                        this.paginateJson = JSON.parse(response.data.paginate);
                        this.dropzoneOptions.removeType = 'client';
                        this.$refs.vueDropzone.removeAllFiles();
                        this.dropzoneOptions.removeType = 'server';

                        this.setPaginate();
                        this.insertImageToLib(this.imagesList)

                    })
            },
            template: function () {
                return `<div class="dz-preview dz-file-preview">
                    <div class="dz-image">
                        <div data-dz-thumbnail-bg></div>
                    </div>
                    <div class="dz-details">
                        <div class="dz-size"><span data-dz-size></span></div>
                        <div class="dz-filename"><span data-dz-name></span></div>
                    </div>
                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                    <div class="dz-success-mark"><i class="fa fa-check"></i></div>
                    <div class="dz-error-mark"><i class="fa fa-close"></i></div>
                </div>
                `;
            },
            thumbnail: function (file, dataUrl) {
                var j, len, ref, thumbnailElement;
                if (file.previewElement) {
                    file.previewElement.classList.remove("dz-file-preview");
                    ref = file.previewElement.querySelectorAll("[data-dz-thumbnail-bg]");
                    for (j = 0, len = ref.length; j < len; j++) {
                        thumbnailElement = ref[j];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.style.backgroundImage = 'url("' + dataUrl + '")';
                    }
                    return setTimeout(((function (_this) {
                        return function () {
                            return file.previewElement.classList.add("dz-image-preview");
                        };
                    })(this)), 1);
                }
            },
        },
    }
</script>

<style lang="scss">
    .dropzone {
        font-family: 'Arial', sans-serif;
        letter-spacing: 0.2px;
        color: #777;
        transition: background-color .2s linear;
        min-height: 200px;
        padding: 10px 0;
        margin: 0;
        text-align: center;

        &:hover {
            background-color: #ffe9c1;

        }

        .dz-preview {
            padding: 0;
            margin: 5px;
            border: 2px solid #682727;

            .dz-image {
                width: 270px;
                height: 180px;
                margin-bottom: 10px;


                > div {
                    width: inherit;
                    height: inherit;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position-x: center;
                    animation-duration: 1s;

                }

                > img {
                    width: 100%;
                }

                .dz-details {
                    color: white;
                    transition: opacity .2s linear;
                    text-align: center;

                }
            }

        }

        .img-attrs {
            position: relative;
            z-index: 33;
            text-align: right;
            font-size: 12px;
            background: white;

            span {
                margin: 2px 0;

                display: inline-block;
                text-align: left;
            }

            input {
                width: 78%;
                margin: 2px;
                padding-left: 2px;
                border: 1px #e8e1e1 solid;

                &:hover {
                    cursor: initial;
                }
            }
        }

        .dz-preview .dz-remove,
        .dz-preview .to-editor-input,
        {
            position: relative;
            z-index: 33;
            color: #fff;
            margin: 0;
            padding: 10px;
            border: 2px #fff solid;
            text-decoration: none;
            text-transform: uppercase;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: 1.1px;
            opacity: .2;
            width: 50%;
            text-align: center;
            display: inline-block;
            cursor: pointer;
            top: 0;
        }

        .dz-remove {
            background: red;
            color: #fff;
        }

        .to-editor-input {
            background: green;
            color: #fff;

        }

        .dz-preview:hover .dz-image > div {
            background-size: cover;
        }

        .dz-preview:hover .img-attrs {
            background: none;
            color: white;

        }

        .dz-preview:hover .to-editor-input,
        .dz-preview:hover .dz-remove,
        {
            color: white;
            opacity: 1;
        }

        .to-editor-input:hover,
        .dz-remove:hover,
        {
            text-decoration: underline !important;

        }
    }

    .dropzone .dz-success-mark, .dz-error-mark, .dz-remove {
        display: none;
    }

    .dropzone-container.fixed .dropzone {
        position: fixed;
        right: 0;
        top: 0;
        bottom: 0;
        width: 300px;
        z-index: 1000;
        overflow-y: auto;
        background: gold;
        border: 2px solid black;
    }

    .dropzone-container.fixed .btn-fixed {
        position: fixed;
        top: 0;
        right: 0;
        z-index: 99999;
        font-size: 20px;
        border: 2px solid white;
    }

    .dropzone-container.fixed .dropzone-pagination {

    }

    .dropzone-container.fixed .dropzone-pagination {
        position: fixed;
        bottom: 0;
        z-index: 1001;
        border: 2px solid black;
        padding: 10px;
        background: gold;
        width: 300px;
        right: 0;

        .page-link {
            padding: .4rem .65rem;
        }
    }

    .page-link {
        position: relative;
        display: block;
        margin-left: -1px;
        line-height: 1.25;
        color: #28a745;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .page-item.active .page-link {
        z-index: 1;
        color: #fff;
        background-color: #28a645;
        border-color: #28a645;
    }

    .pagination {
        background: #f7f7f7;
        display: -ms-flexbox;
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: .25rem;
        align-content: center;
        align-items: center;
        justify-content: center;
    }
</style>
