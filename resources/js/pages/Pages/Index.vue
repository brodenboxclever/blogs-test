<script setup lang="ts">
import { Settings } from '@lucide/vue';
import DataTable from '@/components/table/DataTable.vue';
import DataTableColumn from '@/components/table/DataTableColumn.vue';
import QuickToggleButton from '@/components/table/QuickToggleButton.vue';
import pages from '@/routes/pages';
import { edit, update } from '@/routes/pages/page/index.js';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pages',
                href: pages.page.index(),
            },
        ],
    },
});
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <DataTable :data="$page.props.pages.data"
                   :sort="$page.props.filters.sort_by"
                   :direction="$page.props.filters.sort_direction">
            <DataTableColumn v-slot="{row, value}" name="title" sortable>
                <span class="inline-block" :style="{'width': (row.depth * 10) + 'px'}"></span>
                <Link class="link link-hover link-primary" :href="edit(row).url">{{ value }}</Link>
            </DataTableColumn>

            <DataTableColumn v-slot="{value}" name="path" sortable>
                <code class="min-w-full w-20 block text-xs truncate">/{{ value }}</code>
            </DataTableColumn>

            <DataTableColumn name="created_at" label="Created On" sortable :size="200" />

            <DataTableColumn v-slot="{value, row, column}" name="is_enabled" label="" :size="0">
                <QuickToggleButton :value :name="column.name" :url="update(row).url" />
            </DataTableColumn>

            <DataTableColumn name="actions" label="" :size="0">
                <button class="btn btn-circle shadow-none border-gray-700 border">
                    <Settings />
                </button>
            </DataTableColumn>
        </DataTable>
    </div>
</template>
