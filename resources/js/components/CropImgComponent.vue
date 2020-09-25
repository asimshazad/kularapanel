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
                    <clipper-basic
                        class="vy-clipper"
                        ref="clipper"
                        :min-scale="0.5"
                        :area="100"
                        :bg-color="'white'"
                        :src="imgURL"
                        :ratio="ratio"
                        :initWidth="cropWidth"
                    >
                        <div class="placeholder" slot="placeholder"></div>
                    </clipper-basic>
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

    import {clipperFixed, clipperPreview} from 'vuejs-clipper'
    import {clipperUpload} from 'vuejs-clipper'

    export default {
        name: "CropImgComponent",
        props: {
            returnBase64: {
                default: true
            },
            ratio: {
                default: 1.7
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
            'clipper-basic': clipperFixed,
            'clipper-upload': clipperUpload,
        },
        data() {
            return {
                texts: {
                    toggleDefault: 'Сховати/Показати',
                    toggleNextCropie: 'Повторити обрізку'
                },
                imgURL: '',
                defaultImagePath: '/kulara/no_img_ico.png',
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
            if (this.image)
                this.resultURL = this.image;

            if (!this.resultURL)
                this.resultURL = this.defaultImagePath
            //Якщо base64 то не відпрявляєм inputFile
            console.log('this.inputName', this.inputName);

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
