<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import blogs from '@/routes/blogs';
import TheBlogs from './components/TheBlogs.vue';

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
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <TheBlogs :blogs="$page.props.blogs" />

        <Pagination :links="$page.props.blogs.meta.links" />
    </div>
</template>
