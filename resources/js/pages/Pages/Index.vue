<script setup lang="ts">
import pages from '@/routes/pages';
import ThePages from './components/ThePages.vue';

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
        <ThePages :pages="$page.props.pages.data" />

        <!-- <Pagination :links="$page.props.pages.meta.links" /> -->
    </div>
</template>
