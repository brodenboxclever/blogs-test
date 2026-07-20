<script setup lang="ts">
defineProps<{
    links: Record<string, any>
}>();
</script>

<template>
    <div class="w-full flex mt-4 justify-center">
        <div class="join">
            <template v-for="link in links" :key="link.label">
                <component :is="link.url ? 'Link' : 'span'"
                           :href="link.url"
                           class="join-item btn btn-ghost"
                           :class="{ 'text-gray-500 btn-disabled': !link.url, 'btn-primary': link.active }">
                    <span v-html="link.label"></span>
                </component>
            </template>
        </div>
    </div>
</template>
