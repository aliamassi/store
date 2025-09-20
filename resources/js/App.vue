<template>
    <div class="max-w-6xl mx-auto p-4 md:p-6 lg:p-8">
        <header class="sticky top-0 z-50 bg-white shadow -mx-4 md:-mx-6 lg:-mx-8 px-4 md:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Café Menu</h1>
                <a href="#cart" @click.prevent="scrollToCart" class="btn-cart inline-flex items-center gap-2 text-sm font-medium">
                    <span class="count inline-flex items-center justify-center min-w-6 h-6 rounded-full bg-emerald-600 text-white text-xs px-2">{{ count }}</span>
                    <span>See Your Cart</span>
                    <span class="total font-semibold">${{ total.toFixed(2) }}</span>
                </a>
            </div>
            <div class="flex border-b border-gray-200 mt-4 overflow-x-auto">
                <TabButton v-for="(label, key) in tabLabels" :key="key" :tab="key" v-model="tab">{{ label }}</TabButton>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2">
                <section v-for="(label, key) in tabLabels" :key="key" v-show="tab === key">
                    <h2 class="text-xl font-semibold mb-3">{{ label }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <ProductCard v-for="item in (catalog[key] || [])" :key="item.id" :item="item" @add="add" />
                    </div>
                </section>
            </div>
            <CartPanel />
        </div>

        <div v-if="count > 0" id="mobile-cart-bar" class="fixed left-0 right-0 bottom-0 z-50 bg-black text-white px-4 py-3 md:hidden">
            <div class="inner flex items-center justify-between gap-3">
                <div class="title text-sm">Cart</div>
                <div class="spacer flex-1"></div>
                <div class="value text-sm">Items: <span class="value-count font-semibold">{{ count }}</span></div>
                <div class="divider w-px h-4 bg-white/20"></div>
                <div class="value text-sm">Total: <span class="value-total font-semibold">${{ total.toFixed(2) }}</span></div>
                <button class="btn-cart ml-2 inline-flex items-center justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-medium" @click="scrollToCart">See Cart</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import TabButton from './components/TabButton.vue'
import ProductCard from './components/ProductCard.vue'
import CartPanel from './components/CartPanel.vue'
import { useCart } from './composables/useCart'

const { add, count, total } = useCart()
const tab = ref('hot-drinks')
const tabLabels = {
    'hot-drinks': 'Hot Drinks',
    'waffles': 'Waffles',
    'ice-cream': 'Ice Cream',
    'juices': 'Juices',
}
const catalog = ref({})

function scrollToCart(){ document.getElementById('cart')?.scrollIntoView({ behavior: 'smooth', block: 'start' }) }

onMounted(async () => {
    const res = await fetch('/api/catalog')
    if (res.ok) catalog.value = await res.json()
})
</script>
