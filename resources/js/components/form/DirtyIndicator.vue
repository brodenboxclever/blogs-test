<script setup lang="ts">
defineOptions({ inheritAttrs: false });

defineProps<{
    visible: boolean;
}>();
</script>

<template>
    <Transition name="dirty-dot">
        <span v-if="visible"
              class="pointer-events-none absolute top-1/2 inline-block size-2 -translate-y-1/2 rounded-full bg-warning shadow-[0_0_6px] shadow-warning/70"
              v-bind="$attrs"
              aria-label="Unsaved changes"></span>
    </Transition>
</template>

<style scoped>
.dirty-dot-enter-active,
.dirty-dot-leave-active {
    transition: opacity 150ms ease, transform 150ms ease;
}

.dirty-dot-enter-from,
.dirty-dot-leave-to {
    opacity: 0;
    transform: scale(0.5);
}
</style>
