<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <Head :title="$page.props.title ?? $page.props.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
