<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CircleCheckBig, CircleOff } from '@lucide/vue';
import { edit } from '@/routes/pages/page';

const { page } = defineProps<{
    page: {
        uuid: string;
        [key: string]: any;
    } | null;
}>();
</script>

<template>
    <template v-if="page">
        <tr>
            <td>
                <div class="flex flex-col w-full" :style="'padding-left: ' + (page.depth * 15) + 'px'">
                    <Link class="link link-hover link-primary" :href="edit(page.uuid)"><b>{{ page.title }}</b></Link>
                    <code class="min-w-full w-0 block text-xs text-base-content/50 truncate">/{{ page.path }}</code>
                </div>
            </td>

            <td>{{ page.created_at }}</td>

            <td>
                <button class="btn btn-circle m-auto" :class="page.is_enabled ? 'btn-success' : 'btn-error'">
                    <CircleCheckBig v-if="page.is_enabled" />
                    <CircleOff v-if="!page.is_enabled" />
                </button>
            </td>

            <td><Link class="btn" :href="edit(page.uuid)">Edit</Link></td>
        </tr>

        <PagesRow v-for="child in page.children" :key="child.id" :page="child" />
    </template>
</template>
