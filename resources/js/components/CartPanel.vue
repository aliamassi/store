<template>
    <aside id="cart" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sticky top-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Your Cart</h2>
            <button v-if="items.length" class="text-sm text-red-600 hover:underline" @click="clear">Clear</button>
        </div>

        <p v-if="!items.length" class="text-gray-500 mt-2">Your cart is empty.</p>

        <ul v-else class="mt-3 space-y-3">
            <li v-for="line in items" :key="line.id" class="border rounded-lg p-3">
                <div class="flex justify-between">
                    <p class="font-medium">{{ line.name }}</p>
                    <p class="font-semibold">${{ (line.price * line.qty).toFixed(2) }}</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                    <span>Qty: {{ line.qty }}</span>
                    <button class="px-2 py-0.5 border rounded-md hover:bg-gray-50" @click="decrease(line.id)">-1</button>
                    <button class="px-2 py-0.5 border rounded-md hover:bg-gray-50 text-red-600" @click="removeLine(line.id)">remove</button>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">${{ line.price.toFixed(2) }} each</p>
            </li>
        </ul>

        <div class="mt-4 border-t pt-3" v-if="items.length">
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold">Total</p>
                <p class="text-lg font-bold">${{ total.toFixed(2) }}</p>
            </div>
            <div class="mt-3 flex gap-2">
                <a :href="waLink" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2">
                    Go WhatsApp
                </a>
            </div>
        </div>
    </aside>
</template>
<script setup>
import { computed } from 'vue'
import {useCart} from '../../composables/useCart.js'
const { items, total, decrease, removeLine, clear, buildWhatsAppLink } = useCart()
const waLink = computed(() => buildWhatsAppLink())
</script>
