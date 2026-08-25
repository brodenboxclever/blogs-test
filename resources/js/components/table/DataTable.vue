<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { ArrowDownWideNarrow, ArrowUpWideNarrow, ListChevronsDownUp } from '@lucide/vue';
import { ref, provide } from 'vue';

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    sortUrl: {
        type: String,
        required: false,
        default: () => usePage().url,
    },
    sort: {
        type: String,
        default: null,
    },
    direction: {
        type: String,
        default: 'asc',
    },
    headless: {
        type: Boolean,
        default: false,
    }
});

const columns = ref([]);

provide('registerColumn', (column) => {
    columns.value.push(column);
});

provide('unregisterColumn', (name) => {
    columns.value = columns.value.filter(column => column.name !== name);
});

const sortBy = (name) => {
    const nextDirection = props.sort === name && props.direction === 'asc' ? 'desc' : 'asc';
    const url = new URL(props.sortUrl, window.location.origin);

    url.searchParams.set('sort_by', name);
    url.searchParams.set('sort_direction', nextDirection);
    url.searchParams.set('page', '1');

    router.get(url.pathname + url.search, {}, {
        preserveScroll: true,
        preserveState: true,
    });
};

const ariaSort = (column) => {
    if (props.sort !== column.name) {
        return 'none';
    }

    return props.direction === 'asc' ? 'ascending' : 'descending';
};

const sortIndicator = (name) => {
    if (props.sort !== name) {
        return ListChevronsDownUp;
    }

    return props.direction === 'asc' ? ArrowUpWideNarrow : ArrowDownWideNarrow;
};

</script>
<template>
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-auto">
        <table class="table bg-accent border text-accent-foreground w-full min-w-full">
            <thead v-if="!$props.headless">
                <tr>
                    <th v-for="(column, columnIndex) in columns"
                        :key="columnIndex"
                        :aria-sort="ariaSort(column)"
                        :style="{ 'width': column.size + 'px' }"
                        class="text-accent-foreground">
                        <button v-if="column.sortable" type="button" class="inline-flex items-center gap-2" @click="sortBy(column.name)">
                            <component :is="column.headerSlot" v-if="column.headerSlot" :column="column" />
                            <template v-else>{{ column.label }}</template>
                            <component :is="sortIndicator(column.name)" class="size-4" aria-hidden="true" />
                        </button>

                        <template v-else>
                            <component :is="column.headerSlot" v-if="column.headerSlot" :column="column" />
                            <template v-else>{{ column.label }}</template>
                        </template>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(row, rowIndex) in data" :key="rowIndex">
                    <td v-for="(column, columnIndex) in columns" :key="columnIndex">
                        <template v-if="column.cellSlot">
                            <component :is="column.cellSlot"
                                       :row-index
                                       :row
                                       :column-index
                                       :column
                                       :value="row[column.name]" />
                        </template>

                        <template v-else>
                            {{ row[column.name] }}
                        </template>
                    </td>
                </tr>
            </tbody>
        </table>

        <slot />
    </div>
</template>
