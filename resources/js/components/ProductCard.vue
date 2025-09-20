<template>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
        <div class="aspect-square bg-gray-100 overflow-hidden">
            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
        </div>
        <div class="p-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold text-lg">{{ item.name }}</h3>
                    <p class="text-emerald-700 font-bold mt-1">${{ item.price.toFixed(2) }}</p>
                </div>
                <button class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-3 py-2" @click="$emit('add', item)">Add</button>
            </div>
        </div>
        <button class="w-full text-center text-sm text-emerald-600 hover:text-emerald-700 py-2 border-t" @click="$emit('add', item)">Click card to add</button>
    </div>
</template>
<script setup>
defineProps({ item: { type: Object, required: true } })
</script>
