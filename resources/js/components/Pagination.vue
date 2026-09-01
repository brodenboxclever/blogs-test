<script setup lang="ts">

defineProps<{
    meta: Record<string, any>
}>();
</script>

<template>
    <div class="flex justify-between items-center gap-4">
        <span v-if="meta.total">
            Showing <span class="font-medium">{{ meta.from }}</span>
            to <span class="font-medium">{{ meta.to }}</span>
            of <span class="font-medium">{{ meta.total }}</span>

            <template v-for="(link, index) in meta.links" :key="`${link.label}-${index}`">
            </template></span>

        <span v-else>
            No results.
        </span>

        <div class="flex mt-4 justify-center">
            <div class="join">
                <template v-for="link in meta.links" :key="link.label">
                    <component :is="link.url ? 'Link' : 'span'"
                               :href="link.url"
                               class="join-item btn btn-ghost"
                               :class="{ 'text-gray-300 btn-disabled': !link.url, 'btn-primary': link.active }">
                        <span v-html="link.label"></span>
                    </component>
                </template>
            </div>
        </div>
    </div>
</template>
