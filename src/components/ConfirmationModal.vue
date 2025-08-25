<template>
  <Modal :show="show" @close="$emit('close')" title="Confirm Action" subtitle="Are you sure you want to proceed?">
    <template #icon>
      <AlertTriangle class="w-6 h-6 text-red-600" />
    </template>
    <div>
      <p class="text-sm text-gray-600">{{ message }}</p>
      <div class="mt-6 flex justify-end gap-3">
        <button @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
          Cancel
        </button>
        <button @click="$emit('confirm')" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
          Confirm
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import Modal from '@/components/Modal.vue';
import { AlertTriangle } from 'lucide-vue-next';

defineProps({
  show: Boolean,
  message: {
    type: String,
    default: 'This action cannot be undone.'
  }
});
defineEmits(['close', 'confirm']);
</script>