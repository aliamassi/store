<template>
    <button
        class="px-4 py-2 border-b-2 font-medium whitespace-nowrap"
        :class="tab === modelValue ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-500 hover:text-gray-700'"
        @click="$emit('update:modelValue', tab)"
    ><slot /></button>
</template>
<script setup>
defineProps({ tab: { type: String, required: true }, modelValue: { type: String, required: true } })
</script>
