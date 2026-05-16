<script>
export default {
    props: {
        modelValue: {
            type: [String, Number],
            default: ''
        },
        label: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        },
        type: {
            type: String,
            default: 'text'
        },
        format: {
            type: String,
            default: ''
        },
        inputmode: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        inputType() {
            return this.format === 'number' ? 'text' : this.type;
        },
        inputValue: {
            get() {
                if (this.format === 'number') {
                    return this.formatNumber(this.modelValue);
                }

                return this.modelValue;
            },
            set(newValue) {
                if (this.format === 'number') {
                    this.$emit('update:modelValue', this.normalizeNumber(newValue));
                    return;
                }

                this.$emit('update:modelValue', newValue);
            }
        }
    },
    methods: {
        normalizeNumber(value) {
            const stringValue = String(value ?? '');
            const withoutSeparators = stringValue.replace(/,/g, '');
            const sanitized = withoutSeparators.replace(/[^\d.]/g, '');
            const parts = sanitized.split('.');

            if (parts.length <= 1) {
                return sanitized;
            }

            return `${parts[0]}.${parts.slice(1).join('')}`;
        },
        formatNumber(value) {
            const normalized = this.normalizeNumber(value);

            if (normalized === '') {
                return '';
            }

            const hasTrailingDot = normalized.endsWith('.');
            const [integerPart, decimalPart] = normalized.split('.');
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            if (hasTrailingDot) {
                return `${formattedInteger}.`;
            }

            if (decimalPart !== undefined) {
                return `${formattedInteger}.${decimalPart}`;
            }

            return formattedInteger;
        }
    }
};
</script>

<template>
    <div class="mb-3">
        <label :for="id" class="mb-1 block text-sm font-medium text-slate-700">{{ label }}<span v-if="required" class="ml-0.5 text-red-500">*</span></label>
        <input
            :type="inputType"
            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
            :id="id"
            :placeholder="placeholder"
            :required="required"
            :name="id"
            :inputmode="inputmode || null"
            v-model="inputValue"
        />
    </div>
</template>

<style scoped>

</style>
