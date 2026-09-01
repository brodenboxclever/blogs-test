<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { MoreVertical, Search } from '@lucide/vue';
import { ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import DataTable from '@/components/table/DataTable.vue';
import DataTableColumn from '@/components/table/DataTableColumn.vue';
import QuickToggleButton from '@/components/table/QuickToggleButton.vue';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import { debounce } from '@/lib/debounce';
import blogs from '@/routes/namespaced/blogs';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blogs',
                href: blogs.blogs.index(),
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

    router.get(blogs.blogs.index(), data, {
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
                <Link class="link link-hover link-primary" :href="blogs.blogs.posts.index(row.id).url">{{ value }}</Link>
            </DataTableColumn>

            <DataTableColumn name="posts_count"
                             label="Posts"
                             class="text-center"
                             sortable />

            <DataTableColumn name="created_at" label="Created On" sortable class="w-50" />

            <DataTableColumn v-slot="{value, row, column}"
                             name="is_enabled"
                             label=""
                             class="w-0">
                <QuickToggleButton :value :name="column.name" :url="blogs.blogs.update(row).url" />
            </DataTableColumn>

            <DataTableColumn v-slot="{row}" name="actions" label="" class="w-0">
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <button aria-label="Blog actions" class="btn btn-ghost p-0 btn-primary">
                            <MoreVertical class="block aspect-square" />
                        </button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem>
                            <Link :href="blogs.blogs.posts.index(row.id).url">Manage Posts</Link>
                        </DropdownMenuItem>

                        <DropdownMenuItem>
                            <Link :href="blogs.blogs.edit(row).url">Manage Blog</Link>
                        </DropdownMenuItem>

                        <DropdownMenuItem>
                            <Link :href="blogs.blogs.show(row).url">Preview Blog</Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </DataTableColumn>
        </DataTable>

        <Pagination :meta="$page.props.blogs.meta" />
    </div>
</template>
