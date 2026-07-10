<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import pages from '@/routes/pages';

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
    <Head title="Pages" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
    </div>
</template>
