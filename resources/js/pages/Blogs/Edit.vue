<script setup lang="ts">
import { Form, useForm } from '@inertiajs/vue3';
import { provide } from 'vue';
import { update } from '@/actions/Modules/Blogs/Http/Controllers/BlogController';
import SelectField from '@/components/form/SelectField.vue';
import TextField from '@/components/form/TextField.vue';
import blogs from '@/routes/namespaced/blogs/blogs/index.js';

// Define the incoming props
const props = defineProps<{
    blog: Record<string, any>
}>();

defineOptions({
    layout: (props: any) => ({
        breadcrumbs: [
            {
                title: 'Blogs',
                href: blogs.index(),
            },
            {
                title: 'Manage Blog',
                href: blogs.posts.index(props.blog.id),
            },
        ],
    }),
});

useForm({
    title: props.blog.title,
    is_enabled: props.blog.is_enabled,
});

provide('formData', props.blog);

</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <Form method="post" :action="update()" :options="{preserveScroll: true }">
            <div class="space-y-4">
                <TextField name="title" required />

                <SelectField name="is_enabled" :options="{'Yes': true, 'No': false}" />
            </div>

            <button type="submit" class="btn btn-primary block mt-6 ml-auto">Submit</button>
        </Form>
    </div>
</template>

