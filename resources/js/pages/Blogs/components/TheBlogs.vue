<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CircleCheckBig, CircleOff, ExternalLink } from '@lucide/vue';
import { edit } from '@/routes/blogs/blog';
import { edit as editPage } from '@/routes/pages/page';

const {blogs} = defineProps({ blogs: Object });
</script>

<template>
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
        <table class="table table-zebra table-fixed w-full min-w-full">
            <!-- head -->
            <thead>
                <tr>
                    <th class="text-xs font-normal w-md">Name</th>
                    <th class="text-xs font-normal">Page</th>
                    <th class="text-xs font-normal w-50">Created</th>
                    <th class="text-xs font-normal w-20">Enabled</th>
                    <th class="text-xs font-normal w-25"></th>
                </tr>
            </thead>

            <tbody>
                <!-- row 1 -->
                <tr v-for="blog in blogs?.data" :key="blog.id">
                    <td>
                        <Link class="link link-hover link-primary" :href="edit(blog)">
                            <b>{{ blog.title }}</b>
                        </Link>
                    </td>

                    <td>
                        <template v-if="blog.page">
                            <Link class="link block link-hover link-primary" :href="editPage(blog.page)">
                                <b>{{ blog.page.title }}</b>&nbsp;&nbsp;<ExternalLink class="inline w-3" />
                            </Link>

                            <span class="block w-full truncate text-xs text-base-content/40">{{ blog.page.path }}</span>
                        </template>
                    </td>

                    <td>{{ blog.created_at }}</td>

                    <td class="text-center w-0">
                        <button class="btn btn-circle m-auto" :class="blog.is_enabled ? 'btn-success' : 'btn-error'">
                            <CircleCheckBig v-if="blog.is_enabled" />
                            <CircleOff v-if="!blog.is_enabled" />
                        </button>
                    </td>

                    <td><Link class="btn" :href="edit(blog)">Edit</Link></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
