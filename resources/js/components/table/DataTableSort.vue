<!-- DataColumn.vue -->
<template>
    <span v-if="false" />
</template>
<script setup>
import { inject, onMounted, onUnmounted, onUpdated, reactive, useSlots } from 'vue';
import { headline } from '@/lib/str';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: false,
    },
});

const slots = useSlots();

const registerColumn = inject('registerColumn');
const unregisterColumn = inject('unregisterColumn');

const column = reactive({
    name: props.name,
    label: props.label ?? headline(props.name),
    headerSlot: slots.header,
    cellSlot: slots.default,
});

onMounted(() => {
    unregisterColumn(props.name);
    registerColumn(column);
});

onUpdated(() => {
    column.name = props.name;
    column.label = props.label ?? headline(props.name);
    column.headerSlot = slots.header;
    column.cellSlot = slots.default;
});

onUnmounted(() => {
    unregisterColumn(props.name);
});
</script>
