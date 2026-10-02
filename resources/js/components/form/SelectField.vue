<script setup lang="ts">
import { useFormContext } from '@inertiajs/vue3';
import { computed, inject, ref } from 'vue';
import { headline } from '@/lib/str.js';
import DirtyIndicator from './DirtyIndicator.vue';

type SelectOption = string | number | boolean;

const normalizeValue = (value: SelectOption): string | number => typeof value === 'boolean' ? Number(value) : value;

const props = defineProps<{
    name: string;
    options: SelectOption[] | Record<string, SelectOption>;
    label?: string;
    placeholder?: string;
    note?: string;
    required?: boolean;
}>();

const form = useFormContext();
const formData = inject<Record<string, any>>('formData');

const label = computed(() => props.label || headline(props.name));
const error = computed(() => form?.errors[props.name as keyof typeof form.errors] || '');
const options = computed(
    () => Array.isArray(props.options)
        ? props.options.map((option) => ({ value: normalizeValue(option), label: option }))
        : Object.entries(props.options).map(([label, value]) => ({ value: normalizeValue(value), label }))
);

const defaultValue = computed(() => {
    const value = formData?.[props.name];

    return options.value.find((option) => Object.is(option.value, value) || Object.is(option.value, Number(value)))?.value;
});
const hasDefaultValue = computed(() => defaultValue.value !== undefined);
const isDirty = ref(false);

const updateDirty = (event: Event): void => {
    const value = (event.target as HTMLSelectElement).value;

    isDirty.value = value !== String(formData?.[props.name] ?? '');
};
</script>

<template>
    <div class="space-y-1">
        <label class="label" :for="name">
            {{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>

        <div class="relative">
            <select :id="name"
                    class="select w-full pr-10"
                    :class="{ 'select-error': error }"
                    :name
                    v-bind="hasDefaultValue ? { value: defaultValue } : {}"
                    @input="updateDirty"
                    @change="form?.validate(props.name)">
                <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>

                <option v-for="option in options"
                        :key="String(option.value)"
                        :value="option.value">
                    {{ option.label }}
                </option>
            </select>

            <DirtyIndicator :visible="isDirty" class="right-10" />
        </div>

        <div v-if="error" class="text-xs text-error">
            {{ error }}
        </div>

        <div v-if="note" class="text-xs text-base-content/50">
            {{ note }}
        </div>
    </div>
</template>
