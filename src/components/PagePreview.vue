<template>
    <div v-if="previewPages.length > 0">
        <div class="title">Seiten</div>
        <div class="columns is-multiline">
            <div class="column is-2" v-for="page in previewPages">
                <figure class="image has-ratio box m-0 p-1"
                        :style="{'border': currentPage === page.pageNumber ? 'black 5px solid': 'transparent 5px solid'}">
                    <img @click="changePage(page.pageNumber)" :src="page.previewImage"/>
                    <h2>Seite {{ page.pageNumber }}</h2>
                </figure>
            </div>
            <div class="column is-2 is-flex"
                 style="position:relative;"
                 v-if="has3D"
                 @click="load3DModel()">
                <figure class="image has-ratio box m-0 p-1"
                        style="background-color: white"
                        :style="{'border': currentPage === '3D' ? 'black 5px solid': 'transparent 5px solid'}">
                    <img style="filter: blur(2px); opacity: 0" :src="previewPages[0].previewImage"/>
                </figure>
                <h2 class="three-dee-text title">3D</h2>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .three-dee-text {
        width: 50px;
        height: 50px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -25px 0 0 -25px;
        font-weight: bolder;
    }
</style>
<script>
import {mapState} from "vuex";
import Events from "@rissc/printformer-editor-client/dist/Events";

export default {
    name: "page-preview",
    computed: {
        ...mapState(['editorConfig', 'is3D', 'previewPages'])
    },
    mounted() {
        window.events.on(Events.EDITOR_LOADED, () => {
            this.currentPage = 1;
            this.has3D = this.editorConfig.configuration.show3DPreview;
        });

        window.events.on(Events.EDITOR_PAGES_PAGED, (pageInfo) => {
            this.currentPage = pageInfo.pageNumber;
        });
    },
    methods: {
        changePage(pageNumber) {
            this.$store.commit('setIs3D', false);
            const editorIframe = document.getElementById('editor-iframe');
            const threeDee = document.getElementById('three-dee-iframe');
            threeDee.style.display = 'none';
            editorIframe.style.display = 'block';
            if (this.currentPage !== pageNumber) this.$editor.getPager().showPage(pageNumber)
        },
        load3DModel() {
            if (this.is3D !== this.has3D) {
                this.$store.commit('setIs3D', true);
                this.has3D = true;
                this.currentPage = '3D';
                const editorIframe = document.getElementById('editor-iframe');
                const threeDee = document.getElementById('three-dee-iframe');
                threeDee.style.display = 'block';
                editorIframe.style.display = 'none';
            }
        }
    },
    data() {
        return {
            currentPage: null,
            has3D: false
        }
    }
}
</script>
