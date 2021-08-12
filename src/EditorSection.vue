<template>
    <div class="box" v-if="this.editorLoaded">
        <div class="title">{{ editorConfig.draftInfo.masterName }}</div>
        <variants></variants>
        <form-fields></form-fields>
        <assets></assets>
    </div>
    <div class="box" v-else>
        Laden....
    </div>
</template>

<script>
import Events from "@rissc/printformer-editor-client/dist/Events";
import {mapState} from "vuex";

export default {
    name: "editor-section",
    computed: {
        ...mapState(['editorConfig', 'previewPages'])
    },
    mounted() {
        window.events.on(Events.EDITOR_LOADED, async (config) => {
            this.$store.commit('setEditorConfig', config);

            if (config.configuration.show3DPreview) {
                const editorIframe = document.getElementById('editor-iframe');
                const threeDee = document.getElementById('three-dee-iframe');
                threeDee.src = editorIframe.src.replace(new RegExp(/\/embed/), '/3d');
                this.$editor.setThreeDeeElement(threeDee);
            }

            this.$editor.getPager().pages.then(async (pages) => {
                let prevPages = await Promise.all(pages.map(async (page) => {
                    page.previewImage = await this.$editor.getPager().getPagePreview(page.pageNumber);
                    return page;
                }));
                this.$store.commit('setPreviewPages', prevPages);
            });

            this.editorLoaded = true;
        });
    },
    data() {
        return {
            editorLoaded: false
        }
    }
}
</script>
