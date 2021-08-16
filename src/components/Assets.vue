<template>
    <div v-if="hasAssets">
        <hr class="mt-1">
        <div class="content is-small">
            <h1>Asstes</h1>
        </div>
        <BulmaAccordion dropdown :icon="'custom'" :initial-open-items="[1]"
                        :caretAnimation="{duration: '.6s', timerFunc: 'ease-in-out'}"
                        :slide="{duration: '.9s', timerFunc: 'ease'}">
            <div v-for="picture in pictures" :key="picture.id" class="file has-name is-fullwidth mb-2">
                <cropping-image :picture="picture"/>
            </div>
        </BulmaAccordion>
    </div>
</template>

<script>
import BlockTypes from "@rissc/printformer-ts-common/dist/BlockTypes";
import CroppingImage from "./CroppingImage";
import Events from "@rissc/printformer-editor-client/dist/Events";
import {BulmaAccordion} from 'vue-bulma-accordion';

export default {
    name: "assets",
    components: {CroppingImage, BulmaAccordion},
    created() {
        this.loadPictures();
    },
    mounted() {
        window.events.on(Events.EDITOR_PAGES_PAGED, (pageInfo) => {
            this.currentPage = pageInfo.pageNumber
            this.loadPictures();
        });
    },
    methods: {
        async loadPictures() {
            this.pictures = await this.$editor.findEditorObjects({
                type: BlockTypes.ASSET
            });
        }
    },
    computed: {
        hasAssets() {
            return this.pictures.length > 0
        }
    },
    data() {
        return {
            pictures: {},
            imageUrl: '',
            currentPage: 1
        }
    }
}
</script>
