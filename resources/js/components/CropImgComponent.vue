<template>
    <div :id="inputName" class="crop-image-cmp" :class="{'btn-crop': show}">
        <div class="crop-image-body">
            <div v-if="show">
                <button
                    @click.prevent="show = show !== true"
                    class="btn btn-danger m-2 pull-right"><i class="fa fa-window-close" aria-hidden="true"></i>
                </button>
                <button
                    v-if="imgURL"
                    @click.prevent="getResult"
                    class="btn btn-success m-2  pull-right"
                ><i class="fa fa-crop" aria-hidden="true"></i>
                </button>
                <button
                    @click.prevent="openFile"
                    class="btn btn-warning m-2  pull-right"><i class="fa fa-folder-open" aria-hidden="true"></i>
                </button>
            </div>
            <transition name="fade">
                <div v-if="show">
                    <component
                        v-bind:is="openModule"
                        class="vy-clipper"
                        ref="clipper"
                        :min-scale="0.5"
                        :area="100"
                        :bg-color="'white'"
                        :src="imgURL"
                        :ratio="ratio"
                        :initWidth="cropWidth"
                        :top="0"
                        :left="0"
                        :width="100"
                        :height="100"
                    >
                        <div class="placeholder" slot="placeholder"></div>
                    </component>

                    <input class="input-img-info" type="text" v-model="imageAlt" placeholder="Image Alt"><br>
                    <input class="input-img-info" type="text" v-model="imageTitle" placeholder="Image Title">
                </div>
            </transition>

            <input type="hidden" :name="inputNameBase64Name" :value="this.base64Image">
        </div>
        <div v-if="resultURL && !show" class="row">
            <div class="col-sm-12 col-md-6">
                <img
                    @click.prevent="toggleModule"
                    class="result" :src="resultURL" alt="">
            </div>
            <div class="col-sm-12 col-md-6">
                <label v-if="defaultImagePath !== resultURL" class="input-img-info">
                    Image Alt
                    <input v-model="imageAlt" type="text" :name="inputName + '_alt'" placeholder="Image Alt"><br>
                </label>
                <label v-if="defaultImagePath !== resultURL" class="input-img-info">
                    Image Title
                    <input v-model="imageTitle" type="text" :name="inputName + '_title'" placeholder="Image Title">
                </label>
            </div>
        </div>
        <input :name="returnBase64 ?'': inputName " id="crop-image-input" class="btn btn-warning mb-2 hide" type="file"
               @change="upload($event)">

    </div>
</template>

<script>
    /**
     * Сам плагін не ріже зображення, а генерує координати які відправляються при збереженні з усіма полями форми
     * cropImgX - старт по X
     * cropImgY - старт по Y
     * cropImgH - Висота вихідного IMG
     * cropImgH - Ширина вихідного IMG
     * cropWidth - ширина зображення на виході
     * image - Вставити зображення яке вже існує для наприклад публікації
     * returnBase64 - По замовчуванню обрізаємо зображення на стороні клієнта і відправляємо в base64.
     *              - Якщо false то відправимо файл з параметрами обрізки
     */

    import {clipperFixed, clipperPreview, clipperBasic} from 'vuejs-clipper'
    import {clipperUpload} from 'vuejs-clipper'

    export default {
        name: "CropImgComponent",
        props: {
            returnBase64: {
                default: true
            },
            ratio: {
                default: null
            },
            inputName: {
                default: 'crop_image'
            },
            cropWidth: {
                default: 300
            },
            //Урл для зображення в превью
            image: {
                default: '',
                type: String
            }
        },
        components: {
            clipperFixed,
            clipperUpload,
            clipperBasic,
            clipperPreview,
        },
        data() {
            return {
                openModule: 'clipper-fixed',
                texts: {
                    toggleDefault: 'Сховати/Показати',
                    toggleNextCropie: 'Повторити обрізку'
                },
                clipperConf: {
                    imgURL: '',
                    defaultImagePath: '/asimshazad/no_img_ico.png',
                    resultURL: '',
                    show: false,
                    ratio: 1,
                    inputNameBase64Name: this.inputName, //+ '_base64',
                },
                imgURL: '',
                defaultImagePath: '/asimshazad/no_img_ico.png',
                resultURL: '',
                show: false,
                inputNameBase64Name: this.inputName, //+ '_base64',
                base64Image: '',
                imageAlt: '',
                imageTitle: '',


            }
        },
        methods: {
            toggleModule() {
                this.show = this.show !== true

                if (this.resultURL && !this.imgURL)
                    this.imgURL = this.resultURL;

                if (this.show && this.imgURL === this.defaultImagePath)
                    this.openFile()
            },
            openFile() {
                document.getElementById('crop-image-input').click()
            },
            getResult: function () {
                const canvas = this.$refs.clipper.clip({
                    wPixel: this.cropWidth
                });//call component's clip method
                this.base64Image = this.resultURL = canvas.toDataURL("image/jpeg", 1);//canvas->image

                this.show = false;
            },
            upload: function (e) {
                if (e.target.files.length !== 0) {
                    if (this.imgURL) URL.revokeObjectURL(this.imgURL);
                    this.imgURL = window.URL.createObjectURL(e.target.files[0]);
                }
            }
        },
        created: function () {
            if (!this.ratio)
                this.openModule = 'clipper-basic'

            //зливаэмо настройки
            this.clipperConf = {...this.clipperConf, ...this.options}

            if (this.image)
                this.resultURL = this.image;

            if (!this.resultURL)
                this.resultURL = this.defaultImagePath
            //Якщо base64 то не відпрявляєм inputFile
            console.log('this.inputName', this.inputName);

            console.log('options', this.options);
            console.log('clipperConf', this.clipperConf);

            console.log('merge',);
        },
        computed: {
            options() {
                return Object.keys(this.$options.props).reduce((acc, key) =>
                        Object.assign(acc, this[key] !== undefined ? {[key]: this[key]} : {}),
                    {})
            }
        },
        watch: {
            imgURL: function () {
                this.show = true;
                // this.resultURL = '';
            }
        }

    }
</script>

<style lang="scss">
    .crop-image-cmp {

        .placeholder {
            text-align: center;
            padding: 20px;
            background-color: lightgray;
        }

        .result {
            cursor: pointer;
            max-width: 320px;
            vertical-align: inherit;
        }

        .fade-enter-active, .fade-leave-active {
            transition: opacity .5s;
        }

        .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */
        {
            opacity: 0;
        }


    }

    .hide {
        display: none;
    }

    .pull-right {
        float: right
    }

    .btn-crop .crop-image-body {
        max-width: 600px;
        background: #d6d9e6;
        margin: 0 auto;
        padding: 30px;
        border: 1px #baf1fe solid;
    }

    .btn-crop.crop-image-cmp {
        background: rgba(52, 58, 64, .6);
        padding: 30px;
        position: fixed;
        z-index: 600;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .input-img-info {
        width: 100%;
        margin: 1px 0;
    }

    .input-img-info input {
        width: 100%;
    }


</style>
