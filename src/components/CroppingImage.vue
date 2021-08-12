<template>
    <BulmaAccordionItem style="min-width: 100%">
        <div slot="title" class="h1 mb-2">Name: {{ picture.name }}</div>
        <i slot="icon-closed" class="fas fa-caret-down"></i>
        <i slot="icon-open" class="fas fa-caret-up"></i>
        <div slot="content" style="height: auto">
            <label class="file-label">
                <input :disabled="disableAction" class="file-input"
                       type="file" ref="image"
                       accept="image/*" @change="handleFileUpload()">
                <span class="file-cta">
                    <span class="file-icon"><i class="fas fa-upload"></i></span>
                    <span class="file-label">Bild wählen...</span>
                </span>
                <span class="file-name">{{ picture.name }}</span>
            </label>
            <figure v-if="imgSrc" class="image has-ratio">
                <vue-cropper ref="cropper" :src="imgSrc"/>
            </figure>
            <div slot="footer" v-if="imgSrc" class="buttons mt-2">
                <button class="button is-success" :class="{'is-loading': disableAction}" :disabled="disableAction" @click.prevent="cropImage">Fertig</button>
                <button class="button is-warning" :disabled="disableAction" @click.prevent="resetCropBox">Reset</button>
                <button class="button is-link" :disabled="disableAction" @click.prevent="rotate(-90)">Nach links drehen</button>
                <button class="button is-link" :disabled="disableAction" @click.prevent="rotate(90)">Nach rechts drehen</button>
                <button class="button is-link" ref="flipX" :disabled="disableAction" @click.prevent="flipX()">Spiegeln x-Achse</button>
                <button class="button is-link" ref="flipY" :disabled="disableAction" @click.prevent="flipY()">Spiegeln y-Achse</button>
            </div>
        </div>
    </BulmaAccordionItem>
</template>
<script>
import {mapState} from "vuex";
import { BulmaAccordionItem } from 'vue-bulma-accordion';

export default {
    name: 'cropping-image',
    props: {
        picture: {}
    },
    components: {BulmaAccordionItem},
    computed: {
        ...mapState(['previewPages'])
    },
    methods: {
        handleFileUpload() {
            this.disableAction = true;
            const imgBlob = this.$refs.image.files[0];
            this.initialBlob = imgBlob
            this.createBase64Image(imgBlob);
            this.disableAction = false;
        },
        createBase64Image(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imgSrc = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        async cropImage() {
            this.disableAction = true;
            this.imgSrc = this.$refs.cropper.getCroppedCanvas().toDataURL();
            await this.$editor.getLoader().show('Loading...');
            await this.picture.replaceWithFile(
                new File(
                    [].concat(this.blobToFile(this.dataURItoBlob(this.imgSrc))),
                    this.initialBlob.name,
                    {type: this.initialBlob.type}
                )
            );
            await this.$editor.save();
            this.loadPagePreviews();
            await this.$editor.getLoader().hide();
            this.disableAction = false;
        },
        resetCropBox() {
            this.createBase64Image(this.initialBlob);
            this.$refs.cropper.reset();
        },
        blobToFile(theBlob) {
            theBlob.lastModifiedDate = new Date();
            theBlob.name = this.initialBlob.name;
            theBlob.relativePath = this.initialBlob.name;
            return theBlob;
        },
        dataURItoBlob(dataURI) {
            const binary = atob(dataURI.split(',')[1]);
            let array = [];
            for(let i = 0; i < binary.length; i++) {
                array.push(binary.charCodeAt(i));
            }
            return new Blob([new Uint8Array(array)], {type: this.initialBlob.type});
        },
        rotate(deg) {
            this.$refs.cropper.rotate(deg);
        },
        flipX() {
            const dom = this.$refs.flipX;
            let scale = dom.getAttribute('data-scale');
            scale = scale ? -scale : -1;
            this.$refs.cropper.scaleX(scale);
            dom.setAttribute('data-scale', scale);
        },
        flipY() {
            const dom = this.$refs.flipY;
            let scale = dom.getAttribute('data-scale');
            scale = scale ? -scale : -1;
            this.$refs.cropper.scaleY(scale);
            dom.setAttribute('data-scale', scale);
        },
        loadPagePreviews() {
            this.$editor.getPager().pages.then(async (pages) => {
                let prevPages = await Promise.all(pages.map(async (page) => {
                    await this.$editor.getPager().updatePagePreview(page.pageNumber);
                    page.previewImage = await this.$editor.getPager().getPagePreview(page.pageNumber);
                    return page;
                }));
                this.$store.commit('setPreviewPages', prevPages);
            });
        }
    },
    data() {
        return {
            imgSrc: null,
            initialBlob: null,
            disableAction: false
        }
    }
}
</script>
