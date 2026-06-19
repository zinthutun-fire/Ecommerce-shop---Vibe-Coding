<template>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
        <div v-if="store.items.length === 0" class="text-center py-20">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl text-gray-500">Your cart is empty</h3>
            <a href="/products" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg">Continue Shopping</a>
        </div>
        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div v-for="item in store.items" :key="item.id" class="bg-white rounded-lg shadow p-4 mb-4 flex items-center">
                    <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box text-gray-400 text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="font-semibold">{{ item.product?.name || 'Product' }}</h3>
                        <p class="text-indigo-600 font-bold">${{ parseFloat(item.price).toFixed(2) }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button @click="store.updateItem(item.id, item.quantity - 1)" class="px-2 py-1 border rounded hover:bg-gray-100">-</button>
                        <span class="w-8 text-center">{{ item.quantity }}</span>
                        <button @click="store.updateItem(item.id, item.quantity + 1)" class="px-2 py-1 border rounded hover:bg-gray-100">+</button>
                    </div>
                    <div class="ml-4 text-right">
                        <p class="font-bold">${{ (item.quantity * item.price).toFixed(2) }}</p>
                        <button @click="store.removeItem(item.id)" class="text-red-500 text-sm">Remove</button>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 h-fit">
                <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                <div class="flex justify-between mb-2"><span>Subtotal</span><span>${{ store.total.toFixed(2) }}</span></div>
                <div v-if="store.discount > 0" class="flex justify-between mb-2 text-green-600">
                    <span>Discount</span><span>-${{ store.discount.toFixed(2) }}</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between text-xl font-bold"><span>Total</span><span>${{ (store.total - store.discount).toFixed(2) }}</span></div>
                <a href="/checkout" class="block mt-4 bg-indigo-600 text-white text-center px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCartStore } from '../stores/cart';

const store = useCartStore();
onMounted(() => store.fetchCart());
</script>
