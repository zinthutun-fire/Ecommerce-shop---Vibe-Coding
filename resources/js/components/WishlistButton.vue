<template>
    <button @click="toggle" :disabled="loading" class="text-gray-400 hover:text-red-500 transition">
        <i :class="isWishlisted ? 'fas fa-heart text-red-500' : 'far fa-heart'" class="text-xl"></i>
    </button>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useWishlistStore } from '../stores/wishlist';

const props = defineProps({ productId: { type: Number, required: true }, initial: { type: Boolean, default: false } });
const emit = defineEmits(['toggled']);

const store = useWishlistStore();
const isWishlisted = ref(props.initial);
const loading = ref(false);

async function toggle() {
    loading.value = true;
    const result = await store.toggle(props.productId);
    isWishlisted.value = result;
    loading.value = false;
    emit('toggled', result);
}

watch(() => props.initial, (v) => isWishlisted.value = v);
</script>
