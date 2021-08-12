<template>
    <div>
        <form-field v-for="tdi in tdis"
                    :key="tdi.identifier"
                    :name="tdi.label"
                    :type="tdi.type"
                    :identifier="tdi.identifier"
                    :options="tdi.options"
                    :regexPattern="tdi.regex"
                    :value="tdi.value || tdi.defaultValue"
                    :isRequired="tdi.is_required"
                    :isHidden="tdi.is_hidden">
        </form-field>
    </div>
</template>

<script>
import Events from "@rissc/printformer-editor-client/dist/Events";
import FormField from "./FormField";

export default {
    name: "form-fields",
    components: {FormField},
    mounted() {
        window.events.on(Events.EDITOR_PAGES_PAGED, (pageInfo) => {
            this.currentPage = pageInfo.pageNumber
        });

        window.events.on(Events.EDITOR_SCOPE_CHANGED, (tdis) => {
            this.tdis = tdis.tdis
        });
    },
    data() {
        return {
            tdis: [],
            currentPage: 1
        }
    }
}
</script>
