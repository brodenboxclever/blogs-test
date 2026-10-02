<script setup lang="ts">
import { useFormContext } from '@inertiajs/vue3';
import { Asterisk } from '@lucide/vue';
import { computed, inject, ref } from 'vue';
import { headline } from '@/lib/str.js';
import DirtyIndicator from './DirtyIndicator.vue';

const props = defineProps<{
    name: string,
    label?: string;
    placeholder?: string;
    note?: string;
    required?: boolean;
}>();

const form = useFormContext();
const formData = inject<Record<string, any>>('formData');

const label = computed(() => props.label || headline(props.name));
const error = computed(() => form?.errors[props.name as keyof typeof form.errors] || '');
const isDirty = ref(false);

const updateDirty = (event: Event): void => {
    const value = (event.target as HTMLInputElement).value;

    isDirty.value = value !== String(formData?.[props.name] ?? '');
};
</script>

<template>
    <div class="space-y-1">
        <label class="label" :for="name">
            {{ label }}
            <span v-if="required" class="text-error"><Asterisk class="w-3" /></span>
        </label>

        <div class="input relative w-full pr-8" :class="{ 'border-error': error }">
            <input type="text"
                   :name
                   :placeholder
                   :defaultValue="formData?.[props.name]"
                   @input="updateDirty"
                   @change="form?.validate(props.name)" />

            <DirtyIndicator :visible="isDirty" class="right-3" />
        </div>

        <div v-if="error" class="text-xs text-error">
            {{ error }}
        </div>

        <div v-if="note" class="text-xs text-base-content/50">
            {{ note }}
        </div>
    </div>
</template>
