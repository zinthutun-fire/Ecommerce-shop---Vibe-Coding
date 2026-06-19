<template>
    <div class="bg-white rounded-lg shadow p-6">
        <canvas ref="canvas" height="200"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    labels: { type: Array, default: () => [] },
    data: { type: Array, default: () => [] },
    label: { type: String, default: 'Revenue' },
    color: { type: String, default: '#4F46E5' },
    type: { type: String, default: 'bar' },
});

const canvas = ref(null);
let chart = null;

function createChart() {
    if (chart) chart.destroy();
    if (!canvas.value) return;
    chart = new Chart(canvas.value, {
        type: props.type,
        data: {
            labels: props.labels,
            datasets: [{ label: props.label, data: props.data, backgroundColor: props.color, borderRadius: 4 }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

onMounted(createChart);
watch([() => props.labels, () => props.data], createChart);
</script>
