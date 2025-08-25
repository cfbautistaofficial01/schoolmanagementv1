<template>
  <Bar v-if="chartData" :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Define the props the component will accept
defineProps({
  chartData: {
    type: Object,
    required: true
  }
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: { color: '#6b7280' },
      grid: { color: '#e5e7eb' }
    },
    x: {
      ticks: { color: '#6b7280' },
      grid: { display: false }
    }
  },
}
</script>