<script setup lang="ts">
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
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    </div>
</template>
