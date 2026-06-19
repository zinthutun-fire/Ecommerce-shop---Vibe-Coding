<template>
    <div class="relative">
        <button @click="toggle" class="relative text-gray-600 hover:text-indigo-600">
            <i class="fas fa-shopping-cart text-xl"></i>
            <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ store.count }}</span>
        </button>
        <div v-if="open" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50">
            <div class="p-4">
                <h3 class="font-bold mb-2">Cart ({{ store.count }})</h3>
                <div v-if="store.items.length === 0" class="text-gray-500 text-sm py-4 text-center">Cart is empty</div>
                <div v-for="item in store.items" :key="item.id" class="flex items-center justify-between py-2 border-b">
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ item.product?.name || 'Product' }}</p>
                        <p class="text-xs text-gray-500">{{ item.quantity }} x ${{ item.price }}</p>
                    </div>
                    <p class="text-sm font-bold">${{ (item.quantity * item.price).toFixed(2) }}</p>
                </div>
                <div class="mt-2 flex justify-between font-bold">
                    <span>Total</span>
                    <span>${{ store.total.toFixed(2) }}</span>
                </div>
                <a href="/cart" class="block mt-3 bg-indigo-600 text-white text-center px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">View Cart</a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useCartStore } from '../stores/cart';

const store = useCartStore();
const open = ref(false);

function toggle() { open.value = !open.value; }
</script>
