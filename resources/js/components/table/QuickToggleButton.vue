<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Eye, EyeOff } from '@lucide/vue';
import { ref } from 'vue';

const props = defineProps<{
    url: string,
    value: boolean,
    name: string,
}>();

const value = ref(props.value);

const toggle = () => {
    router.patch(props.url, {
        [props.name]: ! props.value
    });
    value.value = !props.value;
};

</script>
<template>
    <button :class="{'btn-primary': value}" class="btn btn-square rounded-md shadow-none border-gray-700 border" @click="toggle">
        <component :is="value ? Eye : EyeOff" class="size-4" />
    </button>
</template>
