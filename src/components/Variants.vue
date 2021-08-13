<template>
    <div>
        <hr>
        <div class="content is-small">
            <h1>Varianten</h1>
        </div>
        <div class="columns is-multiline is-mobile mb-2">
            <div v-for="variant in variants" @click="loadVariant(variant, variant.id)" class="column is-2-desktop is-2-tablet">
                <div v-if="variant.thumbnail.type === 'color'" class="box variant"
                     :class="{'variant-active': currentId === variant.id}"
                     :style="{'background-color': variant.thumbnail.value}">
                    <figure class="image is-square">
                        <span style="border-style: none; outline-color: transparent"></span>
                    </figure>
                </div>
                <div v-else class="box variant p-0"
                        :class="{'variant-active': currentId === variant.id}">
                    <figure class="image is-square">
                        <img :src="variant.thumbnail.value" height="70px" width="auto">
                    </figure>
                </div>
                {{ variant.name }}
            </div>
        </div>
    </div>
</template>

<style>
.variant-active {
    border: 3px solid red !important;
}

.variant {
    border: 3px solid transparent;
}
</style>

<script>
import {mapState} from "vuex";

export default {
    name: "variants",
    computed: {
        ...mapState(['editorConfig'])
    },
    mounted() {
        this.$editor.getVariants().getActive().then((variant) => {
            if (variant) this.currentId = variant.id;
        });

        this.$editor.getVariants().getAll().then((variants) => {
            this.variants = variants;
        });
    },
    methods: {
        async loadVariant(variant, index) {
            if (this.currentId !== index) {
                await this.$editor.getLoader().show("Loading...");
                await this.$editor.getVariants().apply(variant.id);
                await this.$editor.save();
                await this.$editor.getLoader().hide();
                this.currentId = index;
            }
        }
    },
    data() {
        return {
            variants: [],
            currentId: 0
        }
    }
}
</script>
