<template>
  <transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="show" class="fixed top-6 right-6 z-50 w-full max-w-sm">
      <div class="p-4 rounded-lg shadow-lg" :class="type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <CheckCircle v-if="type === 'success'" class="h-6 w-6 text-white" />
            <XCircle v-else class="h-6 w-6 text-white" />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium">{{ message }}</p>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { CheckCircle, XCircle } from 'lucide-vue-next';
defineProps({
  show: Boolean,
  message: String,
  type: {
    type: String,
    default: 'success', // can be 'success' or 'error'
  },
});
</script>