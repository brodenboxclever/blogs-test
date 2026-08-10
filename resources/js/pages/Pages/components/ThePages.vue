<script setup lang="ts">
import PagesRow from './PagesRow.vue';

const {pages} = defineProps({
    pages: Object
});
</script>

<template>
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
        <table class="table w-full min-w-full">
            <!-- head -->
            <thead>
                <tr>
                    <th class="w-80 min-w-80">Name</th>
                    <th class="w-50 min-w-50">Created</th>
                    <th class="w-0">Enabled</th>
                    <th class="w-[40px]"></th>
                </tr>
            </thead>

            <tbody>
                <template v-for="page in pages?.data" :key="page.id">
                    <PagesRow :page="page" />
                </template>
            </tbody>
        </table>
    </div>
</template>
