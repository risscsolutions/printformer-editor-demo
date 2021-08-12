<template>
    <ValidationObserver ref="observer" v-slot="{ invalid, valid }">
        <ValidationProvider :rules="rules" v-slot="{errors, classes}">
            <div class="field">
                <label class="label">{{ name }}</label>
                <div class="control" v-if="type === 'text'">
                    <input v-model="formValue"
                           class="input"
                           :class="classes"
                           type="text" :id="name" :pattern="regexString"
                           :disabled="disabledForm"
                    />
                </div>
                <div class="control" v-if="type === 'select'">
                    <div class="select">
                        <select v-model="formValue">
                            <option v-for="option in options" :value="option.value">{{ option.label }}</option>
                        </select>
                    </div>
                </div>
                <p class="help is-success" v-if="errors.length === 0">&nbsp;</p>
                <p class="help is-danger" v-else v-for="error in errors">
                    {{ error }}
                </p>
            </div>
        </ValidationProvider>
    </ValidationObserver>
</template>

<script>
import {mapState} from "vuex";
import {debounce} from "lodash";

export default {
    name: "form-field",
    props: {
        name: String,
        type: String,
        identifier: String,
        options: Array,
        isHidden: Boolean,
        isRequired: Boolean,
        regexPattern: Array,
        value: String
    },
    data() {
        return {
            formValue: this.value ? this.value : '',
            disabledForm: false
        }
    },
    computed: {
        regexString() {
            if (this.regexPattern.length > 0) {
                return this.regexPattern[0] !== null && this.regexPattern[1] !== null
                    ? this.regexPattern[0] + this.regexPattern[1]
                    : this.regexPattern[0];
            } else {
                return ''
            }
        },
        rules() {
            let rulesObject = {};

            if (this.isRequired) {
                rulesObject.required = true
            }

            if (this.regexPattern.length > 0) {
                rulesObject.regex = this.regexString
            }

            return rulesObject
        },
        ...mapState(['is3D'])
    },
    created() {
        this.debouncedUpdate = debounce(async () => {
            await this.$editor.getFormEditor().update(this.identifier,this.formValue, null);
        }, 500);
    },
    methods: {
        async updateField() {
            const valid = await this.$refs.observer.validate()
            if (valid) {
                if (!this.is3D) {
                    await this.$editor.getFormEditor().update(this.identifier,this.formValue, null);
                } else {
                    this.disabledForm = true;
                    this.debouncedUpdate();
                    this.disabledForm = false;
                }
            }
        }
    },
    watch: {
        formValue() {
            this.updateField()
        }
    }
}
</script>
