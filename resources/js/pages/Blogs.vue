<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import blogs from '@/routes/blogs';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blogs',
                href: blogs.index(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Blogs" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    </div>
</template>
