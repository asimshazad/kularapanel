<template>
    <div>
        <div class="list-group-item bg-light text-left btn-group-sm text-md-right pb-1"
             v-if="addToEditor === true"
        >
            <button
                @click.prevent="allImageToEditor"
                type="button" value="redirect"
                class="btn bg-success mb-2 add-all-img-to-editor text-white">
                <i class="fa fa-plus" aria-hidden="true"></i>
                всі в редактор
            </button>
        </div>
        <vue-dropzone ref="vueDropzone" id="dropzone"
                      :options="dropzoneOptions"
                      :useCustomSlot=true
                      v-on:vdropzone-sending="sendingEvent"
                      v-on:vdropzone-file-added="fileAddedEvent"
                      v-on:vdropzone-removed-file="removedFileEvent"
                      v-on:vdropzone-success="uploadSuccessEvent"
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

            if (!this.images || !this.images.length)
                return console.log('No default img dropzone');

            this.imagesList = JSON.parse(this.images);

            this.insertImageToLib(this.imagesList);

            if (this.paginate) {
                this.paginateJson = JSON.parse(this.paginate);
                this.setPaginate();
            }


            if (this.addToEditor === true)
                this.addEventEditor();

        },
        data: function () {
            return {
                dropzoneOptions: {
                    url: this.url,
                    thumbnailWidth: 150,
                    thumbnailHeight: 150,
                    addRemoveLinks: true,
                    maxFilesize: 1,
                    headers: {"My-Awesome-Header": "header value"},
                    removeType: 'server'
                },
                //Якщо були загружені
                base64Images: {},
                paginateJson: {},
                imagesList: false,
                paginateItems: 5, //К-сть по право і ліво
                paginateStart: 0,
                paginateEnd: 0,
            }
        },
        methods: {
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
                document.querySelectorAll('.to-editor-input').forEach(function (el) {
                    el.addEventListener('click', function (el) {
                        el.originalTarget.checked = true;

                        window.ssds = el;
                        tinymce.activeEditor.selection.setContent(self.wrapImg(el.originalTarget));

                    })
                });
            },
            wrapImg(el) {
                let wrap;
                if (this.short === 'true') {
                    wrap = '<p class="publication-img text-center">[img]' + el.value + '[/img]</p>';

                } else {
                    wrap = '<img src="' + el.getAttribute('originalurl') + '" alt="">';

                }

                return wrap;

            },
            fileAddedEvent(file) {
                if (this.addToEditor === true) {
                    file._removeLink.insertAdjacentHTML("beforeBegin",
                        "<div class=\"custom-control add-editor-check  custom-checkbox\">\n" +
                        "                    <input type=\"checkbox\" originalUrl=\"" + file.originalUrl + "\" name=\"img_to_editor[]\" id=\"id-" + file.upload.uuid + "\" class=\"custom-control-input to-editor-input\"" +
                        " value='" + file.hash_name + "'" +
                        ">\n" +
                        "                    <label for=\"id-" + file.upload.uuid + "\" class=\"custom-control-label\">В редактор</label>\n" +
                        "                </div>"
                    );
                }

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
            }

        }
        ,
    }
</script>

<style lang="scss">
    .dropzone {
        .dz-preview .add-editor-check {
            position: absolute !important;
            z-index: 30;
            margin-left: 15px;
            padding: 10px;
            top: inherit;
            text-decoration: none;
            text-transform: uppercase;
            font-size: .8rem;
            font-weight: 800;

        }

        .dz-remove {
            background: #dc3545;
        }

    }

    .pagination {
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
