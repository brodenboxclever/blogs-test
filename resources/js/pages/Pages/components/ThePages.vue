<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CircleCheckBig, CircleOff } from '@lucide/vue';
import { edit } from '@/routes/pages/page';

const {pages} = defineProps({ pages: Object });
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
                <!-- row 1 -->
                <tr v-for="page in pages" :key="page.id">
                    <td>
                        <div :style="'padding-left: ' + (page.depth * 15) + 'px'">
                            <Link class="link link-hover link-primary" :href="edit(page.id)"><b>{{ page.title }}</b></Link>
                            <code class="block text-xs text-base-content/50">{{ page.path }}</code>
                        </div>
                    </td>

                    <td>{{ page.created_at }}</td>

                    <td>
                        <button class="btn btn-circle m-auto" :class="page.is_enabled ? 'btn-success' : 'btn-error'">
                            <CircleCheckBig v-if="page.is_enabled" />
                            <CircleOff v-if="!page.is_enabled" />
                        </button>
                    </td>

                    <td><Link class="btn" :href="edit(page.id)">Edit</Link></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
