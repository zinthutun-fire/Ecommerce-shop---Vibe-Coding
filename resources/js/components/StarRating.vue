<template>
    <div class="flex items-center">
        <span v-for="i in 5" :key="i" @click="setRating(i)" class="cursor-pointer text-xl" :class="i <= modelValue ? 'text-yellow-400' : 'text-gray-300'">
            <i class="fas fa-star"></i>
        </span>
    </div>
</template>

<script setup>
const props = defineProps({ modelValue: { type: Number, default: 0 } });
const emit = defineEmits(['update:modelValue']);
function setRating(val) { emit('update:modelValue', val); }
</script>
