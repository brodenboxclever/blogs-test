<!-- DataColumn.vue -->
<template>
    <span v-if="false" />
</template>
<script setup>
import { inject, onMounted, onUnmounted, onUpdated, reactive, useAttrs, useSlots } from 'vue';
import { headline } from '@/lib/str';

const props = defineProps({
    name: {
        type: String,
        required: false,
    },
    label: {
        type: String,
        required: false,
    },
    size: {
        type: Number,
        required: false,
    },
    sortable: {
        type: Boolean,
        default: false,
    },
});

const slots = useSlots();
const attrs = useAttrs();

const registerColumn = inject('registerColumn');
const unregisterColumn = inject('unregisterColumn');

const column = reactive({
    name: props.name,
    size: props.size,
    label: props.label ?? headline(props.name),
    sortable: props.sortable,
    headerSlot: slots.header,
    cellSlot: slots.default,
    attrs
});

onMounted(() => {
    unregisterColumn(props.name);
    registerColumn(column);
});

onUpdated(() => {
    column.name = props.name;
    column.size = props.size;
    column.label = props.label ?? headline(props.name);
    column.sortable = props.sortable;
    column.headerSlot = slots.header;
    column.cellSlot = slots.default;
    column.attrs = attrs;
});

onUnmounted(() => {
    unregisterColumn(props.name);
});
</script>
