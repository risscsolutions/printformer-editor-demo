import Vue from 'vue';
import Vuex from 'vuex';
import VueCropper from 'vue-cropperjs';
import EventEmitter from 'eventemitter3';
import makeStore from "./store";
import {ValidationProvider} from 'vee-validate/dist/vee-validate.full.esm';
import {configure, ValidationObserver} from 'vee-validate';

import EditorSection from './src/EditorSection';
import Connector from "@rissc/printformer-editor-client/dist/Connector";
import PagePreview from "./src/components/PagePreview";
import FormFields from "./src/components/FormFields";
import FormField from "./src/components/FormField";
import Variants from "./src/components/Variants";
import Assets from "./src/components/Assets";
import CroppingImage from "./src/components/CroppingImage";

Vue.use(Vuex);
Vue.component('form-fields', FormFields);
Vue.component('form-field', FormField);
Vue.component('variants', Variants);
Vue.component('assets', Assets);
Vue.component('cropping-image', CroppingImage);
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)
Vue.component('VueCropper', VueCropper);

configure({
    classes: {
        valid: 'is-success',
        invalid: 'is-danger'
    }
});

window.onload = () => {
    const connector = new Connector();
    const editorIframe = document.getElementById('editor-iframe');
    editorIframe.src = window.pfURL;
    window.events = new EventEmitter();

    // mobile chrome ios fix
    if (navigator.userAgent.match('CriOS')) {
        document.body.style.height = innerHeight + 'px';
        document.body.style.position = 'relative';
    }

    const store = makeStore()

    connector.connect(editorIframe, window.events).then(editor => {
        Vue.prototype.$editor = editor;

        new Vue({
            store,
            render: createElement => createElement(EditorSection)
        }).$mount("#main");

        new Vue({
            store,
            render: createElement => createElement(PagePreview)}
        ).$mount("#pages");
    });
}
