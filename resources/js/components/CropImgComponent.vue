<template>
    <div :id="inputName" :class="{'btn-crop': show}">
        <div>
            <input :name="fileInputName" :id="fileInputName" class="btn btn-warning mb-2" type="file"
                   @change="upload($event)">
            <button
                v-if="imgURL"
                @click.prevent="getResult"
                class="btn btn-success mb-2"
            >Обрізати зображення
            </button>
            <button
                v-if="imgURL"
                @click.prevent="show = show !== true"
                class="btn btn-info mb-2"
            >{{resultURL ? texts.toggleNextCropie : texts.toggleDefault}}
            </button>
            <div
                v-if="!returnBase64"
                class="cropie-param">
                <input type="hidden" name="crop_image_x" v-model="form.cropImgX">
                <input type="hidden" name="crop_image_y" v-model="form.cropImgY">
                <input type="hidden" name="crop_image_h" v-model="form.cropImgH">
                <input type="hidden" name="crop_image_w" v-model="form.cropImgW">
            </div>

        </div>
        <transition name="fade">
            <div v-if="show">
                <clipper-basic
                    class="vy-clipper"
                    ref="clipper"
                    :min-scale="0.5"
                    :src="imgURL"
                    :ratio="ratio"
                    :initWidth="cropWidth"
                >
                    <div class="placeholder" slot="placeholder"></div>
                </clipper-basic>
            </div>
        </transition>
        <div v-if="resultURL">
            <div>Результат:</div>
            <img class="result" :src="resultURL" alt="">
        </div>
        <input
            v-if="returnBase64"
            type="hidden" :name="inputNameBase64Name" :value="this.base64Image">
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
                resultURL: '',
                show: false,
                form: {
                    cropImgY: '',
                    cropImgX: '',
                    cropImgH: '',
                    cropImgW: '',

                },
                inputNameBase64Name: this.inputName + '_base64',
                fileInputName: '',
                base64Image: '',


            }
        },
        methods: {
            getResult: function () {
                const canvas = this.$refs.clipper.clip({
                    wPixel: this.cropWidth
                });//call component's clip method
                this.base64Image = this.resultURL = canvas.toDataURL("image/jpeg", 1);//canvas->image

                //Кординати
                this.form.cropImgX = Math.round(this.$refs.clipper.getDrawPos().pos.sx);
                this.form.cropImgY = Math.round(this.$refs.clipper.getDrawPos().pos.sy);
                this.form.cropImgH = Math.round(this.$refs.clipper.getDrawPos().pos.sheight);
                this.form.cropImgW = Math.round(this.$refs.clipper.getDrawPos().pos.swidth);

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
            this.resultURL = this.image;

            //Якщо base64 то не відпрявляєм inputFile
            this.fileInputName = this.returnBase64 ? '' : this.fileInputName;
        },
        watch: {
            imgURL: function () {
                this.show = true;
                this.resultURL = '';
            }
        }

    }
</script>

<style lang="scss">
    #clipper {

        .my-clipper {
            width: 100%;
            max-width: 700px;
        }

        .placeholder {
            text-align: center;
            padding: 20px;
            background-color: lightgray;
        }

        .result {
            width: 100%;
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

    .btn-crop {
        position: fixed;
        top: 0;
        z-index: 600;
        background: white;
    }

</style>
