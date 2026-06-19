<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Rating</label>
            <star-rating v-model="form.rating" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input v-model="form.title" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Review</label>
            <textarea v-model="form.body" rows="4" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" :disabled="sending" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 disabled:opacity-50">
            {{ sending ? 'Submitting...' : 'Submit Review' }}
        </button>
    </form>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';

const props = defineProps({ productId: { type: Number, required: true } });
const emit = defineEmits(['submitted']);

const form = reactive({ rating: 0, title: '', body: '' });
const sending = ref(false);

async function submit() {
    sending.value = true;
    try {
        await axios.post(`/reviews/${props.productId}`, form);
        form.rating = 0; form.title = ''; form.body = '';
        emit('submitted');
    } finally { sending.value = false; }
}
</script>
