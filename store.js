import Vuex from 'vuex';

export default function makeStore() {
    return new Vuex.Store({
        state: {
            editorConfig: {},
            is3D: false,
            previewPages: []
        },
        mutations: {
            setEditorConfig(state, editorConfig) {
                state.editorConfig = editorConfig;
            },
            setIs3D(state, is3D) {
                state.is3D = is3D;
            },
            setPreviewPages(state, previewPages) {
                state.previewPages = previewPages;
            }
        }
    });
}
