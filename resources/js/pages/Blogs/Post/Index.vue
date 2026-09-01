<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { MoreVertical, Search } from '@lucide/vue';
import { ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';
import DataTable from '@/components/table/DataTable.vue';
import DataTableColumn from '@/components/table/DataTableColumn.vue';
import QuickToggleButton from '@/components/table/QuickToggleButton.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { debounce } from '@/lib/debounce';
import blogs from '@/routes/namespaced/blogs';

// Define the incoming props
defineProps<{
    blog: {
        id: number;
        title?: string;
    };
}>();

defineOptions({
    layout: (props: any) => ({
        breadcrumbs: [
            {
                title: 'Blogs',
                href: blogs.blogs.index(),
            },
            {
                title: props.blog.title ?? 'Posts',
                href: blogs.blogs.posts.index(props.blog.id),
            },
        ],
    }),
});

const page = usePage();

const search = ref(page.props.filters?.q || '');
watch(search, debounce((q: string) => {
    const data: Record<string, string> = q ? { q } : {};
    data.sort_by = page.props.filters?.sort_by;
    data.sort_direction = page.props.filters?.sort_direction;

    router.get(blogs.blogs.posts.index(page.props.blog.id), data, {
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

        <DataTable :data="$page.props.posts.data"
                   :sort="$page.props.filters.sort_by"
                   :direction="$page.props.filters.sort_direction">
            <DataTableColumn v-slot="{row, value}" name="title" sortable class="w-200">
                <Link class="link link-hover link-primary" :href="blogs.blogs.posts.edit({blog: row.blog.id, post: row.id}).url">{{ value }}</Link>
            </DataTableColumn>

            <DataTableColumn v-slot="{value}" name="slug" sortable class="w-0">
                <code class="min-w-full w-50 block text-xs truncate">/{{ value }}</code>
            </DataTableColumn>

            <DataTableColumn name="comments_count"
                             label="Comments"
                             class="text-center w-0"
                             sortable />

            <DataTableColumn name="created_at" label="Created On" sortable class="w-50" />

            <DataTableColumn v-slot="{value, row, column}" name="is_enabled" label="" class="w-0">
                <QuickToggleButton :value :name="column.name" :url="blogs.blogs.posts.update({blog: row.blog.id, post: row}).url" />
            </DataTableColumn>

            <DataTableColumn v-slot="{row}" name="actions" label="" class="w-0">
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <button class="btn btn-ghost p-0 btn-primary">
                            <MoreVertical class="block aspect-square" />
                        </button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem>
                            <Link :href="blogs.blogs.posts.edit([row.blog.id, row]).url">Manage Post</Link>
                        </DropdownMenuItem>

                        <DropdownMenuItem>
                            <Link :href="blogs.blogs.posts.show([row.blog.id, row]).url">Preview Post</Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </DataTableColumn>
        </DataTable>

        <Pagination :meta="$page.props.posts.meta" />
    </div>
</template>
