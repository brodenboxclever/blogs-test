<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { Search, Settings } from '@lucide/vue';
import { ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import DataTable from '@/components/table/DataTable.vue';
import DataTableColumn from '@/components/table/DataTableColumn.vue';
import QuickToggleButton from '@/components/table/QuickToggleButton.vue';
import { debounce } from '@/lib/debounce';
import blogs from '@/routes/blogs';
import { edit, index, update } from '@/routes/blogs/blog';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blogs',
                href: blogs.blog.index(),
            },
        ],
    },
});

const {props} = usePage();

const search = ref(props.filters?.q || '');
watch(search, debounce((q: string) => {
    const data: Record<string, string> = q ? { q } : {};
    data.sort_by = props.filters?.sort_by;
    data.sort_direction = props.filters?.sort_direction;

    router.get(index(), data, {
        preserveState: true,
        replace: true,
    });
}));
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <label class="input">
            <Search />

            <input v-model="search"
                   type="search"
                   name="q"
                   class="grow"
                   placeholder="Search" />
        </label>

        <DataTable :data="$page.props.blogs.data"
                   :sort="$page.props.filters.sort_by"
                   :direction="$page.props.filters.sort_direction">
            <DataTableColumn v-slot="{row, value}" name="title" sortable>
                <Link class="link link-hover link-primary" :href="edit(row).url">{{ value }}</Link>
            </DataTableColumn>

            <DataTableColumn name="created_at" label="Created On" sortable :size="200" />

            <DataTableColumn v-slot="{value, row, column}" name="is_enabled" label="">
                <QuickToggleButton :value :name="column.name" :url="update(row).url" />
            </DataTableColumn>

            <DataTableColumn name="actions" label="" :size="0">
                <button class="btn btn-circle shadow-none border-gray-700 border">
                    <Settings />
                </button>
            </DataTableColumn>
        </DataTable>

        <Pagination :links="$page.props.blogs.meta.links" />
    </div>
</template>
